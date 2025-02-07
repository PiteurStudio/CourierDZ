<?php

declare(strict_types=1);

namespace CourierDZ\ShippingProviders;

use CourierDZ\ProviderIntegrations\EcotrackProviderIntegration;

class E48hrLivraisonProvider extends EcotrackProviderIntegration
{
    /**
     * The url for the provider's API.
     */
    public static function apiDomain(): string
    {
        return 'https://48hr.ecotrack.dz/';
    }

    /**
     * {@inheritdoc}
     */
    public static function metadata(): array
    {
        return [
            'name' => 'E48hrLivraison',
            'title' => '48Hr Livraison',
            'logo' => '#',
            'description' => '48Hr Livraison est une entreprise algérienne opérant dans le secteur de livraison express',
            'website' => 'https://48hr.ecotrack.dz/',
            'api_docs' => 'https://48hr.ecotrack.dz/',
            'support' => 'https://48hr.ecotrack.dz/',
            'tracking_url' => 'https://suivi.ecotrack.dz/suivi/',
        ];
    }
}
