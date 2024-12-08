<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Firebase\JWT\JWT;

class AuthController
{
    /**
     * Handle user login and JWT token generation.
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Invalid request method']);
            return;
        }
        $request = new LoginRequest($_POST);
        if (!$request->validate()) {
            http_response_code(422);
            echo json_encode(['errors' => $request->errors()]);
            return;
        }
        $data = $request->sanitized();
        $user = User::getUser($data['username']);
        if (!$user || !password_verify($data['password'], $user['password'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
            return;
        }
        $payload = [
            'iss' => 'your_app_name',
            'sub' => $user['id'],
            'role' => $user['role_name'],
            'iat' => time(),
            'exp' => time() + 3600
        ];
        $jwt = JWT::encode($payload, config('auth.key'), 'HS256');
        http_response_code(200);
        echo json_encode(['token' => $jwt]);
    }
}
