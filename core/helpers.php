<?php
/**
 * Global Helper Functions
 * These functions are available throughout the application
 */

/**
 * Load environment variables from .env file
 */
function loadEnv($path)
{
    if (!file_exists($path)) {
        return false;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Parse KEY=VALUE
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            // Remove quotes if present
            $value = trim($value, '"\' ');

            // Set environment variable if not already set
            if (!getenv($key)) {
                putenv("$key=$value");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }

    return true;
}

/**
 * Get environment variable with optional default
 */
function env($key, $default = null)
{
    $value = getenv($key);
    
    if ($value === false) {
        return $default;
    }

    // Convert string booleans to actual booleans
    switch (strtolower($value)) {
        case 'true':
        case '(true)':
            return true;
        case 'false':
        case '(false)':
            return false;
        case 'empty':
        case '(empty)':
            return '';
        case 'null':
        case '(null)':
            return null;
    }

    return $value;
}

/**
 * Generate a URL
 */
function url($path = '')
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
 * Get asset URL
 */
function asset($path)
{
    return url('assets/' . ltrim($path, '/'));
}

/**
 * Redirect to a URL
 */
function redirect($url, $statusCode = 302)
{
    header("Location: " . url($url), true, $statusCode);
    exit;
}

/**
 * Escape HTML output
 */
function e($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Get old input value (for form repopulation)
 */
function old($key, $default = '')
{
    return Session::get('old_' . $key, $default);
}

/**
 * Check if user is authenticated
 */
function auth()
{
    return Session::isLoggedIn();
}

/**
 * Get current user data
 */
function user($key = null)
{
    $userData = Session::get('user_data', []);
    
    if ($key === null) {
        return $userData;
    }
    
    return $userData[$key] ?? null;
}

/**
 * Get flash message
 */
function flash($key)
{
    return Session::getFlash($key);
}

/**
 * Check if there is a flash message
 */
function hasFlash($key)
{
    return Session::has('flash_' . $key);
}

/**
 * Display success message
 */
function successMessage()
{
    if (hasFlash('success')) {
        return '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    ' . e(flash('success')) . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>';
    }
    return '';
}

/**
 * Display error message
 */
function errorMessage()
{
    if (hasFlash('error')) {
        return '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ' . e(flash('error')) . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>';
    }
    return '';
}

/**
 * Display info message
 */
function infoMessage()
{
    if (hasFlash('info')) {
        return '<div class="alert alert-info alert-dismissible fade show" role="alert">
                    ' . e(flash('info')) . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>';
    }
    return '';
}

/**
 * Display all flash messages
 */
function flashMessages()
{
    return successMessage() . errorMessage() . infoMessage();
}

/**
 * Format currency
 */
function currency($amount, $symbol = '₹')
{
    return $symbol . number_format($amount, 2);
}

/**
 * Format date
 */
function formatDate($date, $format = 'd-m-Y')
{
    if (empty($date)) return '';
    
    $timestamp = is_numeric($date) ? $date : strtotime($date);
    return date($format, $timestamp);
}

/**
 * Format datetime
 */
function formatDateTime($datetime, $format = 'd-m-Y H:i:s')
{
    if (empty($datetime)) return '';
    
    $timestamp = is_numeric($datetime) ? $datetime : strtotime($datetime);
    return date($format, $timestamp);
}

/**
 * Debug helper
 */
function dd(...$vars)
{
    echo '<pre>';
    foreach ($vars as $var) {
        var_dump($var);
    }
    echo '</pre>';
    die();
}

/**
 * Get config value
 */
function config($key, $default = null)
{
    static $config = null;
    
    if ($config === null) {
        $config = [
            'app' => require CONFIG_PATH . '/app.php',
            'database' => require CONFIG_PATH . '/database.php'
        ];
    }
    
    $keys = explode('.', $key);
    $value = $config;
    
    foreach ($keys as $k) {
        if (!isset($value[$k])) {
            return $default;
        }
        $value = $value[$k];
    }
    
    return $value;
}
