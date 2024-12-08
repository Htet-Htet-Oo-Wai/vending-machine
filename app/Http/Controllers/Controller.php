<?php

namespace App\Http\Controllers;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Base Controller class that provides common functionalities
 * such as role checking and JWT token verification.
 */
class Controller
{
    /**
     * Check if the user has the required role to access a page.
     *
     * @param string $requiredRole The role required to access the page.
     * @return void
     */
    protected function checkRole(string $requiredRole): void
    {
        if (!isset($_SESSION['role_name'])) {
            header('Location: /login');
            exit;
        }
        if ($_SESSION['role_name'] !== $requiredRole) {
            http_response_code(403);
            echo "You do not have permission to access this page.";
            exit;
        }
    }

    /**
     * Verify the JWT token from the Authorization header.
     *
     * @return object Decoded JWT payload.
     */
    protected function verifyToken(): object
    {
        $headers = getallheaders();
        if (empty($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Authorization header not found']);
            exit;
        }
        $token = str_replace('Bearer ', '', $headers['Authorization']);
        try {
            return JWT::decode($token, new Key(config('auth.key'), 'HS256'));
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid token', 'message' => $e->getMessage()]);
            exit;
        }
    }
}
