<?php

/**
 * Helper Functions
 * Common utility functions used throughout the application
 */

/**
 * Redirect to URL
 */
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

/**
 * Get URL parameter safely
 */
function getParam($key, $default = null, $filter = FILTER_SANITIZE_SPECIAL_CHARS) {
    $value = $_GET[$key] ?? $default;
    return $filter ? filter_var($value, $filter) : $value;
}

/**
 * Get POST parameter safely
 */
function postParam($key, $default = null, $filter = FILTER_SANITIZE_SPECIAL_CHARS) {
    $value = $_POST[$key] ?? $default;
    return $filter ? filter_var($value, $filter) : $value;
}

/**
 * Get REQUEST parameter safely
 */
function requestParam($key, $default = null, $filter = FILTER_SANITIZE_SPECIAL_CHARS) {
    $value = $_REQUEST[$key] ?? $default;
    return $filter ? filter_var($value, $filter) : $value;
}

/**
 * Set flash message
 */
function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get flash message
 */
function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Check if request is AJAX
 */
function isAjax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
}

/**
 * Return JSON response
 */
function jsonResponse($success, $message = '', $data = []) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

/**
 * Get current URL
 */
function getCurrentUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

/**
 * Generate pagination
 */
function generatePagination($currentPage, $totalPages, $baseUrl) {
    $html = '<nav aria-label="Pagination"><ul class="flex gap-2">';
    
    if ($currentPage > 1) {
        $html .= '<li><a href="' . $baseUrl . '?page=1" class="px-3 py-2 border rounded">First</a></li>';
        $html .= '<li><a href="' . $baseUrl . '?page=' . ($currentPage - 1) . '" class="px-3 py-2 border rounded">Previous</a></li>';
    }
    
    for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++) {
        if ($i === $currentPage) {
            $html .= '<li><span class="px-3 py-2 bg-blue-500 text-white rounded">' . $i . '</span></li>';
        } else {
            $html .= '<li><a href="' . $baseUrl . '?page=' . $i . '" class="px-3 py-2 border rounded">' . $i . '</a></li>';
        }
    }
    
    if ($currentPage < $totalPages) {
        $html .= '<li><a href="' . $baseUrl . '?page=' . ($currentPage + 1) . '" class="px-3 py-2 border rounded">Next</a></li>';
        $html .= '<li><a href="' . $baseUrl . '?page=' . $totalPages . '" class="px-3 py-2 border rounded">Last</a></li>';
    }
    
    $html .= '</ul></nav>';
    return $html;
}

/**
 * Get all users
 */
function getAllUsers($conn, $limit = 20, $offset = 0) {
    $sql = "SELECT * FROM nirvahaka_shonu LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get user by ID
 */
function getUserById($conn, $userId) {
    $sql = "SELECT * FROM nirvahaka_shonu WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

/**
 * Count total users
 */
function countUsers($conn) {
    $result = $conn->query("SELECT COUNT(*) as total FROM nirvahaka_shonu");
    $row = $result->fetch_assoc();
    return $row['total'];
}

/**
 * Get recent bets
 */
function getRecentBets($conn, $limit = 10) {
    $sql = "SELECT * FROM bet_kacha ORDER BY id DESC LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get dashboard stats
 */
function getDashboardStats($conn) {
    $stats = [];
    
    // Total users
    $result = $conn->query("SELECT COUNT(*) as total FROM nirvahaka_shonu");
    $stats['total_users'] = $result->fetch_assoc()['total'];
    
    // Active users (logged in last 24 hours)
    $result = $conn->query("SELECT COUNT(*) as total FROM nirvahaka_shonu WHERE last_login > DATE_SUB(NOW(), INTERVAL 24 HOUR)");
    $stats['active_users'] = $result->fetch_assoc()['total'];
    
    // Today's revenue
    $result = $conn->query("SELECT SUM(amount) as total FROM transactions WHERE DATE(created_at) = CURDATE() AND type = 'bet'");
    $row = $result->fetch_assoc();
    $stats['today_revenue'] = $row['total'] ?? 0;
    
    // Pending withdrawals
    $result = $conn->query("SELECT COUNT(*) as total FROM withdraw_request WHERE status = 'pending'");
    $stats['pending_withdrawals'] = $result->fetch_assoc()['total'];
    
    return $stats;
}

/**
 * Log activity to audit log
 */
function logActivity($conn, $userId, $action, $details = '') {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $sql = "INSERT INTO audit_log (user_id, action, details, ip_address, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $userId, $action, $details, $ip);
    return $stmt->execute();
}
