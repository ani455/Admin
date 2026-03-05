<?php

/**
 * Authentication Model Class
 * Handles user authentication, sessions, and permissions
 */

class Auth {
    private $db;
    private $userId;
    private $userRole;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
        $this->checkSession();
    }

    /**
     * Check if user is logged in
     */
    public function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Get current user ID
     */
    public function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Get current user
     */
    public function getUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }

        $stmt = $this->db->prepare("SELECT * FROM nirvahaka_shonu WHERE ID = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        return $user;
    }

    /**
     * Login user
     */
    public function login($email, $password) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM nirvahaka_shonu WHERE email_id = ? LIMIT 1");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();

            if (!$user) {
                return ['success' => false, 'message' => 'Invalid credentials'];
            }

            // Verify password
            if (!password_verify($password, $user['password'])) {
                return ['success' => false, 'message' => 'Invalid credentials'];
            }

            // Set session
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['user_email'] = $user['email_id'];
            $_SESSION['user_name'] = $user['full_name'] ?? 'Admin';
            $_SESSION['user_role'] = $user['role'] ?? 'admin';
            $_SESSION['last_login'] = date('Y-m-d H:i:s');

            return ['success' => true, 'message' => 'Login successful'];

        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Login error: ' . $e->getMessage()];
        }
    }

    /**
     * Logout user
     */
    public function logout() {
        session_destroy();
        return true;
    }

    /**
     * Check session validity
     */
    private function checkSession() {
        if (!$this->isLoggedIn()) {
            return;
        }

        // Verify user still exists and is active
        $stmt = $this->db->prepare("SELECT status FROM nirvahaka_shonu WHERE ID = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if (!$user || $user['status'] !== 'active') {
            $this->logout();
        }
    }

    /**
     * Check if user has permission
     */
    public function hasPermission($permission) {
        if (!$this->isLoggedIn()) {
            return false;
        }

        $userRole = $_SESSION['user_role'] ?? 'user';

        // Admin has all permissions
        if ($userRole === 'admin') {
            return true;
        }

        // Check specific permissions based on role
        $permissions = [
            'admin' => ['view_all', 'edit_all', 'delete_users', 'manage_payments'],
            'moderator' => ['view_all', 'edit_users'],
            'viewer' => ['view_all']
        ];

        $rolePerms = $permissions[$userRole] ?? [];
        return in_array($permission, $rolePerms);
    }

    /**
     * Require login
     */
    public static function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . APP_URL . '/index.php');
            exit;
        }
    }

    /**
     * Require permission
     */
    public static function requirePermission($permission) {
        self::requireLogin();
        $auth = new self($GLOBALS['conn']);
        if (!$auth->hasPermission($permission)) {
            header('HTTP/1.0 403 Forbidden');
            exit('Access Denied');
        }
    }
}
