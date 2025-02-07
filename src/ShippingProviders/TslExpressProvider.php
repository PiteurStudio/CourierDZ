<?php

declare(strict_types=1);

namespace CourierDZ\ShippingProviders;

use CourierDZ\ProviderIntegrations\EcotrackProviderIntegration;

class TslExpressProvider extends EcotrackProviderIntegration
{
    /**
     * The url for the provider's API.
     */
    public static function apiDomain(): string
    {
        return 'https://tsl.ecotrack.dz/';
    }

    /**
     * {@inheritdoc}
     */
    public static function metadata(): array
    {
        return [
            'name' => 'TslExpress',
            'title' => 'TSL Express',
            'logo' => 'https://cdn1.ecotrack.dz/tsl/images/login_logoxDIzsCJ.png',
            'description' => 'TSL Express est une entreprise algérienne opérant dans le secteur de livraison express',
            'website' => 'https://tsl.ecotrack.dz/',
            'api_docs' => 'https://tsl.ecotrack.dz/',
            'support' => 'https://tsl.ecotrack.dz/',
            'tracking_url' => 'https://suivi.ecotrack.dz/suivi/',
        ];
    }
}
