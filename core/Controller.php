<?php
/**
 * Base Controller Class
 * All controllers should extend this class
 */
class Controller
{
    /**
     * Load a view
     */
    protected function view($view, $data = [])
    {
        return View::render($view, $data);
    }

    /**
     * Redirect to a URL
     */
    protected function redirect($url, $statusCode = 302)
    {
        header("Location: " . $this->url($url), true, $statusCode);
        exit;
    }

    /**
     * Generate a URL
     */
    protected function url($path = '')
    {
        $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") 
                   . "://" . $_SERVER['HTTP_HOST'];
        
        // Get the directory path of index.php
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $basePath = str_replace('\\', '/', dirname($scriptName));
        
        if ($basePath === '/') {
            $basePath = '';
        }

        return $baseUrl . $basePath . '/' . ltrim($path, '/');
    }

    /**
     * Return JSON response
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Get POST data
     */
    protected function post($key = null, $default = null)
    {
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? $default;
    }

    /**
     * Get GET data
     */
    protected function get($key = null, $default = null)
    {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }

    /**
     * Check if request is POST
     */
    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Check if request is GET
     */
    protected function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * Validate CSRF token
     */
    protected function validateCsrf($token)
    {
        return Session::validateCsrf($token);
    }
}
