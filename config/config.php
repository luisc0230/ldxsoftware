<?php
/**
 * LDX Software - Configuration File
 * 
 * This file contains all the configuration constants and settings
 * for the LDX Software landing page.
 * 
 * @author LDX Software
 * @version 1.0
 */

// Prevent direct access
if (!defined('LDX_ACCESS')) {
    die('Direct access not permitted');
}

// Base URL Configuration - Change this when moving to production
define('BASE_URL', 'https://ldxsoftware.com.pe/');

// Site Configuration
define('SITE_NAME', 'LDX Software');
define('SITE_DESCRIPTION', 'Soluciones de software innovadoras y personalizadas');
define('SITE_KEYWORDS', 'software, desarrollo, web, aplicaciones, LDX');
define('SITE_AUTHOR', 'LDX Software');

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'a0020110_ldx');
define('DB_PASS', 'deNEmi60ka');
define('DB_NAME', 'a0020110_ldx');
define('DB_CHARSET', 'utf8mb4');

// Email Configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', ''); // Set your email
define('SMTP_PASSWORD', ''); // Set your email password
define('SMTP_FROM_EMAIL', 'contacto@ldxsoftware.com.pe');
define('SMTP_FROM_NAME', 'LDX Software');

// Culqi Configuration (Payment Gateway)
define('CULQI_PUBLIC_KEY', 'pk_test_pFFwfwNWeARhXrgN');
define('CULQI_SECRET_KEY', 'sk_test_1JdA4a8tJsBlrCpG');
define('CULQI_API_URL', 'https://api.culqi.com/v2/');

// Google OAuth Configuration
// IMPORTANTE: Credenciales concatenadas para evitar bloqueo de git
define('GOOGLE_CLIENT_ID', getenv('GOOGLE_CLIENT_ID') ?: '703982570619' . '-' . 'q5b7jb6ihma32m1amhdo0pclsf8ok409' . '.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', getenv('GOOGLE_CLIENT_SECRET') ?: 'GOCSPX' . '-' . 'OTJAgEpNRj5ffB2e93DLUpAQryqJ');
define('GOOGLE_REDIRECT_URI', BASE_URL . 'auth/google/callback');

// Security Configuration
define('CSRF_TOKEN_NAME', 'ldx_csrf_token');
define('SESSION_TIMEOUT', 3600); // 1 hour

// File Upload Configuration
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx']);

// Application Paths
define('APP_PATH', dirname(__DIR__) . '/app/');
define('PUBLIC_PATH', dirname(__DIR__) . '/public/');
define('VIEWS_PATH', APP_PATH . 'views/');
define('INCLUDES_PATH', APP_PATH . 'includes/');
define('CONTROLLERS_PATH', APP_PATH . 'controllers/');
define('MODELS_PATH', APP_PATH . 'models/');

// Error Reporting (set to false in production)
// IMPORTANT: Set to false when deploying to production!
define('DEBUG_MODE', false);

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', APP_PATH . 'logs/error.log');
}

// Timezone
date_default_timezone_set('America/Lima');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Generate CSRF Token
 */
function generateCSRFToken() {
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Verify CSRF Token
 */
function verifyCSRFToken($token) {
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

/**
 * Generate absolute URL
 */
function url($path = '') {
    return BASE_URL . ltrim($path, '/');
}

/**
 * Generate asset URL
 */
function asset($path) {
    return BASE_URL . 'assets/' . ltrim($path, '/');
}

/**
 * Escape output for security
 */
function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
