<?php

namespace App\Providers;

class Router
{
    private $routes = [];

    public function get(string $uri, $handler, $needAuth = false): void
    {
        $this->addRoute('GET', $uri, $handler, $needAuth);
    }

    public function post(string $uri, $handler, $needAuth = false): void
    {
        $this->addRoute('POST', $uri, $handler, $needAuth);
    }

    private function addRoute(string $method, string $uri, $handler, $needAuth): void
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $this->normalizeUri($uri),
            'handler' => $handler,
            'needAuth' => $needAuth
        ];
    }

    public function handleRequest(string $uri, string $method): void
    {
        $normalizedUri = $this->normalizeUri($uri);

        foreach ($this->routes as $route) {
            if ($this->routeMatches($route, $normalizedUri, $method)) {
                if ($route['needAuth'] && !isset($_SESSION['user_id'])) {
                    http_response_code(302);
                    header('Location: /login');
                    exit;
                }
                $params = $this->extractParams($route['uri'], $normalizedUri);
                $this->dispatch($route['handler'], $params);
                return;
            }
        }

        $this->sendNotFoundResponse();
    }

    private function normalizeUri(string $uri): string
    {
        return trim($uri, '/');
    }

    private function routeMatches(array $route, string $uri, string $method): bool
    {
        if ($route['method'] !== $method) {
            return false;
        }

        return $this->uriMatches($route['uri'], $uri);
    }

    private function uriMatches(string $routeUri, string $requestUri): bool
    {
        $routeParts = explode('/', $routeUri);
        $uriParts = explode('/', $requestUri);

        if (count($routeParts) !== count($uriParts)) {
            return false;
        }

        foreach ($routeParts as $index => $part) {
            if ($this->isDynamicParam($part)) {
                continue;
            }

            if ($part !== $uriParts[$index]) {
                return false;
            }
        }

        return true;
    }

    private function isDynamicParam(string $part): bool
    {
        return strpos($part, '{') === 0 && strpos($part, '}') === strlen($part) - 1;
    }

    private function extractParams(string $routeUri, string $requestUri): array
    {
        $routeParts = explode('/', $routeUri);
        $uriParts = explode('/', $requestUri);

        $params = [];
        foreach ($routeParts as $index => $part) {
            if ($this->isDynamicParam($part)) {
                $paramName = trim($part, '{}');
                $params[$paramName] = $uriParts[$index];
            }
        }

        return $params;
    }

    private function dispatch($handler, array $params): void
    {
        if (is_callable($handler)) {
            call_user_func_array($handler, $params);
        } elseif (is_array($handler)) {
            [$controller, $method] = $handler;
            $controllerInstance = $this->resolveController($controller);
            call_user_func_array([$controllerInstance, $method], $params);
        }
    }

    private function sendNotFoundResponse(): void
    {
        http_response_code(404);
        echo "404 Not Found";
    }

    private function resolveController($controller)
    {
        $reflectionClass = new \ReflectionClass($controller);
        $constructor = $reflectionClass->getConstructor();

        if (!$constructor) {
            return new $controller();
        }

        $dependencies = [];
        foreach ($constructor->getParameters() as $parameter) {
            $dependencies[] = $this->resolveDependency($parameter->name);
        }

        return $reflectionClass->newInstanceArgs($dependencies);
    }

    private function resolveDependency($dependencyClass)
    {
        if ($dependencyClass ===  'db') {
            return new DBConnection();
        }
        throw new \Exception("Unresolvable dependency: $dependencyClass");
    }
}
