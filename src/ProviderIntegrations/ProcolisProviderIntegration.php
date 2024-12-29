<?php

namespace CourierDZ\ProviderIntegrations;

use CourierDZ\Contracts\ShippingProviderContract;
use CourierDZ\Exceptions\CreateOrderException;
use CourierDZ\Exceptions\CreateOrderValidationException;
use CourierDZ\Exceptions\CredentialsException;
use CourierDZ\Exceptions\FunctionNotSupportedException;
use CourierDZ\Exceptions\HttpException;
use CourierDZ\Exceptions\TrackingIdNotFoundException;
use CourierDZ\Support\ShippingProviderValidation;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;

abstract class ProcolisProviderIntegration implements ShippingProviderContract
{
    use ShippingProviderValidation;

    private array $credentials;

    public array $getCreateOrderValidationRules = [
        'Tracking' => 'nullable|string',
        'TypeLivraison' => 'in:0,1', // Domicile : 0 & Stopdesk : 1
        'TypeColis' => 'in:0,1', // Echange : 1
        'Confrimee' => 'required|in:0,1', // 1 pour les colis Confirmer directement en pret a expedier ( note : if empty zr will set it to 1 because if that field is required )
        'Client' => 'required|string',
        'MobileA' => 'required|string',
        'MobileB' => 'nullable|string',
        'Adresse' => 'required|string',
        'IDWilaya' => 'required|numeric',
        'Commune' => 'required|string',
        'Total' => 'required|numeric',
        'Note' => 'nullable|string',
        'TProduit' => 'required|string',
        'id_Externe' => 'nullable|string', // Votre ID ou Tracking
        'Source' => 'nullable|string',
    ];

    /**
     * Create a new instance of the Procolis provider integration.
     *
     * @param  array  $credentials  An array of credentials for the provider, containing the 'token' and 'key' keys
     *
     * @throws CredentialsException If the credentials do not contain the 'token' and 'key' keys
     */
    public function __construct(array $credentials)
    {
        // Check if the credentials contain the 'token' and 'key' keys
        if (! isset($credentials['token']) || ! isset($credentials['key'])) {
            throw new CredentialsException('Procolis credentials must include "token" and "key".');
        }

        // Store the credentials
        $this->credentials = $credentials;
    }

    // test credentials method

    /**
     * Tests the credentials by making a GET request to the Procolis API to retrieve
     * the token status. If the request is successful, the method returns true. If
     * the request returns a 401 status code, the method returns false. If the
     * request returns any other status code, the method throws an HttpException.
     *
     * @throws HttpException If the request fails
     */
    public function testCredentials(): bool
    {
        try {
            // Initialize Guzzle client
            $client = new Client;

            // Define the headers
            $headers = [
                'token' => $this->credentials['token'],
                'key' => $this->credentials['key'],
            ];

            // Make the GET request
            $response = $client->request('GET', 'https://procolis.com/api_v1/token', [
                'headers' => $headers,
            ]);

            // Get the response body
            $body = $response->getBody()->getContents();

            // Decode JSON response
            $data = json_decode($body, true);

            // Check the status code
            switch ($response->getStatusCode()) {
                case 200:
                    // If the request is successful, return true
                    return $data['Statut'] === 'Accès activé';
                case 401:
                    // If the request returns a 401 status code, return false
                    return false;
                default:
                    // If the request returns any other status code, throw an HttpException
                    throw new HttpException('Procolis, Unexpected error occurred.');
            }
        } catch (GuzzleException $e) {
            // Handle exceptions
            throw new HttpException($e->getMessage());
        }

    }

    /**
     * Gets shipping rates for every wilaya or for a specific wilaya.
     *
     * @param  int|null  $from_wilaya_id  The ID of the wilaya to get rates from
     * @param  int|null  $to_wilaya_id  The ID of the wilaya to get rates to
     * @return array An array of shipping rates, each containing the price, and wilaya IDs
     *
     * @throws HttpException If the request fails
     */
    public function getRates(?int $from_wilaya_id, ?int $to_wilaya_id): array
    {
        try {
            // Initialize Guzzle client
            $client = new Client;

            // Define the headers
            $headers = [
                'token' => $this->credentials['token'],
                'key' => $this->credentials['key'],
                'Content-Type' => 'application/json',
            ];

            // Make the GET request
            $response = $client->request('POST', 'https://procolis.com/api_v1/tarification', [
                'headers' => $headers,
            ]);

            // Get the response body
            $body = $response->getBody()->getContents();

            $result = json_decode($body, true);

            // If the to_wilaya_id is specified, filter the result to only include the specified wilaya
            if ($to_wilaya_id) {
                $filteredResult = [];
                foreach ($result as $wilaya) {
                    if ($wilaya['IDWilaya'] == $to_wilaya_id) {
                        $filteredResult = $wilaya;
                        break;
                    }
                }

                // If no matching wilaya is found, return an empty array
                if (empty($filteredResult)) {
                    return [];
                }

                // Return the first matching wilaya
                return $filteredResult;
            }

            // Decode JSON response
            return $result;

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
     * Create an order with the given order data.
     *
     * @param  array  $orderData  The order data to create an order with
     * @return array The created order
     *
     * @throws CreateOrderValidationException If the order data does not pass validation
     * @throws HttpException If the request fails
     * @throws CreateOrderException If the order creation fails
     */
    public function createOrder(array $orderData): array
    {
        // Validate the order data
        $this->validateCreate($orderData);

        // Prepare the request body
        $data = [
            'Colis' => [
                $orderData,
            ],
        ];

        $requestBody = json_encode($data, JSON_UNESCAPED_UNICODE);

        try {
            // Initialize Guzzle client
            $client = new Client;

            // Define the headers
            $headers = [
                'token' => $this->credentials['token'],
                'key' => $this->credentials['key'],
                'Content-Type' => 'application/json',
            ];

            $request = new Request('POST', 'https://procolis.com/api_v1/add_colis', $headers, $requestBody);

            $response = $client->send($request);

            // Get the response body
            $body = $response->getBody()->getContents();

            $arrayResponse = json_decode($body, true);

            $message = $arrayResponse['Colis'][0]['MessageRetour'];

            // Check if the order creation was successful
            if ($message === 'Double Tracking') {
                throw new CreateOrderException('Create Order failed ( Duplicate `Tracking` ) : '.implode(' ', $arrayResponse['Colis'][0]));
            }

            if ($message !== 'Good') {

                throw new CreateOrderException('Create Order failed ( `'.$message.'` ) : '.implode(' ', $arrayResponse['Colis'][0]));
            }

            // Return the created order
            return $arrayResponse['Colis'][0];

        } catch (GuzzleException $e) {
            // Handle exceptions
            throw new HttpException($e->getMessage());
        }
    }

    /**
     * Retrieves an order from the Procolis API using the given tracking ID.
     *
     * @param  string  $trackingId  The tracking ID of the order to retrieve.
     * @return array The retrieved order.
     *
     * @throws TrackingIdNotFoundException If the tracking ID is not found.
     * @throws HttpException If the request fails.
     */
    public function getOrder(string $trackingId): array
    {
        $data = [
            'Colis' => [
                ['Tracking' => $trackingId],
            ],
        ];

        $requestBody = json_encode($data, JSON_UNESCAPED_UNICODE);

        try {
            // Initialize Guzzle client
            $client = new Client;

            // Define the headers
            $headers = [
                'token' => $this->credentials['token'],
                'key' => $this->credentials['key'],
                'Content-Type' => 'application/json',
            ];

            $request = new Request('POST', 'https://procolis.com/api_v1/lire', $headers, $requestBody);

            $response = $client->send($request);

            // Get the response body
            $body = $response->getBody()->getContents();

            if ($body === 'null') {
                throw new TrackingIdNotFoundException('Tracking ID not found : '.$trackingId.' , Provider : Procolis');
            }

            $arrayResponse = json_decode($body, true);

            // Decode JSON response
            return $arrayResponse['Colis'][0];

        } catch (GuzzleException $e) {
            // Handle exceptions
            throw new HttpException($e->getMessage());
        }
    }

    /**
     * @throws FunctionNotSupportedException
     */
    public function cancelOrder(string $orderId): bool
    {
        throw new FunctionNotSupportedException('Cancel order is not supported by Procolis.');
    }

    /**
     * @throws FunctionNotSupportedException
     */
    public function orderLabel(string $orderId): array
    {
        throw new FunctionNotSupportedException('orderLabel is not supported by Procolis.');
    }

    /**
     * Retrieve metadata for the provider.
     *
     * This method returns an array containing metadata details about
     * the Procolis provider, such as its name, supported features, etc.
     *
     * @return array An array containing the metadata of the provider.
     */
    abstract public static function metadata(): array;
}
