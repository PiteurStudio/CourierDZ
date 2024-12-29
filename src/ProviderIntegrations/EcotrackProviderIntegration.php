<?php

namespace CourierDZ\ProviderIntegrations;

use CourierDZ\Contracts\ShippingProviderContract;
use CourierDZ\Exceptions\CreateOrderException;
use CourierDZ\Exceptions\CredentialsException;
use CourierDZ\Exceptions\HttpException;
use CourierDZ\Exceptions\NotImplementedException;
use CourierDZ\Exceptions\TrackingIdNotFoundException;
use CourierDZ\Support\ShippingProviderValidation;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;

abstract class EcotrackProviderIntegration implements ShippingProviderContract
{
    use ShippingProviderValidation;

    /**
     * Provider credentials
     *
     * @var array<non-empty-string, non-empty-string>
     */
    protected array $credentials;

    /**
     * Validation rules for creating an order
     *
     * @var array<non-empty-string, non-empty-string>
     */
    public array $getCreateOrderValidationRules = [
        'reference' => 'nullable|string|max:255',
        'nom_client' => 'required|string|max:255',
        'telephone' => 'required|numeric|digits_between:9,10',
        'telephone_2' => 'nullable|numeric|digits_between:9,10',
        'adresse' => 'required|string|max:255',
        'code_postal' => 'nullable|numeric',
        'commune' => 'required|string|max:255',
        'code_wilaya' => 'required|numeric|min:1|max:58',
        'montant' => 'required|numeric',
        'remarque' => 'nullable|string|max:255',
        'produit' => 'nullable|string|max:255',
        'stock' => 'integer|in:0,1',
        'quantite' => 'required_if:stock,1|integer|min:1',
        'produit_a_recupere' => 'nullable|string|max:255',
        'boutique' => 'nullable|string|max:255',
        'type' => 'required|integer|in:1,2,3,4', // Type de l'operation *[ 1 = Livraison , 2 = Echange , 3 = PICKUP , 4 = Recouvrement ]* | integer , entre 1 et 4 , **obligatoire**
        'stop_desk' => 'nullable|in:0,1',
    ];

    /**
     * EcotrackProviderIntegration constructor.
     *
     * @param  array<non-empty-string, non-empty-string>  $credentials  An array of credentials for the provider, containing the 'token' key
     *
     * @throws CredentialsException If the credentials do not contain the 'token' key
     */
    public function __construct(array $credentials)
    {
        $provider_name = (static::metadata())['name'];

        if (! isset($credentials['token'])) {
            throw new CredentialsException("{$provider_name} credentials must include 'token'.");
        }

        $this->credentials = $credentials;
    }

    abstract public static function metadata(): array;

    abstract public static function apiDomain(): string;

    /**
     * Test the credentials
     *
     * This method tests the credentials by making a GET request
     * to the Ecotrack API to retrieve the list of wilayas.
     *
     * If the request is successful, the method returns true.
     * If the request returns a 401 or 403 status code, the method returns false.
     * If the request returns any other status code, the method throws an HttpException.
     *
     * @throws HttpException If the request fails
     */
    public function testCredentials(): bool
    {
        try {
            // Initialize Guzzle client
            $client = new Client(['http_errors' => false]);

            // Define the headers
            $headers = [
                'Authorization' => "Bearer {$this->credentials['token']}",
            ];

            // Make the GET request
            $response = $client->request('GET', $this->apiDomain().'api/v1/get/wilayas', [
                'headers' => $headers,
                'Content-Type' => 'application/json',
            ]);

            // Check the status code
            switch ($response->getStatusCode()) {
                case 200:
                    // If the request is successful, return true
                    return true;
                case 401:
                case 403:
                    // If the request returns a 401 or 403 status code, return false
                    return false;
                default:
                    // If the request returns any other status code, throw an HttpException
                    throw new HttpException('Ecotrack '.$this->metadata()['name'].', Unexpected error occurred.');
            }
        } catch (GuzzleException $e) {
            // Handle exceptions
            throw new HttpException($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRates(?int $from_wilaya_id, ?int $to_wilaya_id): array
    {
        try {
            // Initialize Guzzle client
            $client = new Client;

            // Define the headers
            $headers = [
                'Authorization' => "Bearer {$this->credentials['token']}",
            ];

            // Make the GET request
            $response = $client->request('GET', $this->apiDomain().'api/v1/get/fees', [
                'headers' => $headers,
                'Content-Type' => 'application/json',
            ]);

            // Get the response body
            $body = $response->getBody()->getContents();

            // Decode the response body
            $result = json_decode($body, true);

            // If the to_wilaya_id is specified, filter the result to only include the specified wilaya
            if ($to_wilaya_id) {
                foreach ($result['livraison'] as $wilaya) {
                    if ($wilaya['wilaya_id'] == $to_wilaya_id) {
                        // Return the first matching wilaya
                        return $wilaya;
                    }
                }

                // If no matching wilaya is found, return an empty array
                return [];
            }

            // Return the list of shipping rates
            return $result['livraison'];

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
     * {@inheritdoc}
     */
    public function createOrder(array $orderData): array
    {
        // Validate the order data
        $this->validateCreate($orderData);

        // Prepare the request body
        $data = $orderData;

        $requestBody = json_encode($data, JSON_UNESCAPED_UNICODE);

        if($requestBody === false) {
            throw new CreateOrderException('Failed to encode order data to JSON.');
        }

        try {
            // Initialize Guzzle client
            $client = new Client;

            // Define the headers
            $headers = [
                'Authorization' => "Bearer {$this->credentials['token']}",
                'Content-Type' => 'application/json',
            ];

            // Make the POST request
            $request = new Request('POST', $this->apiDomain().'api/v1/create/order', $headers, $requestBody);

            $response = $client->send($request);

            // Get the response body
            $body = $response->getBody()->getContents();

            // Decode the response body
            $arrayResponse = json_decode($body, true);

            // Check if the order creation was successful
            if ($arrayResponse['success'] === false) {
                throw new CreateOrderException('Create Order failed: '.$arrayResponse['message']);
            }

            // Return the order response
            return $arrayResponse;

        } catch (GuzzleException $e) {
            // Handle exceptions
            throw new HttpException($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function orderLabel(string $orderId): array
    {
        try {
            // Initialize Guzzle client
            $client = new Client(['http_errors' => false]);

            // Define the headers
            $headers = [
                'Authorization' => "Bearer {$this->credentials['token']}",
            ];

            // Make the GET request
            $response = $client->request('GET', $this->apiDomain().'api/v1/get/order/label?tracking='.$orderId, [
                'headers' => $headers,
                'Content-Type' => 'application/json',
            ]);

            // Check if the request was successful
            if ($response->getStatusCode() !== 200) {
                // Check if the request failed because the tracking ID was not found
                if ($response->getStatusCode() === 422) {
                    throw new TrackingIdNotFoundException('Tracking ID not found in Ecotrack.');
                }

                // Handle any other error
                throw new HttpException('Failed to retrieve label for order with tracking ID '.$orderId);
            }

            // Get the response body
            $label = $response->getBody()->getContents();

            if(empty($label)){
                throw new HttpException('Failed to retrieve label for order with tracking ID '.$orderId.' - Empty response from Ecotrack');
            }

            $base64data = base64_encode($label);

            if ($base64data === '') {
                throw new \RuntimeException('Unexpected empty base64 string');
            }

            // Return the label details
            return [
                'type' => 'pdf',
                'data' => $base64data,
            ];

        } catch (GuzzleException $e) {
            // Handle exceptions
            throw new HttpException($e->getMessage());
        }
    }

    /**
     * Get order details
     *
     * @throws NotImplementedException
     */
    public function getOrder(string $trackingId): array
    {
        throw new NotImplementedException('Not implemented');
    }
}
