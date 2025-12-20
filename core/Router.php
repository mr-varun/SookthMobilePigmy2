<?php
/**
 * Router Class
 * Handles URL routing and request dispatching
 */
class Router
{
    protected $routes = [];
    protected $notFound = null;

    /**
     * Add a GET route
     */
    public function get($path, $callback)
    {
        $this->addRoute('GET', $path, $callback);
    }

    /**
     * Add a POST route
     */
    public function post($path, $callback)
    {
        $this->addRoute('POST', $path, $callback);
    }

    /**
     * Add a route for any method
     */
    public function any($path, $callback)
    {
        $this->addRoute('ANY', $path, $callback);
    }

    /**
     * Add a route
     */
    protected function addRoute($method, $path, $callback)
    {
        $path = trim($path, '/');
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback
        ];
    }

    /**
     * Set 404 handler
     */
    public function notFound($callback)
    {
        $this->notFound = $callback;
    }

    /**
     * Dispatch the current request
     */
    public function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        
        // Try to get URL from .htaccess rewrite first
        if (isset($_GET['url'])) {
            $requestUri = $_GET['url'];
        } else {
            // Fallback: parse from REQUEST_URI
            $requestUri = $_SERVER['REQUEST_URI'];
            
            // Remove query string
            if (($pos = strpos($requestUri, '?')) !== false) {
                $requestUri = substr($requestUri, 0, $pos);
            }
            
            // Remove base path (e.g., /public)
            $scriptName = dirname($_SERVER['SCRIPT_NAME']);
            if ($scriptName !== '/' && strpos($requestUri, $scriptName) === 0) {
                $requestUri = substr($requestUri, strlen($scriptName));
            }
        }
        
        $requestUri = trim($requestUri, '/');

        // Match the route
        foreach ($this->routes as $route) {
            if ($route['method'] !== 'ANY' && $route['method'] !== $requestMethod) {
                continue;
            }

            // Convert route pattern to regex
            $pattern = $this->convertToRegex($route['path']);

            if (preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches); // Remove full match
                return $this->executeCallback($route['callback'], $matches);
            }
        }

        // No route matched - 404
        if ($this->notFound) {
            return $this->executeCallback($this->notFound, []);
        }

        http_response_code(404);
        echo "404 - Page Not Found";
    }

    /**
     * Convert route path to regex pattern
     */
    protected function convertToRegex($path)
    {
        // Replace {param} with regex capture group
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $path);
        return '#^' . $pattern . '$#';
    }

    /**
     * Execute route callback
     */
    protected function executeCallback($callback, $params)
    {
        if (is_callable($callback)) {
            return call_user_func_array($callback, $params);
        }

        if (is_string($callback)) {
            // Format: ControllerName@methodName or Path\\ControllerName@methodName
            if (strpos($callback, '@') === false) {
                throw new Exception("Invalid route callback format. Expected 'Controller@method'");
            }
            
            list($controller, $method) = explode('@', $callback);
            
            // Determine controller path based on directory structure
            // Replace backslashes with forward slashes for file path
            $controllerPath = APP_PATH . '/Controllers/' . str_replace('\\', '/', $controller) . '.php';
            
            if (file_exists($controllerPath)) {
                require_once $controllerPath;
                
                // Get just the class name (last part after / or \)
                $className = basename(str_replace('\\', '/', $controller));
                
                if (class_exists($className)) {
                    $controllerInstance = new $className();
                    if (method_exists($controllerInstance, $method)) {
                        return call_user_func_array([$controllerInstance, $method], $params);
                    } else {
                        throw new Exception("Method '$method' not found in controller '$className'");
                    }
                } else {
                    throw new Exception("Controller class '$className' not found");
                }
            } else {
                throw new Exception("Controller file not found: $controllerPath");
            }
        }

        throw new Exception("Invalid route callback");
    }
}
