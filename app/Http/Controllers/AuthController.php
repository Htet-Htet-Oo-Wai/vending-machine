<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;

/**
 * AuthController handles user authentication processes.
 */
class AuthController extends Controller
{
    /**
     * Display the login view.
     *
     * @return void
     */
    public function login()
    {
        view('auth/login');
    }

    /**
     * Handle the login request.
     *
     * @return void
     */
    public function postLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request = new LoginRequest($_POST);
            if (!$request->validate()) {
                $_SESSION['errors'] = $request->errors();
                header('Location: /login');
                exit;
            }
            $data = $request->sanitized();
            $user = User::getUser($data['username']);
            if ($user && password_verify($data['password'], $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role_name'] = $user['role_name'];

                if ($user['role_name'] === config('constants.admin_role')) {
                    header('Location: /admin/products');
                } else {
                    header('Location: /products');
                }
                exit;
            } else {
                $_SESSION['errors'] = ["Invalid username or password."];
                header('Location: /login');
                exit;
            }
        }
    }

    /**
     * Handle user logout.
     *
     * @return void
     */
    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: /login');
    }
}