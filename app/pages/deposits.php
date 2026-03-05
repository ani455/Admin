<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../core/Auth.php');
require_once(__DIR__ . '/../models/Deposit.php');

Auth::requireLogin();
$admin = Auth::getCurrentAdmin($conn);

$depositModel = new Deposit($conn);
$filter = $_GET['filter'] ?? 'all';
$page = intval($_GET['page'] ?? 1);
$limit = 20;
$offset = ($page - 1) * $limit;

if ($filter === 'pending') {
    $deposits = $depositModel->getPending($limit, $offset);
} else if ($filter === 'approved') {
    $deposits = $depositModel->getApproved($limit, $offset);
} else {
    $deposits = $depositModel->getAll($limit, $offset);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deposits - Admin Panel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #0f172a; color: #e2e8f0; }
        .admin-layout { display: flex; height: 100vh; }
        .admin-main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        .admin-content { flex: 1; overflow-y: auto; padding: 24px; }
        .page-header { margin-bottom: 24px; }
        .page-header h1 { font-size: 28px; font-weight: 700; margin-bottom: 8px; color: #f1f5f9; }
        .filters { display: flex; gap: 12px; margin-bottom: 20px; }
        .filter-btn { padding: 8px 16px; border: 1px solid rgba(226, 232, 240, 0.2); background: transparent; color: #cbd5e1; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 500; transition: all 0.3s; }
        .filter-btn.active { background: #3b82f6; color: white; border-color: #3b82f6; }
        .filter-btn:hover { border-color: #3b82f6; }
        .card { background: rgba(30, 41, 59, 0.8); border: 1px solid rgba(226, 232, 240, 0.1); border-radius: 12px; padding: 24px; }
        .table-wrapper { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        table thead th { padding: 12px; text-align: left; font-weight: 600; color: #94a3b8; border-bottom: 1px solid rgba(226, 232, 240, 0.1); text-transform: uppercase; font-size: 11px; }
        table tbody td { padding: 12px; border-bottom: 1px solid rgba(226, 232, 240, 0.05); }
        table tbody tr:hover { background: rgba(226, 232, 240, 0.03); }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 4px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
        .status-approved { background: rgba(34, 197, 94, 0.1); color: #86efac; }
        .status-pending { background: rgba(168, 85, 247, 0.1); color: #d8b4fe; }
        .status-rejected { background: rgba(239, 68, 68, 0.1); color: #fca5a5; }
        .empty-state { text-align: center; padding: 48px; color: #64748b; }
        .action-btn { padding: 6px 12px; border: 1px solid rgba(226, 232, 240, 0.2); background: transparent; color: #3b82f6; border-radius: 4px; cursor: pointer; font-size: 12px; margin-right: 4px; }
        .action-btn:hover { background: rgba(59, 130, 246, 0.1); }
    </style>
</head>
<body>
    <div class="admin-layout">
        <?php require_once(__DIR__ . '/../layouts/sidebar.php'); ?>
        <div class="admin-main">
            <?php require_once(__DIR__ . '/../layouts/header.php'); ?>
            <div class="admin-content">
                <div class="page-header">
                    <h1>Deposits Management</h1>
                </div>
                
                <div class="filters">
                    <a href="?filter=all" class="filter-btn <?php echo $filter === 'all' || $filter === '' ? 'active' : ''; ?>">All</a>
                    <a href="?filter=pending" class="filter-btn <?php echo $filter === 'pending' ? 'active' : ''; ?>">Pending</a>
                    <a href="?filter=approved" class="filter-btn <?php echo $filter === 'approved' ? 'active' : ''; ?>">Approved</a>
                </div>
                
                <div class="card">
                    <div class="table-wrapper">
                        <?php if (count($deposits) > 0): ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Deposit ID</th>
                                        <th>User ID</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($deposits as $deposit): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($deposit['abramu_deposita_id'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($deposit['byabaharkarta_id'] ?? 'N/A'); ?></td>
                                        <td>₹<?php echo number_format($deposit['ruchika'] ?? 0, 0); ?></td>
                                        <td>
                                            <?php 
                                            $status = $deposit['sthiti'] ?? '0';
                                            $badgeClass = $status == '1' ? 'status-approved' : ($status == '2' ? 'status-rejected' : 'status-pending');
                                            $statusText = $status == '1' ? 'Approved' : ($status == '2' ? 'Rejected' : 'Pending');
                                            ?>
                                            <span class="status-badge <?php echo $badgeClass; ?>"><?php echo $statusText; ?></span>
                                        </td>
                                        <td><?php echo htmlspecialchars($deposit['sthe_date'] ?? 'N/A'); ?></td>
                                        <td>
                                            <?php if ($status == '0'): ?>
                                                <button class="action-btn" onclick="approveDeposit('<?php echo htmlspecialchars($deposit['abramu_deposita_id']); ?>')">Approve</button>
                                                <button class="action-btn" style="color: #ef4444;" onclick="rejectDeposit('<?php echo htmlspecialchars($deposit['abramu_deposita_id']); ?>')">Reject</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="empty-state">
                                <p>No deposits found</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <script>
                function approveDeposit(id) {
                    if (confirm('Approve this deposit?')) {
                        console.log('[v0] Approving deposit:', id);
                        // API call would go here
                    }
                }
                
                function rejectDeposit(id) {
                    if (confirm('Reject this deposit?')) {
                        console.log('[v0] Rejecting deposit:', id);
                        // API call would go here
                    }
                }
            </script>
        </div>
    </div>
</body>
</html>
