<?php

/**
 * Unified Database Configuration
 * Secure connection with prepared statements
 */

// Load environment file
function loadEnv($path) {
    if (!file_exists($path)) return [];
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $env = [];
    foreach ($lines as $line) {
        if (strpos($line, '=') && strpos($line, '#') !== 0) {
            [$key, $value] = explode('=', $line, 2);
            $env[trim($key)] = trim($value);
        }
    }
    return $env;
}

$env = loadEnv(__DIR__ . '/../../.env');
if (empty($env)) {
    $env = loadEnv(__DIR__ . '/../../.env.example');
}

// Set timezone
date_default_timezone_set($env['TIMEZONE'] ?? 'UTC');

// Database Configuration from environment
define('DB_HOST', $env['DB_HOST'] ?? 'localhost');
define('DB_PORT', $env['DB_PORT'] ?? 3306);
define('DB_USER', $env['DB_USER'] ?? 'root');
define('DB_PASSWORD', $env['DB_PASSWORD'] ?? '');
define('DB_NAME', $env['DB_NAME'] ?? 'admin_panel');
define('APP_ENV', $env['APP_ENV'] ?? 'production');

// Create connection
$conn = mysqli_connect(
    DB_HOST,
    DB_USER,
    DB_PASSWORD,
    DB_NAME,
    DB_PORT
);

// Check connection
if (!$conn) {
    error_log("Database connection failed: " . mysqli_connect_error());
    if (APP_ENV === 'development') {
        die('Database Error: ' . mysqli_connect_error());
    }
    die('Database connection error. Please contact support.');
}

// Set charset
mysqli_set_charset($conn, "utf8mb4");

/**
 * Execute prepared statement safely
 */
function executeQuery($conn, $sql, $types = "", $params = []) {
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        error_log("Query preparation failed: " . mysqli_error($conn));
        return false;
    }
    
    if (!empty($types) && !empty($params)) {
        if (!mysqli_stmt_bind_param($stmt, $types, ...$params)) {
            error_log("Parameter binding failed: " . mysqli_stmt_error($stmt));
            return false;
        }
    }
    
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Query execution failed: " . mysqli_stmt_error($stmt));
        return false;
    }
    
    return $stmt;
}

/**
 * Get single row
 */
function getRow($conn, $sql, $types = "", $params = []) {
    $stmt = executeQuery($conn, $sql, $types, $params);
    if (!$stmt) return null;
    
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    return $row;
}

/**
 * Get multiple rows
 */
function getRows($conn, $sql, $types = "", $params = []) {
    $stmt = executeQuery($conn, $sql, $types, $params);
    if (!$stmt) return [];
    
    $result = mysqli_stmt_get_result($stmt);
    $rows = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    
    mysqli_stmt_close($stmt);
    return $rows;
}

/**
 * Execute insert/update/delete
 */
function executeUpdate($conn, $sql, $types = "", $params = []) {
    $stmt = executeQuery($conn, $sql, $types, $params);
    if (!$stmt) return false;
    
    $affectedRows = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    
    return $affectedRows > 0 ? $affectedRows : false;
}

/**
 * Get last inserted ID
 */
function getLastInsertId($conn) {
    return mysqli_insert_id($conn);
}
?>
