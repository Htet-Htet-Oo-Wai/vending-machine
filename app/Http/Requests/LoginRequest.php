<?php

namespace App\Http\Requests;

class LoginRequest
{
    protected $data;
    protected $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Validate the login request data.
     *
     * @return bool
     */
    public function validate(): bool
    {
        if (empty($this->data['username'])) {
            $this->errors['username'] = 'The username field is required.';
        }
        if (empty($this->data['password'])) {
            $this->errors['password'] = 'The password field is required.';
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
            'username' => filter_var($this->data['username'] ?? '', FILTER_SANITIZE_STRING),
            'password' => filter_var($this->data['password'] ?? '', FILTER_SANITIZE_STRING),
        ];
    }
}
