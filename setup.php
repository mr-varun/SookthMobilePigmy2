<?php
/**
 * Setup Helper Script
 * Run this file once to set up the application
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load core helpers (needed for env() function)
require_once __DIR__ . '/core/helpers.php';

// Load environment variables from .env file
loadEnv(__DIR__ . '/.env');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Setup - Mobile Pigmy App</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>
        body { padding: 2rem; background: #f8f9fa; }
        .setup-container { max-width: 800px; margin: 0 auto; background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .success { color: #10b981; }
        .error { color: #ef4444; }
        .check { margin: 0.5rem 0; padding: 0.5rem; border-left: 3px solid #cbd5e0; }
        .check.ok { border-left-color: #10b981; background: #ecfdf5; }
        .check.fail { border-left-color: #ef4444; background: #fef2f2; }
    </style>
</head>
<body>
    <div class='setup-container'>
        <h1 class='mb-4'>🚀 Mobile Pigmy App Setup</h1>";

// Check PHP version
echo "<h3>System Requirements</h3>";
$phpVersion = phpversion();
$phpOk = version_compare($phpVersion, '7.4.0', '>=');
echo "<div class='check " . ($phpOk ? 'ok' : 'fail') . "'>
    PHP Version: $phpVersion " . ($phpOk ? '✓' : '✗ (Required: 7.4+)') . "
</div>";

// Check extensions
$extensions = ['mysqli', 'json', 'mbstring', 'session'];
foreach ($extensions as $ext) {
    $loaded = extension_loaded($ext);
    echo "<div class='check " . ($loaded ? 'ok' : 'fail') . "'>
        Extension '$ext': " . ($loaded ? 'Loaded ✓' : 'Not loaded ✗') . "
    </div>";
}

// Check and create directories
echo "<h3 class='mt-4'>Directory Permissions</h3>";
$dirs = [
    __DIR__ . '/storage' => 'Storage',
    __DIR__ . '/storage/backups' => 'Backups',
    __DIR__ . '/storage/cache' => 'Cache',
    __DIR__ . '/storage/logs' => 'Logs',
    __DIR__ . '/storage/uploads' => 'Uploads',
    __DIR__ . '/assets' => 'Assets'
];

foreach ($dirs as $dir => $name) {
    $exists = is_dir($dir);
    
    // Try to create directory if it doesn't exist
    if (!$exists) {
        $created = @mkdir($dir, 0755, true);
        $exists = is_dir($dir);
    }
    
    $writable = $exists && is_writable($dir);
    $status = $exists && $writable ? 'ok' : 'fail';
    echo "<div class='check $status'>
        $name Directory: " . 
        ($exists ? ($writable ? 'Writable ✓' : 'Not writable ✗') : 'Could not create ✗') . 
    "</div>";
}

// Check configuration
echo "<h3 class='mt-4'>Configuration</h3>";

// Database config
$dbConfig = __DIR__ . '/config/database.php';
$dbConfigExists = file_exists($dbConfig);
echo "<div class='check " . ($dbConfigExists ? 'ok' : 'fail') . "'>
    Database Config: " . ($dbConfigExists ? 'Exists ✓' : 'Missing ✗') . "
</div>";

// Test database connection
if ($dbConfigExists) {
    try {
        $config = require $dbConfig;
        $conn = new mysqli(
            $config['host'],
            $config['username'],
            $config['password'],
            $config['database'],
            $config['port']
        );
        
        if ($conn->connect_error) {
            echo "<div class='check fail'>
                Database Connection: Failed ✗<br>
                <small>Error: " . $conn->connect_error . "</small>
            </div>";
        } else {
            echo "<div class='check ok'>
                Database Connection: Success ✓
            </div>";
            
            // Check tables
            $tables = ['accounts', 'admin', 'agent', 'backuptransaction', 'branch', 'licence_management', 'transactions'];
            $missingTables = [];
            foreach ($tables as $table) {
                $result = $conn->query("SHOW TABLES LIKE '$table'");
                if ($result->num_rows === 0) {
                    $missingTables[] = $table;
                }
            }
            
            if (empty($missingTables)) {
                echo "<div class='check ok'>Database Tables: All required tables exist ✓</div>";
            } else {
                echo "<div class='check fail'>
                    Missing Tables: " . implode(', ', $missingTables) . " ✗<br>
                    <small>Please import your database schema</small>
                </div>";
            }
            
            $conn->close();
        }
    } catch (Exception $e) {
        echo "<div class='check fail'>
            Database Test: Failed ✗<br>
            <small>Error: " . $e->getMessage() . "</small>
        </div>";
    }
}

// .htaccess check
$htaccess = __DIR__ . '/.htaccess';
$htaccessExists = file_exists($htaccess);
echo "<div class='check " . ($htaccessExists ? 'ok' : 'fail') . "'>
    .htaccess File: " . ($htaccessExists ? 'Exists ✓' : 'Missing ✗') . "
</div>";

// Check if mod_rewrite is enabled (Apache only)
if (function_exists('apache_get_modules')) {
    $modRewrite = in_array('mod_rewrite', apache_get_modules());
    echo "<div class='check " . ($modRewrite ? 'ok' : 'fail') . "'>
        Apache mod_rewrite: " . ($modRewrite ? 'Enabled ✓' : 'Not enabled ✗') . "
    </div>";
}

// Next steps
echo "<h3 class='mt-4'>Next Steps</h3>
<ol>
    <li>Ensure all checks above pass (green)</li>
    <li>Import your database using the SQL files in database/migrations/</li>
    <li>Copy images from old SMP/img/ to assets/img/ (if migrating)</li>
    <li>Configure your .env file with proper database credentials</li>
    <li>Access the application at your configured URL</li>
    <li><strong>Delete this setup.php file after setup is complete</strong></li>
</ol>";

// Configuration info
echo "<h3 class='mt-4'>Configuration Info</h3>
<div class='alert alert-info'>
    <strong>Document Root:</strong> Should point to: <code>" . __DIR__ . "</code><br>
    <strong>Application Path:</strong> <code>" . __DIR__ . "</code><br>
    <strong>Database Config:</strong> <code>" . __DIR__ . "/config/database.php</code>
</div>";

// Quick links
echo "<h3 class='mt-4'>Quick Access</h3>
<div class='d-grid gap-2'>
    <a href='index.php' class='btn btn-primary'>Go to Application Home</a>
    <a href='index.php?url=admin/login' class='btn btn-outline-primary'>Admin Login</a>
    <a href='public/index.php?url=agent/login' class='btn btn-outline-success'>Agent Login</a>
    <a href='public/index.php?url=bank/login' class='btn btn-outline-secondary'>Bank Login</a>
</div>";

echo "<div class='alert alert-warning mt-4'>
    <strong>⚠️ Security Warning:</strong> Delete this setup.php file after completing the setup!
</div>";

echo "</div>
</body>
</html>";
