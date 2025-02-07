<?php

declare(strict_types=1);

namespace CourierDZ\ShippingProviders;

use CourierDZ\ProviderIntegrations\EcotrackProviderIntegration;

class RexLivraisonProvider extends EcotrackProviderIntegration
{
    /**
     * The url for the provider's API.
     */
    public static function apiDomain(): string
    {
        return 'https://rex.ecotrack.dz/';
    }

    /**
     * {@inheritdoc}
     */
    public static function metadata(): array
    {
        return [
            'name' => 'RexLivraison',
            'title' => 'Rex Livraison',
            'logo' => 'https://cdn1.ecotrack.dz/rex/images/login_logoCu3Rwdm.png',
            'description' => 'Rex Livraison est une entreprise algérienne opérant dans le secteur de livraison express',
            'website' => 'https://rex.ecotrack.dz/',
            'api_docs' => 'https://rex.ecotrack.dz/',
            'support' => 'https://rex.ecotrack.dz/',
            'tracking_url' => 'https://suivi.ecotrack.dz/suivi/',
        ];
    }
}
