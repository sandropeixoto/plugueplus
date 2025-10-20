<?php

class Router
{
    private array $routes = [];

    public function add(string $method, string $path, callable $handler, bool $authRequired = false): void
    {
        $method = strtoupper($method);
        $normalizedPath = '/' . ltrim($path, '/');
        if ($normalizedPath !== '/' && str_ends_with($normalizedPath, '/')) {
            $normalizedPath = rtrim($normalizedPath, '/');
        }

        $segments = array_filter(explode('/', trim($normalizedPath, '/')));
        $patternSegments = [];
        $paramNames = [];

        foreach ($segments as $segment) {
            if (preg_match('/^\{([a-zA-Z_][a-zA-Z0-9_]*)\}$/', $segment, $matches)) {
                $paramName = $matches[1];
                $paramNames[] = $paramName;
                $patternSegments[] = '(?P<' . $paramName . '>[^/]+)';
            } else {
                $patternSegments[] = preg_quote($segment, '#');
            }
        }

        $regex = $patternSegments
            ? '#^/' . implode('/', $patternSegments) . '$#'
            : '#^/$#';

        $this->routes[$method][] = [
            'path' => $normalizedPath,
            'handler' => $handler,
            'auth' => $authRequired,
            'pattern' => $regex,
            'params' => $paramNames,
            'hasParams' => !empty($paramNames),
        ];
    }

    public function dispatch(string $method, string $path): void
    {
        $method = strtoupper($method);
        $path = parse_url($path, PHP_URL_PATH) ?? '/';
        $path = '/' . ltrim($path, '/');
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }

        if (!isset($this->routes[$method])) {
            Response::json(['message' => 'Route not found'], 404);
            return;
        }

        foreach ($this->routes[$method] as $route) {
            $params = [];
            $matched = false;

            if ($route['hasParams']) {
                if (preg_match($route['pattern'], $path, $matches)) {
                    foreach ($matches as $key => $value) {
                        if (!is_int($key)) {
                            $params[$key] = $value;
                        }
                    }
                    $matched = true;
                }
            } elseif ($route['path'] === $path) {
                $matched = true;
            }

            if (!$matched) {
                continue;
            }

            if ($route['auth'] && !AuthMiddleware::check()) {
                return;
            }

            $handler = $route['handler'];
            $args = $params ? [$params] : [];

            try {
                $handler(...$args);
            } catch (Throwable $throwable) {
                error_log('[Router] ' . $throwable->getMessage());

                $payload = [
                    'message' => 'Erro interno do servidor. Por favor, tente novamente em instantes.',
                ];

                if (function_exists('env') && env('APP_ENV', 'production') !== 'production') {
                    $payload['debug'] = [
                        'type' => get_class($throwable),
                        'message' => $throwable->getMessage(),
                    ];
                }

                Response::json($payload, 500);
            }
            return;
        }

        Response::json(['message' => 'Route not found'], 404);
    }
}
