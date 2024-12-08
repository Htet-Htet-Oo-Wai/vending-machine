<?php

namespace App\Http\Requests;

class AddToCartRequest
{
    protected $data;
    protected $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Validate the product request data.
     *
     * @return bool
     */
    public function validate(): bool
    {
        // Validate product name
        if (empty($this->data['product_name'])) {
            $this->errors['product_name'] = 'The product name is required.';
        } elseif (!is_string($this->data['product_name'])) {
            $this->errors['product_name'] = 'The product name must be a string.';
        }

        // Validate product price
        if (!isset($this->data['product_price'])) {
            $this->errors['product_price'] = 'The product price is required.';
        } elseif (!is_numeric($this->data['product_price']) || $this->data['product_price'] <= 0) {
            $this->errors['product_price'] = 'The product price must be a positive number.';
        }

        // Validate quantity
        if (!isset($this->data['quantity'])) {
            $this->errors['quantity'] = 'The quantity is required.';
        } elseif (!is_numeric($this->data['quantity']) || $this->data['quantity'] <= 0) {
            $this->errors['quantity'] = 'The quantity must be a positive number.';
        }

        return empty($this->errors);
    }

    /**
     * Get the validation errors.
     *
     * @return array
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Get sanitized data.
     *
     * @return array
     */
    public function sanitized(): array
    {
        return [
            'product_name' => filter_var($this->data['product_name'] ?? '', FILTER_SANITIZE_STRING),
            'product_price' => filter_var($this->data['product_price'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
            'quantity' => filter_var($this->data['quantity'] ?? 0, FILTER_SANITIZE_NUMBER_INT),
        ];
    }
}
