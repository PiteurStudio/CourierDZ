<?php

declare(strict_types=1);

namespace CourierDZ\ShippingProviders;

use CourierDZ\ProviderIntegrations\EcotrackProviderIntegration;

class DistazeroProvider extends EcotrackProviderIntegration
{
    /**
     * The url for the provider's API.
     */
    public static function apiDomain(): string
    {
        return 'https://distazero.ecotrack.dz/';
    }

    /**
     * {@inheritdoc}
     */
    public static function metadata(): array
    {
        return [
            'name' => 'Distazero',
            'title' => 'Distazero',
            'logo' => 'https://cdn1.ecotrack.dz/distazero/images/login_logooI8OebS.png',
            'description' => 'Distazero est une entreprise algérienne opérant dans le secteur de livraison express',
            'website' => 'https://distazero.ecotrack.dz/',
            'api_docs' => 'https://distazero.ecotrack.dz/',
            'support' => 'https://distazero.ecotrack.dz/',
            'tracking_url' => 'https://suivi.ecotrack.dz/suivi/',
        ];
    }
}
