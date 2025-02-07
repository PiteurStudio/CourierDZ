<?php

declare(strict_types=1);

namespace CourierDZ\ShippingProviders;

use CourierDZ\ProviderIntegrations\EcotrackProviderIntegration;

class CoyoteExpressProvider extends EcotrackProviderIntegration
{
    /**
     * The url for the provider's API.
     */
    public static function apiDomain(): string
    {
        return 'https://coyoteexpressdz.ecotrack.dz/';
    }

    /**
     * {@inheritdoc}
     */
    public static function metadata(): array
    {
        return [
            'name' => 'CoyoteExpress',
            'title' => 'Coyote express',
            'logo' => '#',
            'description' => 'Coyote express est une entreprise algérienne opérant dans le secteur de livraison express',
            'website' => 'https://coyoteexpressdz.ecotrack.dz/',
            'api_docs' => 'https://coyoteexpressdz.ecotrack.dz/',
            'support' => 'https://coyoteexpressdz.ecotrack.dz/',
            'tracking_url' => 'https://suivi.ecotrack.dz/suivi/',
        ];
    }
}
