<?php

namespace CourierDZ\ShippingProviders;

use CourierDZ\ProviderIntegrations\EcotrackProviderIntegration;

class ConexlogProvider extends EcotrackProviderIntegration
{
    /**
     * The url for the provider's API.
     */
    public static function apiDomain(): string
    {
        return 'https://app.conexlog-dz.com/';
    }

    /**
     * @inheritdoc
     */
    public static function metadata(): array
    {
        return [
            'name' => 'Conexlog',
            'title' => 'Conexlog',
            'logo' => 'https://conexlog-dz.com/assets/img/logo.png',
            'description' => 'CONEXLOG est le prestataire exclusif des services agréés en Algérie pour le groupe UPS',
            'website' => 'https://conexlog-dz.com/',
            'api_docs' => 'https://conexlog-dz.com/',
            'support' => 'https://conexlog-dz.com/contact.php',
            'tracking_url' => 'https://conexlog-dz.com/suivi.php',
        ];
    }
}
