<?php

declare(strict_types=1);

namespace CourierDZ\ShippingProviders;

use CourierDZ\ProviderIntegrations\EcotrackProviderIntegration;

class AndersonDeliveryProvider extends EcotrackProviderIntegration
{
    /**
     * The url for the provider's API.
     */
    public static function apiDomain(): string
    {
        return 'https://anderson.ecotrack.dz/';
    }

    /**
     * {@inheritdoc}
     */
    public static function metadata(): array
    {
        return [
            'name' => 'AndersonDelivery',
            'title' => 'Anderson Delivery',
            'logo' => 'https://cdn1.ecotrack.dz/anderson/images/login_logoctVbSeP.png',
            'description' => 'Anderson Delivery est une entreprise algérienne opérant dans le secteur de livraison express',
            'website' => 'https://anderson.ecotrack.dz/',
            'api_docs' => 'https://anderson.ecotrack.dz/',
            'support' => 'https://anderson.ecotrack.dz/',
            'tracking_url' => 'https://suivi.ecotrack.dz/suivi/',
        ];
    }
}
