<?php
namespace App\Core;

class Router
{
    protected $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => []
    ];

    public function get($uri, $callback, $middleware = [])
    {
        $this->routes['GET'][$uri] = [
            'callback' => $callback,
            'middleware' => $middleware
        ];
        return $this;
    }

    public function post($uri, $callback, $middleware = [])
    {
        $this->routes['POST'][$uri] = [
            'callback' => $callback,
            'middleware' => $middleware
        ];
        return $this;
    }

    public function put($uri, $callback, $middleware = [])
    {
        $this->routes['PUT'][$uri] = [
            'callback' => $callback,
            'middleware' => $middleware
        ];
        return $this;
    }

    public function delete($uri, $callback, $middleware = [])
    {
        $this->routes['DELETE'][$uri] = [
            'callback' => $callback,
            'middleware' => $middleware
        ];
        return $this;
    }

    public function route($uri)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Handle method spoofing for HTML forms
        if ($method === 'POST' && isset($_POST['_method'])) {
            $spoofedMethod = strtoupper($_POST['_method']);
            if (in_array($spoofedMethod, ['PUT', 'DELETE', 'PATCH'])) {
                $method = $spoofedMethod;
            }
        }
        
        // Remove trailing slash
        $uri = rtrim($uri, '/');
        
        // Add leading slash if missing
        if (strpos($uri, '/') !== 0) {
            $uri = '/' . $uri;
        }
        
        // Check for exact match first
        if (isset($this->routes[$method][$uri])) {
            $route = $this->routes[$method][$uri];
            $callback = $route['callback'];
            $middleware = $route['middleware'];
            
            // Apply middleware
            if (!$this->applyMiddleware($middleware)) {
                return;
            }
            
            if (is_callable($callback)) {
                call_user_func($callback);
            } elseif (is_string($callback)) {
                // Handle controller@method format
                $this->callController($callback);
            }
            return;
        }
        
        // Check for pattern matches
        foreach ($this->routes[$method] as $routeUri => $route) {
            // Convert route pattern to regex
            $pattern = preg_replace('/\(([^\)]+)\)/', '([^/]+)', $routeUri);
            $pattern = '#^' . $pattern . '$#';
            
            if (preg_match($pattern, $uri, $matches)) {
                // Remove the full match
                array_shift($matches);
                
                $callback = $route['callback'];
                $middleware = $route['middleware'];
                
                // Apply middleware
                if (!$this->applyMiddleware($middleware)) {
                    return;
                }
                
                if (is_callable($callback)) {
                    call_user_func_array($callback, $matches);
                } elseif (is_string($callback)) {
                    // Handle controller@method format with parameters
                    $this->callController($callback, $matches);
                }
                return;
            }
        }
        
        // Default route or 404
        $this->handleNotFound();
    }

    protected function applyMiddleware($middleware)
    {
        foreach ($middleware as $mw) {
            $middlewareInstance = Middleware::resolve($mw);
            if ($middlewareInstance) {
                $next = function() { return true; };
                $result = $middlewareInstance->handle($next);
                if ($result === false) {
                    return false;
                }
            }
        }
        return true;
    }

    protected function callController($callback, $params = [])
    {
        // Split controller and method
        list($controller, $method) = explode('@', $callback);
        
        // Build full controller class name
        $controller = "App\\Controllers\\" . $controller;
        
        // Check if controller exists
        if (class_exists($controller)) {
            $controllerInstance = new $controller();
            
            // Check if method exists
            if (method_exists($controllerInstance, $method)) {
                call_user_func_array([$controllerInstance, $method], $params);
            } else {
                $this->handleNotFound();
            }
        } else {
            $this->handleNotFound();
        }
    }

    protected function handleNotFound()
    {
        http_response_code(404);
        echo "<h1>404 - Page Not Found</h1>";
        echo "<p>The requested page could not be found.</p>";
    }
    
    // Method to get routes for debugging (only for development)
    public function getRoutes()
    {
        return $this->routes;
    }
}