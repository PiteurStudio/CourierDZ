<?php

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
     */
    protected array $credentials;

    /**
     * Validation rules for creating an order
     */
    public array $getCreateOrderValidationRules = [

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
            'api_docs' => null,
            'support' => 'https://maystro-delivery.com/ContactUS.html',
            'tracking_url' => 'https://maystro-delivery.com/trackingSD.html',
        ];
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
            $response = $client->request('GET', 'https://b.maystro-delivery.com/api/base/wilayas/?country=1', [
                'headers' => $headers,
                'Content-Type' => 'application/json',
            ]);

            // Check the status code
            return match ($response->getStatusCode()) {
                // If the request is successful, return true
                200 => true,
                // If the request returns a 401 or 403 status code, return false
                401, 403 => false,
                // If the request returns any other status code, throw an HttpException
                default => throw new HttpException(static::metadata()['name'].', Unexpected error occurred.'),
            };
        } catch (GuzzleException $guzzleException) {
            // Handle exceptions
            throw new HttpException($guzzleException->getMessage());
        }
    }

    public function getRates(?int $from_wilaya_id, ?int $to_wilaya_id): array
    {
        throw new NotImplementedException('Not implemented');
    }

    public function getCreateOrderValidationRules(): array
    {
        return $this->getCreateOrderValidationRules;
    }

    public function createOrder(array $orderData): array
    {
        throw new NotImplementedException('Not implemented');
    }

    public function getOrder(string $trackingId): array
    {
        throw new NotImplementedException('Not implemented');
    }

    public function orderLabel(string $orderId): array
    {
        throw new NotImplementedException('Not implemented');
    }

    public function validateCreate(array $data): bool
    {
        throw new NotImplementedException('Not implemented');
    }
}
