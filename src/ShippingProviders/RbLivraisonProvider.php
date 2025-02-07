<?php

declare(strict_types=1);

namespace CourierDZ\ShippingProviders;

use CourierDZ\ProviderIntegrations\EcotrackProviderIntegration;

class RbLivraisonProvider extends EcotrackProviderIntegration
{
    /**
     * The url for the provider's API.
     */
    public static function apiDomain(): string
    {
        return 'https://rblivraison.ecotrack.dz/';
    }

    /**
     * {@inheritdoc}
     */
    public static function metadata(): array
    {
        return [
            'name' => 'RbLivraison',
            'title' => 'RB Livraison',
            'logo' => '#',
            'description' => 'RB Livraison est une entreprise algérienne opérant dans le secteur de livraison express',
            'website' => 'https://rblivraison.ecotrack.dz/',
            'api_docs' => 'https://rblivraison.ecotrack.dz/',
            'support' => 'https://rblivraison.ecotrack.dz/',
            'tracking_url' => 'https://suivi.ecotrack.dz/suivi/',
        ];
    }
}
