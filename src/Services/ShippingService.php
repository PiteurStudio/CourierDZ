<?php

namespace CourierDZ\Services;

use CourierDZ\Contracts\ShippingProviderContract;
use CourierDZ\Exceptions\InvalidProviderException;

class ShippingService
{
    private readonly ShippingProviderContract $shippingProviderContract;

    /**
     * Create a new ShippingService instance for the given provider.
     *
     * @param  non-empty-string  $providerName  The name of the shipping provider (e.g. "ZR Express", "Procolis", etc.)
     * @param  array<non-empty-string, non-empty-string>  $credentials  An array of credentials for the provider (e.g. API key, username, password, etc.)
     *
     * @throws InvalidProviderException If the provider is not valid
     */
    public function __construct(string $providerName, array $credentials)
    {
        $this->shippingProviderContract = $this->loadProvider($providerName, $credentials);
    }

    /**
     * Load the provider class.
     *
     * This method takes a provider name and credentials and checks if the provider exists.
     * If the provider does not exist, it throws an InvalidProviderException.
     * If the provider exists but does not implement ShippingProviderContract
     * or extend XyzProviderIntegration, it throws an InvalidProviderException.
     *
     * @param  non-empty-string  $providerName  The name of the shipping provider (e.g. "ZR Express", "Procolis", etc.)
     * @param  array<non-empty-string, non-empty-string>  $credentials  An array of credentials for the provider (e.g. API key, username, password, etc.)
     * @return ShippingProviderContract The provider class instance
     *
     * @throws InvalidProviderException If the provider is not valid
     */
    private function loadProvider(string $providerName, array $credentials): ShippingProviderContract
    {
        $namespace = sprintf('CourierDZ\ShippingProviders\%sProvider', $providerName);

        if (! class_exists($namespace)) {
            // If the provider class does not exist, throw an exception
            // with a list of available providers
            $availableProvidersNames = '';
            foreach (self::getProviders() as $provider) {
                $availableProvidersNames .= $provider['name'].', ';
            }

            throw new InvalidProviderException(sprintf('Incorrect `%s` Shipping provider name, Available providers are: ', $providerName).rtrim($availableProvidersNames, ', '));
        }

        // Create an instance of the provider class
        // and check if it implements ShippingProviderContract
        // or extends XyzProviderIntegration
        $providerClass = new $namespace($credentials);

        if (! $providerClass instanceof ShippingProviderContract) {
            throw new InvalidProviderException($providerName.'Provider must implement ShippingProviderContract or extend XyzProviderIntegration.');
        }

        return $providerClass;
    }

    /**
     * Check if the credentials are valid for the current shipping provider.
     *
     * This method delegates the credential validation to the provider's
     * implementation of the testCredentials method.
     *
     * @return bool True if the credentials are valid, false otherwise.
     */
    public function testCredentials(): bool
    {
        // Call the provider's testCredentials method to verify the credentials.
        return $this->shippingProviderContract->testCredentials();
    }

    /**
     * get the creation validation rules.
     *
     * @return array<non-empty-string, non-empty-string|array<int, non-empty-string>> The validation rules for creating an order
     */
    public function getCreateOrderValidationRules(): array
    {
        return $this->shippingProviderContract->getCreateOrderValidationRules();
    }

    /**
     * Validate the order creation data.
     *
     * This method delegates the validation of the order data
     * to the provider's implementation of the validateCreate method.
     *
     * @param  array<non-empty-string, non-empty-string>  $orderData  The order data to validate
     * @return bool True if the order data is valid, false otherwise
     */
    public function validateCreate(array $orderData): bool
    {
        // Call the provider's validateCreate method to validate the order data
        return $this->shippingProviderContract->validateCreate($orderData);
    }

    /**
     * Get shipping rates for every wilaya or for a specific wilaya.
     *
     * @param  int<1, 58>|null  $from_wilaya_id  The ID of the wilaya to get rates from
     * @param  int<1, 58>|null  $to_wilaya_id  The ID of the wilaya to get rates to
     * @return array<int , mixed> An array of shipping rates, each containing the price, and wilaya IDs
     */
    public function getRates(?int $from_wilaya_id = null, ?int $to_wilaya_id = null): array
    {
        return $this->shippingProviderContract->getRates($from_wilaya_id, $to_wilaya_id);
    }

    /**
     * Create a new order.
     *
     * This method delegates the order creation to the provider's
     * implementation of the createOrder method.
     *
     * @param  array<non-empty-string, mixed>  $orderData  The order data to create an order with
     * @return array<non-empty-string, mixed> An array containing the order ID and the tracking ID
     */
    public function createOrder(array $orderData): array
    {
        return $this->shippingProviderContract->createOrder($orderData);
    }

    /**
     * Read an order by its tracking ID.
     *
     * This method delegates the order retrieval to the provider's
     * implementation of the getOrder method.
     *
     * @param  non-empty-string  $trackingId  The tracking ID of the order to retrieve
     * @return array<non-empty-string, mixed> An array containing the order details
     */
    public function getOrder(string $trackingId): array
    {
        return $this->shippingProviderContract->getOrder($trackingId);
    }

    /**
     * Retrieve the label for a specific order.
     *
     * This method delegates the task to the provider's implementation of the
     * orderLabel method, which returns the label details for the given order ID.
     *
     * @param  non-empty-string  $orderId  The ID of the order for which to retrieve the label.
     * @return array{type: 'pdf'|'url', data: non-empty-string} An array containing the label details of the order.
     */
    public function orderLabel(string $orderId): array
    {
        // Delegate to the provider's orderLabel method to get the order label
        return $this->shippingProviderContract->orderLabel($orderId);
    }

    //    /**
    //     * Cancel an order.
    //     *
    //     * This method delegates the cancellation of an order to the provider's
    //     * implementation of the cancelOrder method.
    //     *
    //     * @param  string  $orderId  The ID of the order to be canceled.
    //     * @return bool True if the order was successfully canceled, false otherwise.
    //     */
    //    public function cancelOrder(string $orderId): bool
    //    {
    //        // Delegate the cancellation to the provider's cancelOrder method
    //        return $this->provider->cancelOrder($orderId);
    //    }

    /**
     * Get metadata for the provider.
     *
     * This method is called by the ShippingService to retrieve metadata about
     * the current provider.
     *
     * @return array<non-empty-string, non-empty-string|null> An array containing metadata of the provider
     */
    public function metaData(): array
    {
        return $this->shippingProviderContract->metaData();
    }

    /**
     * Get a list of all available providers with metadata.
     *
     * This method reads the contents of the ShippingProviders directory and
     * loads every provider class that implements the ShippingProviderContract.
     * It then calls the metaData method on each provider to get the
     * provider's metadata and returns an array of all the metadata.
     *
     * @return array<int , array<non-empty-string, non-empty-string|null>> An array containing the metadata for all available providers.
     */
    public static function getProviders(): array
    {
        $providers = [];

        $providers_filenames = glob(__DIR__.'/../ShippingProviders/*Provider.php');

        if ($providers_filenames === false) {
            $providers_filenames = [];
        }

        foreach ($providers_filenames as $provider_filename) {
            $className = basename($provider_filename, '.php');
            $namespace = 'CourierDZ\ShippingProviders\\'.$className;

            // Check if the class exists and implements the ShippingProviderContract
            if (class_exists($namespace) && is_subclass_of($namespace, ShippingProviderContract::class)) {
                // Get the metadata for the provider
                $providers[] = $namespace::metadata();
            }
        }

        return $providers;
    }
}
