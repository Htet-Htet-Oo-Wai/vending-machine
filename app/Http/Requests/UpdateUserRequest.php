<?php

namespace App\Http\Requests;

class UpdateUserRequest
{
    protected $data;
    protected $errors = [];

    /**
     * UpdateUserRequest constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Validate the user data.
     *
     * @return bool
     */
    public function validate(): bool
    {
        if (empty($this->data['name'] ?? '')) {
            $this->errors['name'] = 'User name is required.';
        }
        if (empty($this->data['email'] ?? '')) {
            $this->errors['email'] = 'Email is required.';
        } elseif (!filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Please enter a valid email address.';
        }
        if (empty($this->data['role_id'] ?? '')) {
            $this->errors['role_id'] = 'Role is required.';
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
            'email' => filter_var($this->data['email'] ?? '', FILTER_SANITIZE_EMAIL),
            'role_id' => filter_var($this->data['role_id'] ?? '', FILTER_SANITIZE_NUMBER_INT),
        ];
    }
}
