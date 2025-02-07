<?php

declare(strict_types=1);

namespace CourierDZ\ShippingProviders;

use CourierDZ\ProviderIntegrations\EcotrackProviderIntegration;

class MsmGoProvider extends EcotrackProviderIntegration
{
    /**
     * The url for the provider's API.
     */
    public static function apiDomain(): string
    {
        return 'https://msmgo.ecotrack.dz';
    }

    /**
     * {@inheritdoc}
     */
    public static function metadata(): array
    {
        return [
            'name' => 'MsmGo',
            'title' => 'MSM Go',
            'logo' => '#',
            'description' => 'MSM Go est une entreprise algérienne opérant dans le secteur de livraison express',
            'website' => 'https://msmgo.ecotrack.dz',
            'api_docs' => 'https://msmgo.ecotrack.dz',
            'support' => 'https://msmgo.ecotrack.dz',
            'tracking_url' => 'https://suivi.ecotrack.dz/suivi/',
        ];
    }
}
