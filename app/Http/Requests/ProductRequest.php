<?php

namespace App\Http\Requests;

class ProductRequest
{
    protected $data;
    protected $errors = [];

    /**
     * ProductRequest constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Validate the request data.
     *
     * @return bool
     */
    public function validate(): bool
    {
        // Validate product name
        if (empty($this->data['name'] ?? '')) {
            $this->errors['name'] = 'Please enter a product name.';
        }

        // Validate price
        if (empty($this->data['price'] ?? '')) {
            $this->errors['price'] = 'Please enter a price.';
        } elseif (!is_numeric($this->data['price'])) {
            $this->errors['price'] = 'Please enter a valid price.';
        } elseif ($this->data['price'] <= 0) {
            $this->errors['price'] = 'Price must be greater than $0.01.';
        }

        // Validate stock quantity
        if (empty($this->data['quantity_available'] ?? '')) {
            $this->errors['quantity_available'] = 'Please enter a stock quantity.';
        } elseif (!is_numeric($this->data['quantity_available'])) {
            $this->errors['quantity_available'] = 'Please enter a valid stock quantity.';
        } elseif ($this->data['quantity_available'] <= 0) {
            $this->errors['quantity_available'] = 'Stock quantity must be greater than or equal 1.';
        }

        return empty($this->errors);
    }

    /**
     * Get validation errors.
     *
     * @return array
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Sanitize the input data.
     *
     * @return array
     */
    public function sanitized(): array
    {
        return [
            'name' => filter_var($this->data['name'] ?? '', FILTER_SANITIZE_STRING),
            'price' => filter_var($this->data['price'] ?? '', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
            'quantity_available' => filter_var($this->data['quantity_available'] ?? '', FILTER_SANITIZE_NUMBER_INT),
        ];
    }
}
