<?php

namespace CourierDZ\ProviderIntegrations;

use CourierDZ\Contracts\ShippingProviderContract;
use CourierDZ\Exceptions\CreateOrderException;
use CourierDZ\Exceptions\CreateOrderValidationException;
use CourierDZ\Exceptions\CredentialsException;
use CourierDZ\Exceptions\HttpException;
use CourierDZ\Exceptions\TrackingIdNotFoundException;
use CourierDZ\Support\ShippingProviderValidation;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;

abstract class YalidineProviderIntegration implements ShippingProviderContract
{
    use ShippingProviderValidation;

    /**
     * Provider credentials
     */
    protected array $credentials;

    /**
     * Validation rules for creating an order
     */
    public array $getCreateOrderValidationRules = [
        'order_id' => 'required|string',
        'from_wilaya_name' => 'required|string',
        'firstname' => 'required|string',
        'familyname' => 'required|string',
        'contact_phone' => 'required|string',
        'address' => 'required|string',
        'to_commune_name' => 'required|string',
        'to_wilaya_name' => 'required|string',
        'product_list' => 'required|array',
        'Price' => 'required|numeric|min:0|max:150000',
        'do_insurance' => 'required|boolean',
        'declared_value' => 'required|numeric|min:0|max:150000',
        'Length' => 'required|numeric|min:0',
        'Width' => 'required|numeric|min:0',
        'Height' => 'required|numeric|min:0',
        'Weight' => 'required|numeric|min:0',
        'freeshipping' => 'required|boolean',
        'is_stopdesk' => 'required|boolean',
        'stopdesk_id' => 'required_if:is_stopdesk,true|string',
        'has_exchange' => 'required|boolean',
        'product_to_collect' => 'required|boolean',
    ];

    /**
     * Constructor
     *
     * @throws CredentialsException
     */
    public function __construct(array $credentials)
    {
        // Get the provider name from the metadata
        $provider_name = static::metadata()['name'];

        // Check if the credentials are valid
        if (! isset($credentials['id']) || ! isset($credentials['token'])) {
            throw new CredentialsException($provider_name.' credentials must include "id" and "token".');
        }

        // Set the credentials
        $this->credentials = $credentials;
    }

    /**
     * Get provider metadata
     */
    abstract public static function metadata(): array;

    /**
     * Get the API domain
     */
    abstract public static function apiDomain(): string;

    /**
     * Test credentials
     *
     * Makes a GET request to the /wilayas endpoint to check if the credentials are valid.
     * If the request is successful (200 status code), the credentials are valid.
     * If the request returns a 401 or 500 status code, the credentials are invalid.
     * Any other status code is considered an unexpected error.
     *
     * @throws HttpException
     */
    public function testCredentials(): bool
    {
        try {
            // Initialize Guzzle client
            $client = new Client(['http_errors' => false]);

            // Define the headers
            $headers = [
                'X-API-ID' => $this->credentials['id'],
                'X-API-TOKEN' => $this->credentials['token'],
            ];

            // Make the GET request
            $response = $client->request('GET', $this->apiDomain().'/v1/wilayas/', [
                'headers' => $headers,
            ]);

            // If the request is successful, the credentials are valid
            if ($response->getStatusCode() === 200) {
                return true;
            }

            // If the request returns a 401 or 500 status code, the credentials are invalid
            if (in_array($response->getStatusCode(), [401, 500])) {
                return false;
            }

            // Any other status code is considered an unexpected error
            throw new HttpException('Yalidine, Unexpected error occurred.');
        } catch (GuzzleException $e) {
            // Handle exceptions
            throw new HttpException($e->getMessage());
        }
    }

    /**
     * Get rates
     *
     * @param  int  $from_wilaya_id
     * @param  int  $to_wilaya_id
     *
     * @throws HttpException
     */
    public function getRates($from_wilaya_id, $to_wilaya_id): array
    {
        try {
            // Initialize Guzzle client
            $client = new Client(['http_errors' => false]);

            // Define the headers
            $headers = [
                'X-API-ID' => $this->credentials['id'],
                'X-API-TOKEN' => $this->credentials['token'],
            ];

            // Make the GET request
            $response = $client->request('GET', $this->apiDomain().'/v1/fees/?from_wilaya_id='.$from_wilaya_id.'&to_wilaya_id='.$to_wilaya_id, [
                'headers' => $headers,
            ]);

            // Return the response body as an array
            return json_decode($response->getBody()->getContents(), true);

        } catch (GuzzleException $e) {
            // Handle exceptions
            throw new HttpException($e->getMessage());
        }
    }

    public function getCreateOrderValidationRules(): array
    {
        return $this->getCreateOrderValidationRules;
    }

    /**
     * Create order
     *
     * This method creates an order with the given order data.
     * It makes a POST request to the Yalidine API with the given order data.
     * If the request is successful, it returns the created order.
     * If the request fails, it throws an HttpException.
     * If the order creation fails, it throws a CreateOrderException.
     *
     * @param  array  $orderData  The order data to create an order with
     * @return array The created order
     *
     * @throws CreateOrderValidationException If the order data does not pass validation
     * @throws CreateOrderException If the order creation fails
     * @throws HttpException If the request fails
     */
    public function createOrder(array $orderData): array
    {
        $this->validateCreate($orderData);

        try {
            // Initialize Guzzle client
            $client = new Client;

            // Define the headers
            $headers = [
                'X-API-ID' => $this->credentials['id'],
                'X-API-TOKEN' => $this->credentials['token'],
                'Content-Type' => 'application/json',
            ];

            $request = new Request('POST', $this->apiDomain().'/v1/parcels/', $headers, [$orderData]);

            $response = $client->send($request);

            // Get the response body
            $body = $response->getBody()->getContents();

            $arrayResponse = json_decode($body, true);

            $message = $arrayResponse[$orderData['id']]['message'];

            // Check if the order creation was successful
            if ($arrayResponse[$orderData['id']]['status'] !== 'true') {
                throw new CreateOrderException('Create Order failed ( `'.$message.'` ) : '.implode(' ', $arrayResponse[$orderData['id']]));
            }

            // Return the created order
            return $arrayResponse[$orderData['id']];

        } catch (GuzzleException $e) {
            // Handle exceptions
            throw new HttpException($e->getMessage());
        }
    }

    /**
     * Get order label
     *
     * @return array Associative array with the following keys:
     *               - type: string, 'url'
     *               - data: string, the PDF label URL
     *
     * @throws TrackingIdNotFoundException
     * @throws HttpException
     */
    public function orderLabel(string $orderId): array
    {
        // Get order details
        $order = $this->getOrder($orderId);

        // Return the label URL as an associative array
        return [
            'type' => 'url',
            'data' => $order['label'],
        ];
    }

    /**
     * Read order details
     *
     * @throws HttpException
     * @throws TrackingIdNotFoundException
     */
    public function getOrder(string $trackingId): array
    {
        try {
            // Initialize Guzzle client
            $client = new Client(['http_errors' => false]);

            // Define the headers
            $headers = [
                'X-API-ID' => $this->credentials['id'],
                'X-API-TOKEN' => $this->credentials['token'],
            ];

            // Make the GET request
            $response = $client->request('GET', 'https://api.yalidine.app/v1/parcels/'.$trackingId, [
                'headers' => $headers,
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if ($data['total_data'] == 0) {
                throw new TrackingIdNotFoundException('Tracking ID not found : '.$trackingId.' , Provider : Yalidine');
            }

            return $data['data'][0];

        } catch (GuzzleException $e) {
            // Handle exceptions
            throw new HttpException($e->getMessage());
        }
    }
}
