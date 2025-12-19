<?php
/**
 * Session Class
 * Handles session management
 */
class Session
{
    /**
     * Start session
     */
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set session value
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get session value
     */
    public static function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Check if session key exists
     */
    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove session key
     */
    public static function remove($key)
    {
        if (self::has($key)) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Destroy session
     */
    public static function destroy()
    {
        session_destroy();
        $_SESSION = [];
    }

    /**
     * Flash a message (available for one request)
     */
    public static function flash($key, $value)
    {
        self::set('flash_' . $key, $value);
    }

    /**
     * Set flash message (alias for flash())
     */
    public static function setFlash($key, $value)
    {
        self::flash($key, $value);
    }

    /**
     * Get flash message
     */
    public static function getFlash($key, $default = null)
    {
        $value = self::get('flash_' . $key, $default);
        self::remove('flash_' . $key);
        return $value;
    }

    /**
     * Delete session key (alias for remove())
     */
    public static function delete($key)
    {
        self::remove($key);
    }

    /**
     * Generate CSRF token
     */
    public static function getCsrfToken()
    {
        if (!self::has('csrf_token')) {
            self::set('csrf_token', bin2hex(random_bytes(32)));
        }
        return self::get('csrf_token');
    }

    /**
     * Validate CSRF token
     */
    public static function validateCsrf($token)
    {
        return hash_equals(self::getCsrfToken(), $token);
    }

    /**
     * Check if user is logged in
     */
    public static function isLoggedIn()
    {
        return self::has('user_id');
    }

    /**
     * Get logged in user ID
     */
    public static function getUserId()
    {
        return self::get('user_id');
    }

    /**
     * Get user role
     */
    public static function getUserRole()
    {
        return self::get('user_role');
    }

    /**
     * Login user
     */
    public static function login($userId, $role, $userData = [])
    {
        self::set('user_id', $userId);
        self::set('user_role', $role);
        self::set('user_data', $userData);
    }

    /**
     * Logout user
     */
    public static function logout()
    {
        self::destroy();
    }
}
