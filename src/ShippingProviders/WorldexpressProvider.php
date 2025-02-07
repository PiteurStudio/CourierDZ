<?php

declare(strict_types=1);

namespace CourierDZ\ShippingProviders;

use CourierDZ\ProviderIntegrations\EcotrackProviderIntegration;

class WorldexpressProvider extends EcotrackProviderIntegration
{
    /**
     * The url for the provider's API.
     */
    public static function apiDomain(): string
    {
        return 'https://worldexpress.ecotrack.dz/';
    }

    /**
     * {@inheritdoc}
     */
    public static function metadata(): array
    {
        return [
            'name' => 'Worldexpress',
            'title' => 'WorldExpress',
            'logo' => '#',
            'description' => 'WorldExpress est une entreprise algérienne opérant dans le secteur de livraison express',
            'website' => 'https://worldexpress.ecotrack.dz/',
            'api_docs' => 'https://worldexpress.ecotrack.dz/',
            'support' => 'https://worldexpress.ecotrack.dz/',
            'tracking_url' => 'https://suivi.ecotrack.dz/suivi/',
        ];
    }
}
