<?php
/**
 * Users API
 * Handles user operations
 */

require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../core/Auth.php');
require_once(__DIR__ . '/../core/Security.php');
require_once(__DIR__ . '/../models/User.php');

header('Content-Type: application/json');

Auth::requireLogin();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$userModel = new User($conn);

$response = ['success' => false, 'message' => 'Unknown action'];

if ($method === 'GET') {
    if ($action === 'list') {
        $limit = intval($_GET['limit'] ?? 50);
        $offset = intval($_GET['offset'] ?? 0);
        $users = $userModel->getAll($limit, $offset);
        $response = ['success' => true, 'data' => $users];
    }
    else if ($action === 'search') {
        $query = Security::sanitize($_GET['q'] ?? '');
        if (empty($query)) {
            $response = ['success' => false, 'message' => 'Search query required'];
        } else {
            $users = $userModel->search($query);
            $response = ['success' => true, 'data' => $users];
        }
    }
    else if ($action === 'get') {
        $userId = Security::sanitize($_GET['id'] ?? '');
        if (empty($userId)) {
            $response = ['success' => false, 'message' => 'User ID required'];
        } else {
            $user = $userModel->getById($userId);
            $response = ['success' => true, 'data' => $user];
        }
    }
    else if ($action === 'count') {
        $count = $userModel->getCount();
        $response = ['success' => true, 'count' => $count];
    }
}

echo json_encode($response);
?>
