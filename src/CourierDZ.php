<?php

namespace CourierDZ;

use CourierDZ\Enum\ShippingProvider;
use CourierDZ\Exceptions\InvalidProviderException;
use CourierDZ\Services\ShippingService;

class CourierDZ
{
    /**
     * Create a new ShippingService instance for the given provider.
     *
     * @param  ShippingProvider  $provider  The provider to use
     * @param  array  $credentials  The credentials to use for the provider
     *
     * @throws InvalidProviderException
     */
    public static function provider(ShippingProvider $provider, array $credentials): ShippingService
    {
        return new ShippingService($provider->value, $credentials);
    }

    /**
     * Retrieve a list of all available shipping providers along with their metadata.
     *
     * This function calls the ShippingService to get the details of all providers
     * that are currently integrated into the system.
     *
     * @return array An array containing metadata of all available providers.
     */
    public static function providers(): array
    {
        // Fetch the list of providers from the ShippingService
        return ShippingService::getProviders();
    }
}
