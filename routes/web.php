<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Providers\Router;

$router = new Router();

// Product routes
$router->get('/', [AuthController::class, 'redirect']);

$router->get('login', [AuthController::class, 'login']);
$router->post('login', [AuthController::class, 'postLogin']);
$router->post('logout', [AuthController::class, 'logout']);

$router->get('admin/products', [ProductsController::class, 'index']);
$router->get('admin/products/create', [ProductsController::class, 'create']);
$router->post('admin/products', [ProductsController::class, 'store']);
$router->get('admin/products/{id}/edit', [ProductsController::class, 'edit']);
$router->post('admin/products/{id}/update', [ProductsController::class, 'update']);
$router->post('admin/products/{id}/delete', [ProductsController::class, 'destroy']);

$router->get('admin/users', [UserController::class, 'index']);
$router->get('admin/users/create', [UserController::class, 'create']);
$router->post('admin/users', [UserController::class, 'store']);
$router->get('admin/users/{id}/edit', [UserController::class, 'edit']);
$router->post('admin/users/{id}/update', [UserController::class, 'update']);
$router->post('admin/users/{id}/delete', [UserController::class, 'destroy']);

$router->get('admin/orders', [OrderController::class, 'index']);
$router->get('orders', [OrderController::class, 'getAuthUserOrders']);

$router->get('products', [ProductsController::class, 'getAllProducts']);
$router->post('products/{id}/addtocart', [ProductsController::class, 'addToCart']);
$router->post('products/purchase', [ProductsController::class, 'purchase']);
$router->get('cart', [ProductsController::class, 'viewCart']);
$router->get('cart/clear', [ProductsController::class, 'clearCart']);

return $router;