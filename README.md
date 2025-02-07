<img src="https://banners.beyondco.de/CourierDZ.png?theme=light&packageManager=composer+require&packageName=piteurstudio%2Fcourierdz&pattern=architect&style=style_1&description=Simplify+the+integration+of+Algerian+shipping+providers+into+your+applications&md=1&showWatermark=1&fontSize=100px&images=https%3A%2F%2Fwww.php.net%2Fimages%2Flogos%2Fnew-php-logo.svg&widths=300&heights=auto" alt="CourierDZ Banner" />

<p align="center">
    <a href="https://php.net"><img src="https://poser.pugx.org/piteurstudio/CourierDZ/require/php" alt="PHP 8.2"></a>
    <a href="https://packagist.org/packages/piteurstudio/courierdz"><img src="https://img.shields.io/packagist/v/piteurstudio/courierdz.svg" alt="Latest Version on Packagist"></a>
    <a href="https://github.com/piteurstudio/courierdz/actions/workflows/run-tests.yml"><img src="https://img.shields.io/github/actions/workflow/status/piteurstudio/courierdz/run-tests.yml?branch=main&amp;label=tests" alt="Tests"></a>
    <a href="https://coveralls.io/github/PiteurStudio/CourierDZ?branch=main"><img src="https://coveralls.io/repos/github/PiteurStudio/CourierDZ/badge.svg?branch=main" alt="Coverage Status"></a>
    <a href="https://packagist.org/packages/piteurstudio/courierdz"><img src="https://img.shields.io/packagist/dt/piteurstudio/courierdz.svg" alt="Total Downloads"></a>
    <a href="https://github.com/PiteurStudio/CourierDZ/tree/main/src/ShippingProviders"><img src="https://img.shields.io/badge/Supported_providers-26-blue" alt="Supported providers"></a>
</p>

# CourierDZ

CourierDZ is a PHP client designed to simplify the integration of Algerian shipping providers into your applications. 
Whether you're working on an e-commerce platform or logistics solution, and provides a unified and easy-to-use interface for managing orders, tracking shipments.

## Features

- **Multi-Provider Support**: Integrate with popular Algerian shipping services such as:
    - `Yalidine` , `Yalitec`
    - `Mayesto Delivery`
    - `Procolis` ( `Zr Express` )
    - `Ecotrack` ( `DHD` , `Conexlog/UPS` and many more...  )
- **Unified API**: A consistent interface to interact with all supported providers.
- **Order Management**: Create, update, and cancel shipping orders effortlessly.
- **Extensible Design**: Easily add new providers or customize existing ones.


## Requirements

> - PHP 8.2 or higher
> - cURL extension


## Installation

You can install the package via composer:

```bash
composer require piteurstudio/courierdz
```

### Current Supported Methods
| **Provider/Feature**                        | **Yalidine** | **Procolis** | **Ecotrack** | **Maystro Delivery** |
|---------------------------------------------|--------------|--------------|--------------|----------------------|
| testCredentials                             | ✅            | ✅            | ✅            | ✅                    |
| getRates                                    | ❌            | ✅            | ✅            | ❔                    |
| getRates(null , \$to_wilaya_id)             | ❌            | ✅            | ✅            | ❌                    |
| getRates(\$from_wilaya_id , \$to_wilaya_id) | ✅            | ❌            | ❌            | ❌                    |
| getCreateValidationRules                    | ✅            | ✅            | ✅            | ✅                    |
| createOrder                                 | ✅            | ✅            | ✅            | ✅                    |
| getOrder                                    | ✅            | ✅            | ❔            | ✅                    |
| updateOrder                                 | ❔            | ❔            | ❔            | ❔                    |
| cancelOrder                                 | ❔            | ❔            | ❔            | ❔                    |
| orderLabel                                  | ✅            | ❌            | ✅            | ✅                    |
| createProduct                               | ❌            | ❌            | ❌            | ✅                    |

 - ✅ Implemented
 - ❌ Unsupported by the provider
 - ⌛ In Progress
 - ❔ Not implemented yet ( unknown if supported or not )

*Note : Unsupported method can be implemented in the future by using user and password instead of API.*

## Usage

### Initialize the shipping provider and set your credentials.

```php

// Ecotrack providers
$credentials = ['token' => '****'];

// Procolis providers ( ZREXPRESS )
$credentials = ['id' => '****', 'token' => '****'];

// Yalidine providers
$credentials = ['token' => '****', 'key' => '****'];

// Mayestro Delivery providers
$credentials = ['token' => '****'];


$shipping_provider = CourierDZ::provider(ShippingProvider::ZREXPRESS, $credentials);

// or 

$shipping_provider = new XyzProvider($credentials); // where Xyz is the provider name
```

### Create a parcel ( order )

```php
/*
 * return array of provider response
 * Note : results may vary depending on the provider
 */
 
$result = $shipping_provider->createOrder([
        'Tracking' => 'CourierDz-123',
        'TypeLivraison' => 1,
        'TypeColis' => 0,
        'Confrimee' => 0,
        'Client' => 'Mohamed',
        'MobileA' => '0990909090',
        'MobileB' => '0880808080',
        'Adresse' => 'Rue 39',
        'IDWilaya' => "09",
        'Commune' => 'Maraval',
        'Total' => "2000",
        'Note' => 'test test',
        'TProduit' => 'Article1',
        "id_Externe" => 'CourierDz-123',
        "Source" => 'CourierDz',
    ]))
```
To know the required fields for the order creation depend on the provider requirements, use 
```php
$rules = $shipping_provider->getCreateOrderValidationRules();
```


### Retrieving a label ( order )

```php
/*
 * return array of label data ( base64 encoded string or url )
 */
 
$label = $shipping_provider->orderLabel('CourierDz-123');
```

***Output :***
```
[
    [type] => 'pdf'
    [data] => 'base64 encoded string'
]
         -- OR --
[     
    [type] => 'url'
    [url] => 'https://example.com/label.pdf'
]
```
---------------

#### **More examples and methods can be found in the [DOCUMENTATION.md](DOCUMENTATION.md) file.**

## Contribution

We welcome all contributions! Please follow these guidelines:

1. Document any changes in behavior — ensure `README.md` updated accordingly.
2. Write tests to cover any new functionality.
3. Please ensure that your pull request passes all tests.

### Testing

```bash
composer test
```

## Issues & Suggesting Features

If you encounter any issues or have ideas for new features, please [open an issue](https://github.com/PiteurStudio/CourierDZ/issues/new/choose).

We appreciate your feedback and contributions to help improve this package.

## Provider not yet included?

[Request Provider](https://github.com/PiteurStudio/CourierDZ/discussions/new?category=ideas) and provide the following information:

- Provider Name
- Provider Website
- API Documentation
- Any other relevant information

### Future Planned Features
- [ ] Add more methods
- [ ] Add more tests
- [ ] Add more examples
- [ ] Add more documentation
- [ ] Add more shipping providers ( eg : [Elogistia](https://documenter.getpostman.com/view/21600448/2s8YzP14j2), E-Com Delivery, Abex Express , Flash Delivery, E-Send , WIN DELIVERY , COLILOG EXPRESS, GODYMA EXPRESS , LETS GO DELIVERY, LEOPARD EXPRESS , MR LIVREUR, EL AMANA DELIVERY, ALLO LIVRAISON, COLIRELI , Yalitec , [GuepEX](https://guepex.app/app/login.php) , [Zimou Express](https://zimou-express.app/app/login.php))

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.


## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Nassim](https://github.com/n4ss1m) / [Piteur Studio](https://github.com/PiteurStudio)
- [All Contributors](../../contributors)



## Disclaimer

- This package is not officially affiliated with or endorsed by any shipping providers. 
- Names, logos, and trademarks are the property of their respective owners. 
- Before using any shipping provider, ensure it is authorized by **ARPCE**.  ( Check the approved list [here](https://www.arpce.dz/ar/service/post-sd#operators).  )
- This package may include providers not listed by ARPCE; verify their compliance before use.

## ⭐ Support Us

If you find this package helpful, please consider giving it a ⭐ on [GitHub](https://github.com/PiteurStudio/CourierDZ) !
Your support encourages us to keep improving the project.
Thank you!

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
