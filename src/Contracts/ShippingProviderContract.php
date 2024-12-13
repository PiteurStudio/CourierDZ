<?php

namespace CourierDZ\Contracts;

interface ShippingProviderContract
{
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
     * Get a list of all available wilayas.
     *
     * This method is called by the ShippingService to get the rates for
     * the current provider.
     *
     * @param  int|null  $from_wilaya_id  The ID of the wilaya to get rates from
     * @param  int|null  $to_wilaya_id  The ID of the wilaya to get rates to
     * @return array An array of shipping rates, each containing the price, and wilaya IDs
     */
    public function getRates(?int $from_wilaya_id, ?int $to_wilaya_id): array;

    /**
     * Create a new order.
     *
     * This method is called by the ShippingService to create a new order
     * using the current provider.
     *
     * @param  array  $orderData  The order data to create an order with
     * @return array An array containing the order ID and the tracking ID
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
     * This method is called by the ShippingService to read an order
     * using the current provider.
     *
     * @param  string  $trackingId  The tracking ID of the order to retrieve
     * @return array An array containing the order details
     */
    public function getOrder(string $trackingId): array;

    /**
     * Retrieve the label for an order.
     *
     * This method is called by the ShippingService to retrieve the label
     * for an order using the current provider.
     *
     * @param  string  $orderId  The ID of the order to retrieve the label for
     * @return array An array containing the label details of the order
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
     * @return array An array containing metadata of the provider
     */
    public static function metadata(): array; // Return name, logo, description

    /**
     * Validate data for creating an order.
     *
     * This method is called by the ShippingService to validate the order data
     * before creating a new order using the current provider.
     *
     * @param  array  $data  The order data to validate
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
