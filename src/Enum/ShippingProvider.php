<?php

declare(strict_types=1);

namespace CourierDZ\Enum;

enum ShippingProvider: string
{
    // Ecotrack
    case DHD = 'Dhd';
    case CONEXLOG = 'Conexlog';
    case MSMGO = 'MsmGo';
    case REXLIVRAISON = 'RexLivraison';
    case RBLIVRAISON = 'RbLivraison';
    case SPEEDDELIVERY = 'SpeedDelivery';
    case AREEX = 'Areex';
    case PREST = 'Prest';
    case ROCKETDELIVERY = 'RocketDelivery';
    case WORLDEXPRESS = 'Worldexpress';
    case BACONSULT = 'BaConsult';
    case PACKERS = 'Packers';
    case E48HRLIVRAISON = 'E48hrLivraison';
    case MONOHUB = 'MonoHub';
    case ANDERSONDELIVERY = 'AndersonDelivery';
    case GOLIVRI = 'Golivri';
    case COYOTEEXPRESS = 'CoyoteExpress';
    case SALVADELIVERY = 'SalvaDelivery';
    case DISTAZERO = 'Distazero';
    case FRETDIRECT = 'Fretdirect';
    case TSLEXPRESS = 'TslExpress';
    case NEGMAREXPRESS = 'NegmarExpress';
    // Yalidine
    case YALIDINE = 'Yalidine';
    case YALITEC = 'Yalitec';
    // Procolis
    case ZREXPRESS = 'ZRExpress';
    // Other
    case MAYSTRO_DELIVERY = 'MaystroDelivery';

}
