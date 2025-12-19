<?php
/**
 * CSRF Middleware
 * Protects against CSRF attacks
 */

class CsrfMiddleware
{
    /**
     * Validate CSRF token
     */
    public static function validate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
            
            if (!Session::validateCsrf($token)) {
                http_response_code(403);
                die('CSRF token validation failed');
            }
        }
        
        return true;
    }

    /**
     * Generate CSRF token field for forms
     */
    public static function field()
    {
        $token = Session::getCsrfToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }

    /**
     * Get CSRF token
     */
    public static function token()
    {
        return Session::getCsrfToken();
    }
}
