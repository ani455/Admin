<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../core/Auth.php');
require_once(__DIR__ . '/../core/Functions.php');

Auth::requireLogin();
$admin = Auth::getCurrentAdmin($conn);

// Get users data
$sql = "SELECT * FROM `byabaharkarta` LIMIT 50";
$users = getRows($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - Admin Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #0f172a;
            color: #e2e8f0;
        }
        
        .admin-layout {
            display: flex;
            height: 100vh;
        }
        
        .admin-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .admin-content {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
        }
        
        .page-header {
            margin-bottom: 24px;
        }
        
        .page-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #f1f5f9;
        }
        
        .card {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(226, 232, 240, 0.1);
            border-radius: 12px;
            padding: 24px;
        }
        
        .table-wrapper {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        
        table thead th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #94a3b8;
            border-bottom: 1px solid rgba(226, 232, 240, 0.1);
            text-transform: uppercase;
            font-size: 11px;
        }
        
        table tbody td {
            padding: 12px;
            border-bottom: 1px solid rgba(226, 232, 240, 0.05);
        }
        
        table tbody tr:hover {
            background: rgba(226, 232, 240, 0.03);
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-active {
            background: rgba(34, 197, 94, 0.1);
            color: #86efac;
        }
        
        .status-inactive {
            background: rgba(100, 116, 139, 0.1);
            color: #cbd5e1;
        }
        
        .empty-state {
            text-align: center;
            padding: 48px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <?php require_once(__DIR__ . '/../layouts/sidebar.php'); ?>
        
        <div class="admin-main">
            <?php require_once(__DIR__ . '/../layouts/header.php'); ?>
            
            <div class="admin-content">
                <div class="page-header">
                    <h1>Users Management</h1>
                </div>
                
                <div class="card">
                    <div class="table-wrapper">
                        <?php if (count($users) > 0): ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>User ID</th>
                                        <th>Username</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Joined Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['byabaharkarta_id'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($user['byabaharkarta_hesaru'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($user['mobilnumber'] ?? 'N/A'); ?></td>
                                        <td>
                                            <span class="status-badge status-active">Active</span>
                                        </td>
                                        <td><?php echo htmlspecialchars($user['sthe_date'] ?? 'N/A'); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="empty-state">
                                <p>No users found</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
