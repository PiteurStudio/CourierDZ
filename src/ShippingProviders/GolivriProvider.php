<?php

declare(strict_types=1);

namespace CourierDZ\ShippingProviders;

use CourierDZ\ProviderIntegrations\EcotrackProviderIntegration;

class GolivriProvider extends EcotrackProviderIntegration
{
    /**
     * The url for the provider's API.
     */
    public static function apiDomain(): string
    {
        return 'https://golivri.ecotrack.dz/';
    }

    /**
     * {@inheritdoc}
     */
    public static function metadata(): array
    {
        return [
            'name' => 'Golivri',
            'title' => 'GOLIVRI',
            'logo' => 'https://cdn1.ecotrack.dz/golivri/images/login_logoP2208XU.png',
            'description' => 'GOLIVRI est une entreprise algérienne opérant dans le secteur de livraison express',
            'website' => 'https://golivri.ecotrack.dz/',
            'api_docs' => 'https://golivri.ecotrack.dz/',
            'support' => 'https://golivri.ecotrack.dz/',
            'tracking_url' => 'https://suivi.ecotrack.dz/suivi/',
        ];
    }
}
