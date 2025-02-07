<?php

declare(strict_types=1);

namespace CourierDZ\ShippingProviders;

use CourierDZ\ProviderIntegrations\EcotrackProviderIntegration;

class BaConsultProvider extends EcotrackProviderIntegration
{
    /**
     * The url for the provider's API.
     */
    public static function apiDomain(): string
    {
        return 'https://bacexpress.ecotrack.dz/';
    }

    /**
     * {@inheritdoc}
     */
    public static function metadata(): array
    {
        return [
            'name' => 'BaConsult',
            'title' => 'BA Consult',
            'logo' => 'https://cdn1.ecotrack.dz/bacexpress/images/login_logoeORMVno.png',
            'description' => 'BA Consult est une entreprise algérienne opérant dans le secteur de livraison express',
            'website' => 'https://bacexpress.ecotrack.dz/',
            'api_docs' => 'https://bacexpress.ecotrack.dz/',
            'support' => 'https://bacexpress.ecotrack.dz/',
            'tracking_url' => 'https://suivi.ecotrack.dz/suivi/',
        ];
    }
}
