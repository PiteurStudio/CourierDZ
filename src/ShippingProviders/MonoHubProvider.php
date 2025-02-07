<?php

declare(strict_types=1);

namespace CourierDZ\ShippingProviders;

use CourierDZ\ProviderIntegrations\EcotrackProviderIntegration;

class MonoHubProvider extends EcotrackProviderIntegration
{
    /**
     * The url for the provider's API.
     */
    public static function apiDomain(): string
    {
        return 'https://mono.ecotrack.dz/';
    }

    /**
     * {@inheritdoc}
     */
    public static function metadata(): array
    {
        return [
            'name' => 'MonoHub',
            'title' => 'Mono Hub',
            'logo' => '#',
            'description' => 'Mono Hub est une entreprise algérienne opérant dans le secteur de livraison express',
            'website' => 'https://mono.ecotrack.dz/',
            'api_docs' => 'https://mono.ecotrack.dz/',
            'support' => 'https://mono.ecotrack.dz/',
            'tracking_url' => 'https://suivi.ecotrack.dz/suivi/',
        ];
    }
}
