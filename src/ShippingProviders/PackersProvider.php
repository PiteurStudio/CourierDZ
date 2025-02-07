<?php

declare(strict_types=1);

namespace CourierDZ\ShippingProviders;

use CourierDZ\ProviderIntegrations\EcotrackProviderIntegration;

class PackersProvider extends EcotrackProviderIntegration
{
    /**
     * The url for the provider's API.
     */
    public static function apiDomain(): string
    {
        return 'https://packers.ecotrack.dz/';
    }

    /**
     * {@inheritdoc}
     */
    public static function metadata(): array
    {
        return [
            'name' => 'Packers',
            'title' => 'Packers',
            'logo' => '#',
            'description' => 'Packers est une entreprise algérienne opérant dans le secteur de livraison express',
            'website' => 'https://packers.ecotrack.dz/',
            'api_docs' => 'https://packers.ecotrack.dz/',
            'support' => 'https://packers.ecotrack.dz/',
            'tracking_url' => 'https://suivi.ecotrack.dz/suivi/',
        ];
    }
}
