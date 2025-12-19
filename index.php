<?php
/**
 * Mobile Pigmy Application
 * Front Controller - All requests go through this file
 */

// Set Indian timezone for entire application
date_default_timezone_set('Asia/Kolkata');

// Define base paths
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');
define('STORAGE_PATH', BASE_PATH . '/storage');
define('PUBLIC_PATH', __DIR__);

// Load configuration
require_once CONFIG_PATH . '/app.php';
require_once CONFIG_PATH . '/database.php';

// Load core files
require_once BASE_PATH . '/core/Router.php';
require_once BASE_PATH . '/core/Database.php';
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/core/View.php';
require_once BASE_PATH . '/core/Session.php';
require_once BASE_PATH . '/core/helpers.php';
require_once BASE_PATH . '/core/license-helper.php';

// Initialize session
Session::start();

// Initialize database connection
Database::connect();

// Load routes
$router = new Router();
require_once BASE_PATH . '/routes/web.php';

// Dispatch the request
$router->dispatch();
