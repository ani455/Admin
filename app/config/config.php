<?php

/**
 * Application Configuration
 */

// Load environment variables
$env = parse_ini_file(__DIR__ . '/../../.env');
if (!$env) {
    $env = parse_ini_file(__DIR__ . '/../../.env.example');
}

// App Info
define('APP_NAME', $env['APP_NAME'] ?? 'Admin Panel');
define('APP_URL', $env['APP_URL'] ?? 'http://localhost');
define('APP_ENV', $env['APP_ENV'] ?? 'development');

// Session
define('SESSION_NAME', $env['SESSION_NAME'] ?? 'admin_session');
define('SESSION_TIMEOUT', (int)($env['SESSION_TIMEOUT'] ?? 1800)); // 30 minutes

// Security
define('ENCRYPTION_KEY', $env['ENCRYPTION_KEY'] ?? 'change-this-key-in-production');
define('PASSWORD_HASH_ALGO', 'bcrypt');

// Pagination
define('ITEMS_PER_PAGE', 20);

// Colors (SaaS Design System)
define('COLOR_PRIMARY', '#3B82F6');
define('COLOR_SECONDARY', '#10B981');
define('COLOR_DANGER', '#EF4444');
define('COLOR_WARNING', '#F59E0B');
define('COLOR_INFO', '#06B6D4');
define('COLOR_BG_DARK', '#0F172A');
define('COLOR_SURFACE', '#1E293B');
define('COLOR_BORDER', '#334155');
define('COLOR_TEXT_PRIMARY', '#F1F5F9');
define('COLOR_TEXT_SECONDARY', '#CBD5E1');

// Date format
define('DATE_FORMAT', 'Y-m-d');
define('TIME_FORMAT', 'H:i:s');
define('DATETIME_FORMAT', 'Y-m-d H:i:s');

// File upload
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_UPLOAD_TYPES', ['jpg', 'jpeg', 'png', 'pdf']);

// Timezone
define('APP_TIMEZONE', 'UTC');
date_default_timezone_set(APP_TIMEZONE);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
    
    // Session timeout
    if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > SESSION_TIMEOUT) {
        session_destroy();
        header('Location: ' . APP_URL . '/index.php?login=expired');
        exit;
    }
    $_SESSION['last_activity'] = time();
}
