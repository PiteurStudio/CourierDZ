<?php

declare(strict_types=1);

namespace CourierDZ\ShippingProviders;

use CourierDZ\ProviderIntegrations\EcotrackProviderIntegration;

class FretdirectProvider extends EcotrackProviderIntegration
{
    /**
     * The url for the provider's API.
     */
    public static function apiDomain(): string
    {
        return 'https://fret.ecotrack.dz/';
    }

    /**
     * {@inheritdoc}
     */
    public static function metadata(): array
    {
        return [
            'name' => 'Fretdirect',
            'title' => 'FRET.Direct',
            'logo' => '#',
            'description' => 'FRET.Direct est une entreprise algérienne opérant dans le secteur de livraison express',
            'website' => 'https://fret.ecotrack.dz/',
            'api_docs' => 'https://fret.ecotrack.dz/',
            'support' => 'https://fret.ecotrack.dz/',
            'tracking_url' => 'https://suivi.ecotrack.dz/suivi/',
        ];
    }
}
