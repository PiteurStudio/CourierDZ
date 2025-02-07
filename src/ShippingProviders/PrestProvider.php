<?php

declare(strict_types=1);

namespace CourierDZ\ShippingProviders;

use CourierDZ\ProviderIntegrations\EcotrackProviderIntegration;

class PrestProvider extends EcotrackProviderIntegration
{
    /**
     * The url for the provider's API.
     */
    public static function apiDomain(): string
    {
        return 'https://prest.ecotrack.dz/';
    }

    /**
     * {@inheritdoc}
     */
    public static function metadata(): array
    {
        return [
            'name' => 'Prest',
            'title' => 'Prest',
            'logo' => '#',
            'description' => 'Prest est une entreprise algérienne opérant dans le secteur de livraison express',
            'website' => 'https://prest.ecotrack.dz/',
            'api_docs' => 'https://prest.ecotrack.dz/',
            'support' => 'https://prest.ecotrack.dz/',
            'tracking_url' => 'https://suivi.ecotrack.dz/suivi/',
        ];
    }
}
