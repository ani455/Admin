<?php
/**
 * Withdrawals API
 * Handles withdrawal operations
 */

require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../core/Auth.php');
require_once(__DIR__ . '/../core/Security.php');
require_once(__DIR__ . '/../models/Withdrawal.php');

header('Content-Type: application/json');

Auth::requireLogin();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$withdrawalModel = new Withdrawal($conn);

$response = ['success' => false, 'message' => 'Unknown action'];

if ($method === 'GET') {
    if ($action === 'list') {
        $limit = intval($_GET['limit'] ?? 50);
        $offset = intval($_GET['offset'] ?? 0);
        $withdrawals = $withdrawalModel->getAll($limit, $offset);
        $response = ['success' => true, 'data' => $withdrawals];
    }
    else if ($action === 'pending') {
        $limit = intval($_GET['limit'] ?? 50);
        $offset = intval($_GET['offset'] ?? 0);
        $withdrawals = $withdrawalModel->getPending($limit, $offset);
        $response = ['success' => true, 'data' => $withdrawals];
    }
    else if ($action === 'get') {
        $withdrawalId = Security::sanitize($_GET['id'] ?? '');
        if (empty($withdrawalId)) {
            $response = ['success' => false, 'message' => 'Withdrawal ID required'];
        } else {
            $withdrawal = $withdrawalModel->getById($withdrawalId);
            $response = ['success' => true, 'data' => $withdrawal];
        }
    }
}
else if ($method === 'POST') {
    if (!Security::verifyToken($_POST['csrf_token'] ?? '')) {
        $response = ['success' => false, 'message' => 'Token validation failed'];
    }
    else if ($action === 'approve') {
        $withdrawalId = Security::sanitize($_POST['id'] ?? '');
        if (empty($withdrawalId)) {
            $response = ['success' => false, 'message' => 'Withdrawal ID required'];
        } else {
            $result = $withdrawalModel->approve($withdrawalId);
            $response = ['success' => $result, 'message' => $result ? 'Approved successfully' : 'Failed to approve'];
        }
    }
    else if ($action === 'reject') {
        $withdrawalId = Security::sanitize($_POST['id'] ?? '');
        if (empty($withdrawalId)) {
            $response = ['success' => false, 'message' => 'Withdrawal ID required'];
        } else {
            $result = $withdrawalModel->reject($withdrawalId);
            $response = ['success' => $result, 'message' => $result ? 'Rejected successfully' : 'Failed to reject'];
        }
    }
}

echo json_encode($response);
?>
