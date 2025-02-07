<?php

declare(strict_types=1);

namespace CourierDZ\ShippingProviders;

use CourierDZ\Contracts\ShippingProviderContract;
use CourierDZ\Exceptions\CredentialsException;
use CourierDZ\Exceptions\HttpException;
use CourierDZ\Exceptions\NotImplementedException;
use CourierDZ\Support\ShippingProviderValidation;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class MaystroDeliveryProvider implements ShippingProviderContract
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
        'wilaya' => 'required|integer|min:1|max:58',
        'commune' => 'required|integer|min:1',
        'destination_text' => 'nullable|string|max:255',
        'customer_phone' => 'required|numeric|digits_between:9,10',
        'customer_name' => 'required|string|max:255',
        'product_price' => 'required|integer',
        'delivery_type' => 'required|integer|in:0,1', // 0 = Livraison à domicile , 1 = Point de retrait
        'express' => 'boolean',
        'note_to_driver' => 'nullable|string|max:255',
        'products' => 'required|array',
        'source' => 'required|equals:4',
        'external_order_id' => 'nullable|string|max:255',
    ];

    /**
     * Constructor
     *
     * @param  array<non-empty-string, non-empty-string>  $credentials  The provider credentials
     *
     * @throws CredentialsException
     */
    public function __construct(array $credentials)
    {
        // Get the provider name from the metadata
        $provider_name = static::metadata()['name'];

        // Check if the credentials are valid
        if (! isset($credentials['token'])) {
            throw new CredentialsException($provider_name.' credentials must "token".');
        }

        // Set the credentials
        $this->credentials = $credentials;
    }

    /**
     * The metadata for the provider.
     */
    public static function metadata(): array
    {
        return [
            'name' => 'MaystroDelivery',
            'title' => 'Maystro Delivery',
            'logo' => 'https://maystro-delivery.com/img/Maystro-blue-extonly.svg',
            'description' => 'Maystro Delivery société de livraison en Algérie offre un service de livraison rapide et sécurisé .',
            'website' => 'https://maystro-delivery.com/',
            'api_docs' => 'https://maystro.gitbook.io/maystro-delivery-documentation',
            'support' => 'https://maystro-delivery.com/ContactUS.html',
            'tracking_url' => 'https://maystro-delivery.com/trackingSD.html',
        ];
    }

    public static function apiDomain(): string
    {
        return 'https://backend.maystro-delivery.com/api/';
    }

    /**
     * Test the credentials
     *
     * This method tests the credentials by making a GET request
     * to the Maystro Delivery API to retrieve the list of wilayas.
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
                'Authorization' => 'Token '.$this->credentials['token'],
            ];

            // Make the GET request
            $response = $client->request('GET', static::apiDomain().'base/wilayas/?country=1', [
                'headers' => $headers,
                'Content-Type' => 'application/json',
            ]);

            // Check the status code
            return match ($response->getStatusCode()) {
                // If the request is successful, return true
                200, 201 => true,
                // If the request returns a 401 status code, return false
                401 => false,
                // If the request returns any other status code, throw an HttpException
                default => throw new HttpException(static::metadata()['name'].', Unexpected error occurred.'),
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
        throw new NotImplementedException('Not implemented');
    }

    public function getCreateOrderValidationRules(): array
    {
        return $this->getCreateOrderValidationRules;
    }

    /**
     * @return array<non-empty-string, mixed>
     *
     * @throws HttpException
     */
    public function createProduct(string $store_id, string $logistical_description, ?string $product_id): array
    {
        $productData = [
            'store_id' => $store_id,
            'logistical_description' => $logistical_description,
        ];

        if ($product_id !== null && $product_id !== '' && $product_id !== '0') {
            $productData['product_id'] = $product_id;
        }

        try {
            // Initialize Guzzle client
            $client = new Client(['http_errors' => false]);

            // Define the headers
            $headers = [
                'Authorization' => 'Token '.$this->credentials['token'],
            ];

            // Make the GET request
            $response = $client->request('POST', static::apiDomain().'stores/product/', [
                'headers' => $headers,
                'body' => $productData,
            ]);

            if ($response->getStatusCode() == 200) {
                return json_decode($response->getBody()->getContents(), true);
            }

            throw new HttpException(static::metadata()['name'].', Unexpected error occurred.');
        } catch (GuzzleException $guzzleException) {
            // Handle exceptions
            throw new HttpException($guzzleException->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createOrder(array $orderData): array
    {
        // Validate the order data
        $this->validateCreate($orderData);

        try {
            // Initialize Guzzle client
            $client = new Client(['http_errors' => false]);

            // Define the headers
            $headers = [
                'Authorization' => 'Token '.$this->credentials['token'],
            ];

            // Make the GET request
            $response = $client->request('POST', static::apiDomain().'stores/orders/', [
                'headers' => $headers,
                'body' => $orderData,
            ]);

            if (in_array($response->getStatusCode(), [200, 201])) {
                return json_decode($response->getBody()->getContents(), true);
            }

            throw new HttpException(static::metadata()['name'].', Unexpected error occurred.');
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
        $orderId = $trackingId;

        try {
            // Initialize Guzzle client
            $client = new Client(['http_errors' => false]);

            // Define the headers
            $headers = [
                'Authorization' => 'Token '.$this->credentials['token'],
            ];

            // Make the GET request
            $response = $client->request('GET', static::apiDomain().'stores/orders/'.$orderId.'/', [
                'headers' => $headers,
            ]);

            if (in_array($response->getStatusCode(), [200, 201])) {
                return json_decode($response->getBody()->getContents(), true);
            }

            throw new HttpException(static::metadata()['name'].', Unexpected error occurred.');
        } catch (GuzzleException $guzzleException) {
            // Handle exceptions
            throw new HttpException($guzzleException->getMessage());
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
                'Authorization' => 'Token '.$this->credentials['token'],
            ];

            // Make the GET request
            $response = $client->request('POST', static::apiDomain().'delivery/starter/starter_bordureau/', [
                'headers' => $headers,
                'body' => [
                    'all_created' => true,
                    'orders_ids' => [$orderId],
                ],
            ]);

            if (in_array($response->getStatusCode(), [200, 201])) {

                $label = $response->getBody()->getContents();

                if ($label === '' || $label === '0') {
                    throw new HttpException('Failed to retrieve label for order with tracking ID '.$orderId.' - Empty response from Maystro Delivery API.');
                }

                $base64data = base64_encode($label);

                if ($base64data === '') {
                    throw new \RuntimeException('Unexpected empty base64 string');
                }

                return [
                    'type' => 'pdf',
                    'data' => $base64data,
                ];
            }

            throw new HttpException(static::metadata()['name'].', Unexpected error occurred.');
        } catch (GuzzleException $guzzleException) {
            // Handle exceptions
            throw new HttpException($guzzleException->getMessage());
        }
    }
}
