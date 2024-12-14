### !! THIS PACKAGE IS STILL IN DEVELOPMENT BETA VERSION !!

<img src="https://banners.beyondco.de/CourierDZ.png?theme=light&packageManager=composer+require&packageName=piteurstudio%2Fcourierdz&pattern=architect&style=style_1&description=Simplify+the+integration+of+Algerian+shipping+providers+into+your+applications&md=1&showWatermark=1&fontSize=100px&images=https%3A%2F%2Fwww.php.net%2Fimages%2Flogos%2Fnew-php-logo.svg&widths=300&heights=auto" alt="CourierDZ Banner" />

[![Latest Version on Packagist](https://img.shields.io/packagist/v/piteurstudio/courierdz.svg?style=flat-square)](https://packagist.org/packages/piteurstudio/courierdz)
[![Tests](https://img.shields.io/github/actions/workflow/status/piteurstudio/courierdz/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/piteurstudio/courierdz/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/piteurstudio/courierdz.svg?style=flat-square)](https://packagist.org/packages/piteurstudio/courierdz)

# CourierDZ

CourierDZ is a PHP client designed to simplify the integration of Algerian shipping providers into your applications. 
Whether you're working on an e-commerce platform or logistics solution, CourierDZ provides a unified and easy-to-use interface for managing orders, tracking shipments, and more.

## Features

- **Multi-Provider Support**: Integrate with popular Algerian shipping services such as:
    - Procolis ( Zr Express , <s>E-Com Delivery, Abex Express , Flash Delivery, E-Send , WIN DELIVERY , COLILOG EXPRESS, GODYMA EXPRESS , LETS GO DELIVERY, LEOPARD EXPRESS , MR LIVREUR, EL AMANA DELIVERY, ALLO LIVRAISON, COLIRELI</s> )
    - Yalidine ( Yalitec <s>, [GuepEX](https://guepex.app/app/login.php) , [Zimou Express](https://zimou-express.app/app/login.php)</s> )
    - Ecotrack ( DHD , Conexlog (UPS)  )
    - <s>Mayesto Delivery</s>
    - <s>[Elogistia](https://documenter.getpostman.com/view/21600448/2s8YzP14j2) </s>
    - And more to come!
- **Unified API**: A consistent interface to interact with all supported providers.
- <s>**Order Management**: Create, update, and cancel shipping orders effortlessly.</s>
- **Extensible Design**: Easily add new providers or customize existing ones.


## Requirements

> - PHP 8.2 or higher
> - cURL extension


## Installation

You can install the package via composer:

```bash
composer require piteurstudio/courierdz:^0.1.13-beta
```

### Current Supported Methods
| **Provider/Feature**                        | **Yalidine** | **Procolis** | **Ecotrack** | **Mayesto Delivery** |
|---------------------------------------------|--------------|--------------|--------------|----------------------|
| testCredentials                             | ✅            | ✅            | ✅            | N/A                  |
| getRates                                    | ❌            | ✅            | ✅            | N/A                  |
| getRates(null , \$to_wilaya_id)             | ❌            | ✅            | ✅            | N/A                  |
| getRates(\$from_wilaya_id , \$to_wilaya_id) | ✅            | ❌            | ❌            | N/A                  |
| getCreateValidationRules                    | ✅            | ✅            | ✅            | N/A                  |
| createOrder                                 | ✅            | ✅            | ✅            | N/A                  |
| getOrder                                    | ✅            | ✅            | ⌛            | N/A                  |
| orderLabel                                  | ✅            | ❌            | ✅            | N/A                  |

@todo : Add more methods ( updateOrder, cancelOrder ... etc... )

*Suggestion : To support more method we need to create api with user and password for each provider*

## Usage

### List Available Providers

```php
use CourierDZ\CourierDZ;
    
$providersMetaData = CourierDZ::providers();
```

Return array of available providers with their metadata : 

![image](https://github.com/user-attachments/assets/a4453395-4304-4932-8190-5b49af40eab5)



### Setup

```php
/*
 * @param string $provider
 * ------------------------------------------------------------------------------
 * Provider name can be one of the following:
 * 
 * `ShippingProvider::ZREXPRESS` ( check ShippingProvider class for more information )
 * 
 * @param array $credentials
 * ------------------------------------------------------------------------------
 * For example, to setup a shipping service for Procolis ( a.k.a ZREXPRESS ), 
 * you need to provide an array of credentials like this:
 * 
 * ['token' => '****', 'key' => '****']
 * 
 * For Ecotrack, you only need to provide a token like this:
 * 
 * ['token' => '****']
 * 
 * For Yalidine you need to provide an id & token like this:
 * 
 * [ 'id' => '****', 'token' => '****']
 * 
 * Check ShippingProvider class for more information.
 * 
 */
 
$shippingProvider = CourierDZ::provider(ShippingProvider::ZREXPRESS, $credentials);
```

### Get Shipping Provider Metadata

```php
/*
 * $metadata : It will return an array containing the following :
 * [ name, title, logo, description, website, api_docs, support, tracking_url ]
 */

$metadata = $shippingProvider->metadata();
```

***Output :***

![image](https://github.com/user-attachments/assets/8f109f88-4932-40c2-b13d-1dae7d0df1e4)


### Validate Credentials

```php
/*
 * Check if the provided credentials are valid.
 * 
 * return bool
 */
 
echo $shippingProvider->testCredentials() ? 'Valid.' : 'Invalid.';
```

### Get Rates

```php
/*
 * return array of rates of shipping from one wilaya to another 
 * or all rates depending on the parameters / provider api.
 * 
 * @param string|null $from_wilaya_id
 * @param string|null $to_wilaya_id
 * return array
 */
 
// return all rates
 
$rates = $shippingProvider->getRates(); 

// return rates to specific wilaya

$rates = $shippingProvider->getRates(null , $to_wilaya_id);

// Yalidine require $from_wilaya_id , $to_wilaya_id
        
$rates = $shippingProvider->getRates($from_wilaya_id , $to_wilaya_id);
```

### Order Management

#### - Get Create Validation Rules

```php
/*
 * return an array of validation like Laravel rules
 * usefull for form validation and which fields are required to create a new order
 * Note : results may vary depending on the provider
 * 
 * @todo write costum apis to make them looks uniform
 */
 
$orderCreationRules = $shippingProvider->getCreateOrderValidationRules();
```

***Output :***

![image](https://github.com/user-attachments/assets/8049a9f1-c294-4714-ad56-8a28e4a2339a)


#### - Create Order

```php
/*
 * return array of provider response
 * Note : results may vary depending on the provider
 */
 
$result = $shippingProvider->createOrder([
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

#### - Get Order

```php  
/*
 * return array of order details 
 * Note : results may vary depending on the provider
 */
 
$result = $shippingService->getOrder('CourierDz-123');
```

#### - Order Label

```php
/*
 * return array of label data ( base64 encoded string or url )
 */
 
$label = $shippingProvider->orderLabel('CourierDz-123');
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



--------------------

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
- Before using any shipping provider, ensure it is authorized by **ARPCE**.  ( Check the approved list [here](https://www.arpce.dz/ar/service/post-sd).  )
- This package may include providers not listed by ARPCE; verify their compliance before use.

## ⭐ Support Us

If you find this package helpful, please consider giving it a ⭐ on [GitHub](https://github.com/PiteurStudio/CourierDZ) !
Your support encourages us to keep improving the project.
Thank you!

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
