<?php
/**
 * Utility Functions
 * Common functions used across the admin panel
 */

/**
 * Format currency
 */
function formatCurrency($amount, $currency = 'INR') {
    return number_format($amount, 2, '.', ',') . ' ' . $currency;
}

/**
 * Format date
 */
function formatDate($date, $format = 'M d, Y H:i') {
    return date($format, strtotime($date));
}

/**
 * Sanitize input
 */
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect with message
 */
function redirect($url, $message = '', $type = 'info') {
    if (!empty($message)) {
        $_SESSION['message'] = $message;
        $_SESSION['message_type'] = $type;
    }
    header("Location: $url");
    exit;
}

/**
 * Get and clear flash message
 */
function getFlashMessage() {
    $message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
    $type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'info';
    
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
    
    return ['message' => $message, 'type' => $type];
}

/**
 * Generate random string
 */
function generateToken($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Convert status number to text
 */
function getStatusText($status) {
    $statuses = [
        '0' => 'Pending',
        '1' => 'Approved',
        '2' => 'Rejected',
        '3' => 'Processing'
    ];
    
    return $statuses[$status] ?? 'Unknown';
}

/**
 * Format number with commas
 */
function formatNumber($number) {
    return number_format($number, 0, '.', ',');
}
?>
