<?php
/**
 * Authentication System
 * Handles admin login, session management, and security
 */

require_once(__DIR__ . '/../config/database.php');

class Auth {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    /**
     * Login admin user
     */
    public function login($username, $password) {
        // Validate input
        if (empty($username) || empty($password)) {
            return ['success' => false, 'message' => 'Username and password required'];
        }
        
        // Get admin from database
        $sql = "SELECT * FROM `nirvahaka_shonu` WHERE `nirvahaka_hesaru` = ? AND `sthiti` = '1'";
        $admin = getRow($this->conn, $sql, "s", [$username]);
        
        // Check if admin exists
        if (!$admin) {
            error_log("Login attempt for non-existent user: " . $username);
            return ['success' => false, 'message' => 'Invalid credentials'];
        }
        
        // Verify password
        if (!password_verify($password, $admin['guptapada'])) {
            // Fallback for old MD5 hashes during migration
            if (md5($password) !== $admin['guptapada']) {
                error_log("Failed login attempt for: " . $username);
                return ['success' => false, 'message' => 'Invalid credentials'];
            }
        }
        
        // Set session
        $_SESSION['unohs'] = $admin['unohs'];
        $_SESSION['nirvahaka_hesaru'] = $admin['nirvahaka_hesaru'];
        $_SESSION['login_time'] = time();
        
        return ['success' => true, 'message' => 'Login successful'];
    }
    
    /**
     * Check if user is logged in
     */
    public static function isLoggedIn() {
        return isset($_SESSION['unohs']) && isset($_SESSION['nirvahaka_hesaru']);
    }
    
    /**
     * Get current admin info
     */
    public static function getCurrentAdmin($conn) {
        if (!self::isLoggedIn()) {
            return null;
        }
        
        $sql = "SELECT * FROM `nirvahaka_shonu` WHERE `unohs` = ? AND `sthiti` = '1'";
        return getRow($conn, $sql, "s", [$_SESSION['unohs']]);
    }
    
    /**
     * Logout user
     */
    public static function logout() {
        session_destroy();
        return true;
    }
    
    /**
     * Require login
     */
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header("Location: /app/pages/login.php");
            exit;
        }
    }
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
