<?php

namespace CourierDZ\Tests\Services;

use CourierDZ\CourierDZ;
use CourierDZ\Enum\ShippingProvider;
use CourierDZ\Exceptions\CreateOrderValidationException;
use CourierDZ\Exceptions\CredentialsException;
use CourierDZ\Services\ShippingService;
use Mockery;

test('it can boot', function () {

    $service = new ShippingService(ShippingProvider::ZREXPRESS->value, ['token' => 123, 'key' => 123]);

    expect($service)->toBeInstanceOf(\CourierDZ\Services\ShippingService::class);

});

it('need a credentials to boot', function () {

    $courier = new CourierDZ;

    $courier->provider(ShippingProvider::ZREXPRESS, [

    ]);

})->throws(CredentialsException::class);

it('need a valid credentials keys format', function () {

    $courier = new CourierDZ;

    $providers = $courier->providers();

    $shippingService = $courier->provider(ShippingProvider::ZREXPRESS, [
        'token_invalid_key' => '1234567890',
        'key_invalid_key' => '1234567890',
    ]);

})->throws(CredentialsException::class);

it('return true if credentials are valid', function () {

    $shippingService = Mockery::mock(ShippingService::class);
    $shippingService->shouldReceive('testCredentials')->andReturn(true);

    expect($shippingService->testCredentials())->toBeTrue();

});

test('get create order validation rules', function () {

    $courier = new CourierDZ;

    $zr = $courier->provider(ShippingProvider::ZREXPRESS, [
        'token' => '1234567890',
        'key' => '1234567890',
    ]);

    expect($zr->getCreateOrderValidationRules())->toBeArray();

});

test('throw error on invalid create order data', function () {

    $courier = new CourierDZ;

    $zr = $courier->provider(ShippingProvider::ZREXPRESS, [
        'token' => '1234567890',
        'key' => '1234567890',
    ]);

    $zr->createOrder([]);

})->throws(CreateOrderValidationException::class);

test('create order', function () {

    $shippingService = Mockery::mock(ShippingService::class);
    $shippingService->shouldReceive('createOrder')->andReturn([]);

    expect($shippingService->createOrder([]))->toBeArray();

});

test('get order', function () {})->skip(); // @todo

test('cancel order', function () {})->skip(); // @todo

test('order label', function () {})->skip(); // @todo

it('return array for getRates', function () {

    $shippingService = Mockery::mock(ShippingService::class);
    $shippingService->shouldReceive('getRates')->andReturn([]);

    expect($shippingService->getRates())->toBeArray();

});

test('get providers', function () {

    $providers = CourierDZ::providers();

    expect($providers)->toBeArray();
});

test('meta data', function () {

    $courier = new CourierDZ;

    $zr = $courier->provider(ShippingProvider::ZREXPRESS, [
        'token' => '1234567890',
        'key' => '1234567890',
    ]);

    expect($zr->metaData())->toBeArray();

});
