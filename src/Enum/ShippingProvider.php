<?php

namespace CourierDZ\Enum;

enum ShippingProvider: string
{
    case DHD = 'Dhd';
    case CONEXLOG = 'Conexlog';
    case YALIDINE = 'Yalidine';
    case YALITEC = 'Yalitec';
    case ZREXPRESS = 'ZRExpress';
    case MAYSTRO_DELIVERY = 'MaystroDelivery';

}
