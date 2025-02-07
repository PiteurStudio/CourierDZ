<?php

declare(strict_types=1);

namespace CourierDZ\ShippingProviders;

use CourierDZ\ProviderIntegrations\EcotrackProviderIntegration;

class RocketDeliveryProvider extends EcotrackProviderIntegration
{
    /**
     * The url for the provider's API.
     */
    public static function apiDomain(): string
    {
        return 'https://rocket.ecotrack.dz/';
    }

    /**
     * {@inheritdoc}
     */
    public static function metadata(): array
    {
        return [
            'name' => 'RocketDelivery',
            'title' => 'Rocket Delivery',
            'logo' => 'https://cdn1.ecotrack.dz/rocket/images/login_logogAux6nt.png',
            'description' => 'Rocket Delivery est une entreprise algérienne opérant dans le secteur de livraison express',
            'website' => 'https://rocket.ecotrack.dz/',
            'api_docs' => 'https://rocket.ecotrack.dz/',
            'support' => 'https://rocket.ecotrack.dz/',
            'tracking_url' => 'https://suivi.ecotrack.dz/suivi/',
        ];
    }
}
