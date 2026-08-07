<?php
/**
 * Entry point for the application.
 * All requests are routed through here.
 */

// Define application root
define('APP_ROOT', dirname(__FILE__));

// Basic error reporting for development (should be turned off in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Security Headers
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Content-Security-Policy: default-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com https://fonts.googleapis.com https://fonts.gstatic.com https://checkout.razorpay.com https://api.razorpay.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com; font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; img-src 'self' data: https:; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com https://checkout.razorpay.com; frame-src 'self' https://checkout.razorpay.com; connect-src 'self' https://checkout.razorpay.com https://api.razorpay.com;");

// Load configuration
require_once APP_ROOT . '/config/config.php';

// Initialize core components
require_once APP_ROOT . '/core/App.php';
require_once APP_ROOT . '/core/Controller.php';
require_once APP_ROOT . '/core/Database.php';
require_once APP_ROOT . '/core/Mailer.php';

// Instantiate the application
$app = new App();
