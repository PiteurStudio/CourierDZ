<?php

declare(strict_types=1);

namespace CourierDZ\ProviderIntegrations;

use CourierDZ\Contracts\ShippingProviderContract;
use CourierDZ\Exceptions\CreateOrderException;
use CourierDZ\Exceptions\CredentialsException;
use CourierDZ\Exceptions\FunctionNotSupportedException;
use CourierDZ\Exceptions\HttpException;
use CourierDZ\Exceptions\TrackingIdNotFoundException;
use CourierDZ\Support\ShippingProviderValidation;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use http\Exception\InvalidArgumentException;

abstract class ProcolisProviderIntegration implements ShippingProviderContract
{
    use ShippingProviderValidation;

    /**
     * Provider credentials
     *
     * @var array<non-empty-string, non-empty-string>
     */
    private array $credentials;

    /**
     * Validation rules for creating an order
     *
     * @var array<non-empty-string, non-empty-string>
     */
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
     * @param  array<non-empty-string, non-empty-string>  $credentials  An array of credentials for the provider, containing the 'token' and 'key' keys
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
            return match ($response->getStatusCode()) {
                // If the request is successful, return true
                200 => $data['Statut'] === 'Accès activé',
                // If the request returns a 401 status code, return false
                401 => false,
                // If the request returns any other status code, throw an HttpException
                default => throw new HttpException('Procolis, Unexpected error occurred.'),
            };
        } catch (GuzzleException $guzzleException) {
            // Handle exceptions
            throw new HttpException($guzzleException->getMessage());
        }

    }

    /**
     * {@inheritdoc}
     */
    public function getRates(?int $from_wilaya_id = null, ?int $to_wilaya_id = null): array
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
            if ($to_wilaya_id !== null && $to_wilaya_id !== 0) {
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

        } catch (GuzzleException $guzzleException) {
            // Handle exceptions
            throw new HttpException($guzzleException->getMessage());
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
        $data = [
            'Colis' => [
                $orderData,
            ],
        ];

        $requestBody = json_encode($data, JSON_UNESCAPED_UNICODE);

        if ($requestBody === false) {
            throw new CreateOrderException('Create Order failed ( JSON Encoding Error ) : '.json_last_error_msg());
        }

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

        } catch (GuzzleException $guzzleException) {
            // Handle exceptions
            throw new HttpException($guzzleException->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder(string $trackingId): array
    {
        $data = [
            'Colis' => [
                ['Tracking' => $trackingId],
            ],
        ];

        $requestBody = json_encode($data, JSON_UNESCAPED_UNICODE);

        if ($requestBody === false) {
            throw new InvalidArgumentException('$trackingId must be a non-empty string');
        }

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

        } catch (GuzzleException $guzzleException) {
            // Handle exceptions
            throw new HttpException($guzzleException->getMessage());
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
     * {@inheritdoc}
     */
    abstract public static function metadata(): array;
}
