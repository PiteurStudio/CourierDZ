<?php

declare(strict_types=1);

namespace CourierDZ\ShippingProviders;

use CourierDZ\ProviderIntegrations\EcotrackProviderIntegration;

class AreexProvider extends EcotrackProviderIntegration
{
    /**
     * The url for the provider's API.
     */
    public static function apiDomain(): string
    {
        return 'https://areex.ecotrack.dz/';
    }

    /**
     * {@inheritdoc}
     */
    public static function metadata(): array
    {
        return [
            'name' => 'Areex',
            'title' => 'Areex',
            'logo' => '#',
            'description' => 'Areex est une entreprise algérienne opérant dans le secteur de livraison express',
            'website' => 'https://areex.ecotrack.dz/',
            'api_docs' => 'https://areex.ecotrack.dz/',
            'support' => 'https://areex.ecotrack.dz/',
            'tracking_url' => 'https://suivi.ecotrack.dz/suivi/',
        ];
    }
}
