<?php

namespace CourierDZ\ShippingProviders;

use CourierDZ\ProviderIntegrations\EcotrackProviderIntegration;

class DhdProvider extends EcotrackProviderIntegration
{
    /**
     * The url for the provider's API.
     */
    public static function apiDomain(): string
    {
        return 'https://dhd.ecotrack.dz/';
    }

    /**
     * @inheritdoc
     */
    public static function metadata(): array
    {
        return [
            'name' => 'Dhd',
            'title' => 'DHD',
            'logo' => 'https://dhd-dz.com/assets/img/logo.png',
            'description' => 'DHD livraison est une entreprise algérienne opérant dans le secteur de livraison express',
            'website' => 'https://dhd-dz.com/',
            'api_docs' => 'https://dhd-dz.com/',
            'support' => 'https://dhd-dz.com/#contact',
            'tracking_url' => 'https://suivi.ecotrack.dz/suivi/',
        ];
    }
}
