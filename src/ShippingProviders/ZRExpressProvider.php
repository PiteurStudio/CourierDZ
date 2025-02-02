<?php

declare(strict_types=1);

namespace CourierDZ\ShippingProviders;

use CourierDZ\ProviderIntegrations\ProcolisProviderIntegration;

class ZRExpressProvider extends ProcolisProviderIntegration
{
    /**
     * {@inheritdoc}
     */
    public static function metadata(): array
    {
        return [
            'name' => 'ZRExpress',
            'title' => 'ZR Express',
            'logo' => 'https://zrexpress.com/ZREXPRESS_WEB/ext/Logo.jpg',
            'description' => 'ZRexpress société de livraison en Algérie offre un service de livraison rapide et sécurisé .',
            'website' => 'https://zrexpress.com',
            'api_docs' => 'https://zrexpress.com/ZREXPRESS_WEB/FR/Developpement.awp',
            'support' => 'https://www.facebook.com/ZRexpresslivraison/',
            'tracking_url' => null,
        ];
    }
}
