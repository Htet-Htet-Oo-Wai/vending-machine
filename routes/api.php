<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductsController;

global $router;

$router->post('api/login', [AuthController::class, 'login']);
$router->get('api/products', [ProductsController::class, 'index']);
$router->post('api/products/{id}/addtocart', [OrderController::class, 'addToCart']);
$router->post('api/products/purchase', [OrderController::class, 'purchase']);
$router->get('api/cart', [OrderController::class, 'viewCart']);
$router->get('api/orders', [OrderController::class, 'getAuthUserOrders']);
