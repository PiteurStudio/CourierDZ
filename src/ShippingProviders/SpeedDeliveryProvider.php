<?php

declare(strict_types=1);

namespace CourierDZ\ShippingProviders;

use CourierDZ\ProviderIntegrations\EcotrackProviderIntegration;

class SpeedDeliveryProvider extends EcotrackProviderIntegration
{
    /**
     * The url for the provider's API.
     */
    public static function apiDomain(): string
    {
        return 'https://speeddelivery.ecotrack.dz/';
    }

    /**
     * {@inheritdoc}
     */
    public static function metadata(): array
    {
        return [
            'name' => 'SpeedDelivery',
            'title' => 'Speed Delivery',
            'logo' => '#',
            'description' => 'Speed Delivery est une entreprise algérienne opérant dans le secteur de livraison express',
            'website' => 'https://speeddelivery.ecotrack.dz/',
            'api_docs' => 'https://speeddelivery.ecotrack.dz/',
            'support' => 'https://speeddelivery.ecotrack.dz/',
            'tracking_url' => 'https://suivi.ecotrack.dz/suivi/',
        ];
    }
}
