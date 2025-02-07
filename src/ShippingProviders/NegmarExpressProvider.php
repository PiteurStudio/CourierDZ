<?php

declare(strict_types=1);

namespace CourierDZ\ShippingProviders;

use CourierDZ\ProviderIntegrations\EcotrackProviderIntegration;

class NegmarExpressProvider extends EcotrackProviderIntegration
{
    /**
     * The url for the provider's API.
     */
    public static function apiDomain(): string
    {
        return 'https://negmar.ecotrack.dz/';
    }

    /**
     * {@inheritdoc}
     */
    public static function metadata(): array
    {
        return [
            'name' => 'NegmarExpress',
            'title' => 'Negmar Express',
            'logo' => '#',
            'description' => 'Negmar Express est une entreprise algérienne opérant dans le secteur de livraison express',
            'website' => 'https://negmar.ecotrack.dz/',
            'api_docs' => 'https://negmar.ecotrack.dz/',
            'support' => 'https://negmar.ecotrack.dz/',
            'tracking_url' => 'https://suivi.ecotrack.dz/suivi/',
        ];
    }
}
