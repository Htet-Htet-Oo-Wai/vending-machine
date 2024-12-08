<?php

use App\Providers\Router;

require_once '../vendor/autoload.php';
require __DIR__ . '/../routes/web.php';
require __DIR__ . '/../routes/api.php';
global $router;
session_start();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
$router->handleRequest($uri, $method);
?>
