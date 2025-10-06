<?php
// Vercel PHP entrypoint that boots Laravel

// Set error reporting for production
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Ensure Composer autoload is available
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    http_response_code(500);
    die('Composer dependencies not installed. Please run composer install.');
}

require __DIR__ . '/../vendor/autoload.php';

// Change working directory to project root
chdir(__DIR__ . '/..');

// Create necessary directories for Vercel serverless environment
$directories = [
    'storage/logs',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'bootstrap/cache'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Set up environment variables for Vercel
if (!isset($_ENV['APP_KEY'])) {
    $_ENV['APP_KEY'] = 'base64:' . base64_encode(random_bytes(32));
}
if (!isset($_ENV['APP_ENV'])) {
    $_ENV['APP_ENV'] = 'production';
}
if (!isset($_ENV['APP_DEBUG'])) {
    $_ENV['APP_DEBUG'] = 'false';
}
if (!isset($_ENV['APP_URL'])) {
    $_ENV['APP_URL'] = 'https://edubridge-lyart.vercel.app';
}

// Set serverless-friendly environment variables
$_ENV['LOG_CHANNEL'] = 'errorlog';
$_ENV['CACHE_STORE'] = 'array';
$_ENV['SESSION_DRIVER'] = 'array';
$_ENV['QUEUE_CONNECTION'] = 'sync';
$_ENV['DB_CONNECTION'] = 'sqlite';
$_ENV['DB_DATABASE'] = ':memory:'; // Use in-memory SQLite for serverless

// Set up server variables for Laravel
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../public/index.php';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['REQUEST_URI'] = $_SERVER['REQUEST_URI'] ?? '/';
$_SERVER['HTTP_HOST'] = $_SERVER['HTTP_HOST'] ?? 'edubridge-lyart.vercel.app';
$_SERVER['SERVER_NAME'] = $_SERVER['SERVER_NAME'] ?? 'edubridge-lyart.vercel.app';

// Initialize Laravel application
try {
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    
    // Boot the application to ensure all service providers are loaded
    $app->boot();
    
    // Handle the request
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );
    
    $response->send();
    $kernel->terminate($request, $response);
    
} catch (Exception $e) {
    http_response_code(500);
    echo 'Application Error: ' . $e->getMessage();
    // Use error_log instead of Laravel's file-based logging
    error_log('Laravel Error: ' . $e->getMessage());
    error_log('Stack trace: ' . $e->getTraceAsString());
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Fatal Error: ' . $e->getMessage();
    error_log('Fatal Error: ' . $e->getMessage());
    error_log('Stack trace: ' . $e->getTraceAsString());
}
