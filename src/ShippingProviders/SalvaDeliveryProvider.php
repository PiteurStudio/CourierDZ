<?php

declare(strict_types=1);

namespace CourierDZ\ShippingProviders;

use CourierDZ\ProviderIntegrations\EcotrackProviderIntegration;

class SalvaDeliveryProvider extends EcotrackProviderIntegration
{
    /**
     * The url for the provider's API.
     */
    public static function apiDomain(): string
    {
        return 'https://salvadelivery.ecotrack.dz/';
    }

    /**
     * {@inheritdoc}
     */
    public static function metadata(): array
    {
        return [
            'name' => 'SalvaDelivery',
            'title' => 'Salva Delivery',
            'logo' => 'https://cdn1.ecotrack.dz/salvadelivery/images/login_logo6GOyzNz.png',
            'description' => 'Salva Delivery est une entreprise algérienne opérant dans le secteur de livraison express',
            'website' => 'https://salvadelivery.ecotrack.dz/',
            'api_docs' => 'https://salvadelivery.ecotrack.dz/',
            'support' => 'https://salvadelivery.ecotrack.dz/',
            'tracking_url' => 'https://suivi.ecotrack.dz/suivi/',
        ];
    }
}
