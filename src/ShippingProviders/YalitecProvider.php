<?php

declare(strict_types=1);

namespace CourierDZ\ShippingProviders;

use CourierDZ\ProviderIntegrations\YalidineProviderIntegration;

class YalitecProvider extends YalidineProviderIntegration
{
    /**
     * The url for the provider's API.
     */
    public static function apiDomain(): string
    {
        return 'https://api.yalitec.me';
    }

    /**
     * {@inheritdoc}
     */
    public static function metadata(): array
    {
        return [
            'name' => 'Yalitec',
            'title' => 'Yalitec',
            'logo' => 'https://www.yalitec.com/_next/image?url=%2Fimages%2Flogo.png&w=384&q=75',
            'description' => 'Yalitec société de livraison en Algérie offre un service de livraison rapide et sécurisé .',
            'website' => 'https://www.yalitec.com/fr',
            'api_docs' => 'https://yalitec.me/app/dev/docs/api/index.php',
            'support' => 'https://www.yalitec.com/fr#contact',
            'tracking_url' => null,
        ];
    }
}
