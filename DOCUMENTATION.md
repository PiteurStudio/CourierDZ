## Documentation
- [List Available Providers](#list-available-providers)
- [Test credentials](#validate-credentials)
- [Initialize the shipping provider](#initialize-the-shipping-provider-and-set-your-credentials)
- [Get Shipping Provider Metadata](#get-shipping-provider-metadata)
- [Create a parcel](#create-a-parcel--order-)
- [Get Parcel Details](#get-parcel--order--details)
- [Retrieving a label](#retrieving-a-label--order-)
- [Get Create Parcel Validation Rules](#get-create-parcel--order--validation-rules)
- [Get Rates](#get-rates)

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

### List Available Providers

```php
use CourierDZ\CourierDZ;
    
$providersMetaData = CourierDZ::providers();
```

***Output :***

![image](https://github.com/user-attachments/assets/a4453395-4304-4932-8190-5b49af40eab5)

### Get Create Parcel ( Order ) Validation Rules

```php 
$rules = $shipping_provider->getCreateOrderValidationRules();
```

***Output :***

![image](https://github.com/user-attachments/assets/8049a9f1-c294-4714-ad56-8a28e4a2339a)

### Get Shipping Provider Metadata

```php
$metadata = $shipping_provider->metadata();
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
```
 
echo $shipping_provider->testCredentials() ? 'Valid.' : 'Invalid.';

### Get Rates

```php
/*
 * return array of rates of shipping from one wilaya to another 
 * or all rates depending on the parameters / provider api.
 */
 
$rates = $shipping_provider->getRates();  // all rates

$rates = $shipping_provider->getRates(null , $to_wilaya_id); // rates to specific wilaya
        
$rates = $shipping_provider->getRates($from_wilaya_id , $to_wilaya_id); // Yalidine require $from_wilaya_id , $to_wilaya_id
```

### Get Parcel ( Order ) Details

```php  
/*
 * return array of order details 
 * Note : results may vary depending on the provider
 */
 
$result = $shippingService->getOrder('CourierDz-123');
`
