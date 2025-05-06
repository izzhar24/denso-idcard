<?php

namespace App\Core;

class Router
{
    protected $routes = [];
    protected $middlewareGroups = [];
    protected $currentMiddleware = [];
    public function get($uri, $action)
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post($uri, $action)
    {
        $this->addRoute('POST', $uri, $action);
    }

    protected function addRoute($method, $uri, $action)
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action,
            'middleware' => $this->currentMiddleware
        ];
    }

    public function middleware($middleware)
    {
        $this->currentMiddleware = (array) $middleware;

        return $this;
    }

    public function group(callable $callback)
    {
        $callback($this);
        $this->currentMiddleware = [];
    }

    public function dispatch($requestUri, $requestMethod)
    {
        foreach ($this->routes as $route) {
            if ($route['uri'] === $requestUri && $route['method'] === $requestMethod) {
                $this->runMiddleware($route['middleware']);

                // Tangani jika route menggunakan Closure
                if (is_callable($route['action'])) {
                    return call_user_func($route['action']);
                }

                // Tangani jika route menggunakan [Controller::class, 'method']
                [$controllerClass, $method] = $route['action'];
                $controller = new $controllerClass;
                return $controller->$method();
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }

    protected function runMiddleware(array $middlewareList)
    {
        $middlewareMap = require __DIR__ . '/../Core/kernel.php';

        foreach ($middlewareList as $middlewareName) {
            if (isset($middlewareMap[$middlewareName])) {
                $middlewareClass = $middlewareMap[$middlewareName];
                (new $middlewareClass)->handle();
            }
        }
    }
}
