<?php
/**
 * Dashboard API
 * Handles dashboard data
 */

require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../core/Auth.php');
require_once(__DIR__ . '/../models/Dashboard.php');

header('Content-Type: application/json');

Auth::requireLogin();

$action = $_GET['action'] ?? '';
$dashboardModel = new Dashboard($conn);

$response = ['success' => false, 'message' => 'Unknown action'];

if ($action === 'overview') {
    $overview = $dashboardModel->getOverview();
    $response = ['success' => true, 'data' => $overview];
}
else if ($action === 'today-stats') {
    $stats = $dashboardModel->getTodayStats();
    $response = ['success' => true, 'data' => $stats];
}
else if ($action === 'recent-deposits') {
    $limit = intval($_GET['limit'] ?? 5);
    $deposits = $dashboardModel->getRecentDeposits($limit);
    $response = ['success' => true, 'data' => $deposits];
}
else if ($action === 'recent-withdrawals') {
    $limit = intval($_GET['limit'] ?? 5);
    $withdrawals = $dashboardModel->getRecentWithdrawals($limit);
    $response = ['success' => true, 'data' => $withdrawals];
}

echo json_encode($response);
?>
