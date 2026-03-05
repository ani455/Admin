<?php
/**
 * Deposits API
 * Handles deposit operations
 */

require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../core/Auth.php');
require_once(__DIR__ . '/../core/Security.php');
require_once(__DIR__ . '/../models/Deposit.php');

header('Content-Type: application/json');

Auth::requireLogin();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$depositModel = new Deposit($conn);

$response = ['success' => false, 'message' => 'Unknown action'];

if ($method === 'GET') {
    if ($action === 'list') {
        $limit = intval($_GET['limit'] ?? 50);
        $offset = intval($_GET['offset'] ?? 0);
        $deposits = $depositModel->getAll($limit, $offset);
        $response = ['success' => true, 'data' => $deposits];
    } 
    else if ($action === 'pending') {
        $limit = intval($_GET['limit'] ?? 50);
        $offset = intval($_GET['offset'] ?? 0);
        $deposits = $depositModel->getPending($limit, $offset);
        $response = ['success' => true, 'data' => $deposits];
    }
    else if ($action === 'get') {
        $depositId = Security::sanitize($_GET['id'] ?? '');
        if (empty($depositId)) {
            $response = ['success' => false, 'message' => 'Deposit ID required'];
        } else {
            $deposit = $depositModel->getById($depositId);
            $response = ['success' => true, 'data' => $deposit];
        }
    }
}
else if ($method === 'POST') {
    if (!Security::verifyToken($_POST['csrf_token'] ?? '')) {
        $response = ['success' => false, 'message' => 'Token validation failed'];
    }
    else if ($action === 'approve') {
        $depositId = Security::sanitize($_POST['id'] ?? '');
        if (empty($depositId)) {
            $response = ['success' => false, 'message' => 'Deposit ID required'];
        } else {
            $result = $depositModel->approve($depositId);
            $response = ['success' => $result, 'message' => $result ? 'Approved successfully' : 'Failed to approve'];
        }
    }
    else if ($action === 'reject') {
        $depositId = Security::sanitize($_POST['id'] ?? '');
        if (empty($depositId)) {
            $response = ['success' => false, 'message' => 'Deposit ID required'];
        } else {
            $result = $depositModel->reject($depositId);
            $response = ['success' => $result, 'message' => $result ? 'Rejected successfully' : 'Failed to reject'];
        }
    }
}

echo json_encode($response);
?>
