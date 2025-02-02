<?php

declare(strict_types=1);

namespace CourierDZ\ShippingProviders;

use CourierDZ\ProviderIntegrations\YalidineProviderIntegration;

class YalidineProvider extends YalidineProviderIntegration
{
    /**
     * The url for the provider's API.
     */
    public static function apiDomain(): string
    {
        return 'https://api.yalidine.app';
    }

    /**
     * {@inheritdoc}
     */
    public static function metadata(): array
    {
        return [
            'name' => 'Yalidine',
            'title' => 'Yalidine',
            'logo' => 'https://yalidine.com/assets/img/yalidine-logo.png',
            'description' => 'Yalidine société de livraison en Algérie offre un service de livraison rapide et sécurisé .',
            'website' => 'https://yalidine.com/',
            'api_docs' => 'https://yalidine.app/app/dev/docs/api/index.php',
            'support' => 'https://yalidine.com/#contact',
            'tracking_url' => 'https://yalidine.com/suivre-un-colis/',
        ];
    }
}
