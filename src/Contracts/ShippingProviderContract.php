<?php

declare(strict_types=1);

namespace CourierDZ\Contracts;

interface ShippingProviderContract
{
    /**
     * Get validation rules for creating an order.
     *
     * This method is called by the ShippingService to retrieve the validation
     * rules required for creating a new order using the current provider.
     *
     * @return array<non-empty-string, non-empty-string|array<int, non-empty-string>> An array of validation rules for order creation.
     */
    public function getCreateOrderValidationRules(): array;

    /**
     * Check if the credentials are valid.
     *
     * This method is called by the ShippingService to verify that the credentials
     * for the current provider are valid.
     *
     * @return bool True if the credentials are valid, false otherwise.
     */
    public function testCredentials(): bool;

    /**
     * Get shipping rates for every wilaya or for a specific wilaya.
     *
     * @param  int<1, 58>|null  $from_wilaya_id  The ID of the wilaya to get rates from
     * @param  int<1, 58>|null  $to_wilaya_id  The ID of the wilaya to get rates to
     * @return array<int , mixed> An array of shipping rates, each containing the price, and wilaya IDs
     */
    public function getRates(?int $from_wilaya_id, ?int $to_wilaya_id): array;

    /**
     * Create a new order.
     *
     * This method delegates the order creation to the provider's
     * implementation of the createOrder method.
     *
     * @param  array<non-empty-string, mixed>  $orderData  The order data to create an order with
     * @return array<non-empty-string, mixed> An array containing the order ID and the tracking ID
     */
    public function createOrder(array $orderData): array;

    //    /**
    //     * Update an existing order.
    //     *
    //     * @param  array  $orderData
    //     */
    //    //    public function updateOrder(array $orderData): array;

    /**
     * Read an order by its tracking ID.
     *
     * This method delegates the order retrieval to the provider's
     * implementation of the getOrder method.
     *
     * @param  non-empty-string  $trackingId  The tracking ID of the order to retrieve
     * @return array<non-empty-string, mixed> An array containing the order details
     */
    public function getOrder(string $trackingId): array;

    /**
     * Retrieve the label for a specific order.
     *
     * This method delegates the task to the provider's implementation of the
     * orderLabel method, which returns the label details for the given order ID.
     *
     * @param  non-empty-string  $orderId  The ID of the order for which to retrieve the label.
     * @return array{type: 'pdf'|'url', data: non-empty-string} An array containing the label details of the order.
     */
    public function orderLabel(string $orderId): array;

    /**
     * Get the status of an order.
     *
     * @param  string  $trackingId
     */
    //    public function trackOrder(string $trackingId): array;

    /**
     * Cancel an order.
     *
     * @param  string  $orderId
     * @return bool
     */
    //    public function cancelOrder(string $orderId): bool;

    /**
     * Get metadata for the provider.
     *
     * This method is called by the ShippingService to retrieve metadata about
     * the current provider.
     *
     * @return array<non-empty-string, non-empty-string|null> An array containing metadata of the provider
     */
    public static function metadata(): array; // Return name, logo, description

    /**
     * Validate the order creation data.
     *
     * This method delegates the validation of the order data
     * to the provider's implementation of the validateCreate method.
     *
     * @param  array<non-empty-string, non-empty-string>  $data  The order data to validate
     * @return bool True if the order data is valid, false otherwise
     */
    public function validateCreate(array $data): bool;

    /**
     * Validate data for updating an order.
     *
     * @param  array  $data
     * @return void
     */
    //    public function validateUpdate(array $data): void;
}
