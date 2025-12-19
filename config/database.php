<?php
/**
 * Database Configuration
 * 
 * This configuration reads from .env file if available,
 * otherwise falls back to default values.
 * 
 * For production: Create a .env file with your credentials
 * For development: Use .env.example as a template
 */

return [
    'host' => env('DB_HOST', 'localhost'),
    'port' => env('DB_PORT', 3306),
    'database' => env('DB_DATABASE', 'mobile_pigmy'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => env('DB_CHARSET', 'utf8mb4'),
    'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
];
