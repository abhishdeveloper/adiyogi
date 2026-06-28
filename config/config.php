<?php
/**
 * Database & App Configuration
 */

// App Settings
define('APP_NAME', 'MedClinicPro');
define('APP_URL', 'http://localhost:8000'); // Change this in production

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');      // Change for production shared hosting
define('DB_PASS', '');          // Change for production shared hosting
define('DB_NAME', 'medclinicpro'); // Change for production shared hosting

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
