<?php

namespace App\Core;

class Router
{
    protected $routes = [];
    protected $middlewares = [];

    public function get($uri, $action, $middleware = null)
    {
        $this->routes['GET'][$uri] = $action;
        if ($middleware) {
            $this->middlewares['GET'][$uri] = $middleware;
        }
    }

    public function post($uri, $action, $middleware = null)
    {
        $this->routes['POST'][$uri] = $action;
        if ($middleware) {
            $this->middlewares['POST'][$uri] = $middleware;
        }
    }

    public function dispatch()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = '/' . trim(str_replace(BASE_PATH, '', $uri), '/');

        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$method][$uri])) {
            // Cek apakah ada middleware yang perlu dijalankan
            if (isset($this->middlewares[$method][$uri])) {
                $middleware = $this->middlewares[$method][$uri];
                $middleware::handle(); // Jalankan middleware
            }

            $this->callAction($this->routes[$method][$uri]);
        } else {
            http_response_code(404);
            echo "404 - Not Found (URI: {$uri})";
        }
    }

    protected function callAction($action)
    {
        if (is_callable($action)) {
            call_user_func($action);
        } else {
            list($controller, $method) = explode('@', $action);
            $controller = "App\\Controllers\\{$controller}";
            $controllerObject = new $controller;
            call_user_func([$controllerObject, $method]);
        }
    }

    public function run()
    {
        $this->dispatch();
    }
}
