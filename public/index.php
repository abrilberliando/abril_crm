<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

define('LARAVEL_START', microtime(true));

// CRM Application Banner
$crm_banner = "
╔══════════════════════════════════════════════════════════════╗
║                    🏢 ENTERPRISE CRM SYSTEM                  ║
║              Customer Relationship Management Portal         ║
║                     Secure Admin Access                     ║
╚══════════════════════════════════════════════════════════════╝
";

// Display banner in CLI mode for debugging
if (php_sapi_name() === 'cli') {
    echo $crm_banner . PHP_EOL;
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

// Handle the incoming request
$request = Request::capture();

// CRM Security: Check if accessing root and redirect to admin login
if ($request->getPathInfo() === '/' || $request->getPathInfo() === '') {
    // Log access attempt for security monitoring
    error_log("[CRM Security] Root access attempt from IP: " . $request->ip() . " at " . date('Y-m-d H:i:s'));

    // Create a custom response to redirect to admin login
    $response = new \Illuminate\Http\RedirectResponse('/admin/login');

    // Add security headers for CRM application
    $response->headers->set('X-Frame-Options', 'DENY');
    $response->headers->set('X-Content-Type-Options', 'nosniff');
    $response->headers->set('X-XSS-Protection', '1; mode=block');
    $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
    $response->headers->set('X-CRM-System', 'Enterprise-Portal');

    // Add cache control to prevent caching of redirect
    $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
    $response->headers->set('Pragma', 'no-cache');
    $response->headers->set('Expires', '0');

    $response->send();
    exit;
}

// Enhanced request handling for CRM routes
$app->singleton('crm.request.handler', function() use ($request) {
    // Custom CRM request processing
    $crm_routes = [
        '/admin',
        '/admin/login',
        '/admin/dashboard',
        '/admin/customers',
        '/admin/leads',
        '/admin/reports',
        '/admin/settings'
    ];

    $current_path = $request->getPathInfo();

    // Add custom headers for CRM admin routes
    if (str_starts_with($current_path, '/admin')) {
        header('X-CRM-Module: Admin-Panel');
        header('X-Robots-Tag: noindex, nofollow');
    }

    return $request;
});

// Initialize CRM session security
$app->booted(function() use ($request) {
    // Set secure session configuration for CRM
    if ($request->isSecure()) {
        ini_set('session.cookie_secure', 1);
    }
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_samesite', 'Strict');
    ini_set('session.name', 'CRM_SESSION');
});

// Handle the request with enhanced CRM security
try {
    $app->handleRequest($app->make('crm.request.handler'));
} catch (\Throwable $e) {
    // Custom CRM error handling
    error_log("[CRM Error] " . $e->getMessage() . " in " . $e->getFile() . " line " . $e->getLine());

    // In production, redirect to admin login on critical errors
    if (app()->environment('production')) {
        header('Location: /admin/login?error=system');
        exit;
    }

    throw $e;
}

// CRM Performance monitoring
if (defined('LARAVEL_START')) {
    $execution_time = microtime(true) - LARAVEL_START;
    if ($execution_time > 2.0) { // Log slow requests
        error_log("[CRM Performance] Slow request detected: {$request->getPathInfo()} took {$execution_time}s");
    }
}
