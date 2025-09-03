<?php
// src/Routing/Router.php

namespace App\Routing;

class Router
{
    /** @var array<string, list<array{pattern:string, handler:callable}>> */
    private array $routes = [];

    /**
     * Register a generic route for any HTTP method.
     */
    public function add(string $method, string $path, callable $handler): void
    {
        $this->addRoute(strtoupper($method), $path, $handler);
    }

    public function get(string $path, callable $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function put(string $path, callable $handler): void
    {
        $this->addRoute('PUT', $path, $handler);
    }

    public function delete(string $path, callable $handler): void
    {
        $this->addRoute('DELETE', $path, $handler);
    }

    /** @internal */
    private function addRoute(string $method, string $path, callable $handler): void
    {
        // Convert placeholders {id} → named regex groups
        $pattern = preg_replace(
            '#\{([a-zA-Z_]\w*)\}#',
            '(?P<$1>[^/]+)',
            $path
        );
        $pattern = "#^{$pattern}$#";

        $this->routes[$method][] = [
            'pattern' => $pattern,
            'handler' => $handler,
        ];
    }

    /**
     * Dispatch the incoming request to the first matching route.
     * Passes only the captured values (not named keys) to the handler.
     */
    public function dispatch(string $method, string $uri): void
    {
        $path         = parse_url($uri, PHP_URL_PATH) ?: '/';
        $methodRoutes = $this->routes[strtoupper($method)] ?? [];

        foreach ($methodRoutes as $route) {
            if (preg_match($route['pattern'], $path, $matches)) {
                // Extract only named capture groups (key => value)
                $params = array_filter(
                    $matches,
                    fn($key) => is_string($key),
                    ARRAY_FILTER_USE_KEY
                );

                // Call handler with just the values in order
                $args = array_values($params);
                call_user_func_array($route['handler'], $args);
                return;
            }
        }

        // No route matched → 404
        header("HTTP/1.1 404 Not Found");
        echo "404 Not Found";
    }
}
