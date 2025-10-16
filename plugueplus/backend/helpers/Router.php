<?php

class Router
{
    private array $routes = [];

    public function add(string $method, string $path, callable $handler, bool $authRequired = false): void
    {
        $method = strtoupper($method);
        $this->routes[$method][$path] = [
            'handler' => $handler,
            'auth' => $authRequired,
        ];
    }

    public function dispatch(string $method, string $path): void
    {
        $method = strtoupper($method);
        $path = parse_url($path, PHP_URL_PATH);
        $path = rtrim($path, '/') ?: '/';

        if (!isset($this->routes[$method][$path])) {
            Response::json(['message' => 'Route not found'], 404);
            return;
        }

        $route = $this->routes[$method][$path];

        if ($route['auth'] && !AuthMiddleware::check()) {
            return;
        }

        $handler = $route['handler'];
        $handler();
    }
}
