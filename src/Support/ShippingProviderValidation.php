<?php

namespace CourierDZ\Support;

use CourierDZ\Exceptions\CreateOrderValidationException;

trait ShippingProviderValidation
{
    /**
     * Validate the data for creating an order.
     *
     * This method uses the Laravel Validator to check if the data for creating an order is valid.
     * The rules for the validation are provided by the ShippingProvider using the getCreateOrderValidationRules method.
     *
     * @param  array<non-empty-string, non-empty-string>  $orderData  The data to validate
     * @return bool True if the validation passes
     * @throws CreateOrderValidationException If the validation fails
     */
    public function validateCreate(array $orderData): bool
    {
        // Create a validator instance
        $validator = ValidatorSetup::makeValidator()->make($orderData, $this->getCreateOrderValidationRules);

        // Check if the validation fails
        if ($validator->fails()) {
            // Throw a CreateOrderValidationException
            throw new CreateOrderValidationException('Create Order Validation failed: '.json_encode($validator->errors()));
        }

        // Return true if the validation passes
        return true;
    }
}
