<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../core/Auth.php');
require_once(__DIR__ . '/../models/Dashboard.php');

Auth::requireLogin();
$admin = Auth::getCurrentAdmin($conn);

// Get dashboard data
$dashboardModel = new Dashboard($conn);
$overview = $dashboardModel->getOverview();
$recentDeposits = $dashboardModel->getRecentDeposits(3);
$recentWithdrawals = $dashboardModel->getRecentWithdrawals(3);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Panel</title>
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
            margin-bottom: 32px;
        }
        
        .page-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #f1f5f9;
        }
        
        .page-header p {
            font-size: 14px;
            color: #94a3b8;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }
        
        .stat-card {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(226, 232, 240, 0.1);
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            border-color: rgba(226, 232, 240, 0.2);
            background: rgba(30, 41, 59, 0.95);
        }
        
        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }
        
        .stat-title {
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
        }
        
        .stat-icon {
            font-size: 24px;
            opacity: 0.8;
        }
        
        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 8px;
        }
        
        .stat-change {
            font-size: 12px;
            color: #22c55e;
        }
        
        .stat-change.negative {
            color: #ef4444;
        }
        
        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 24px;
        }
        
        .card {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(226, 232, 240, 0.1);
            border-radius: 12px;
            padding: 24px;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(226, 232, 240, 0.1);
        }
        
        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: #f1f5f9;
        }
        
        .card-action {
            font-size: 12px;
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
        }
        
        .recent-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        
        .recent-table thead th {
            padding: 12px 0;
            text-align: left;
            font-weight: 600;
            color: #94a3b8;
            border-bottom: 1px solid rgba(226, 232, 240, 0.1);
        }
        
        .recent-table tbody td {
            padding: 12px 0;
            border-bottom: 1px solid rgba(226, 232, 240, 0.05);
        }
        
        .recent-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-approved {
            background: rgba(34, 197, 94, 0.1);
            color: #86efac;
        }
        
        .status-pending {
            background: rgba(168, 85, 247, 0.1);
            color: #d8b4fe;
        }
        
        .status-rejected {
            background: rgba(239, 68, 68, 0.1);
            color: #fca5a5;
        }
        
        @media (max-width: 768px) {
            .admin-layout {
                flex-direction: column;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .content-grid {
                grid-template-columns: 1fr;
            }
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
                    <h1>Dashboard</h1>
                    <p>Welcome back, <?php echo htmlspecialchars($admin['nirvahaka_hesaru']); ?>! Here's your platform overview.</p>
                </div>
                
                <!-- Stats Grid -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">Total Users</div>
                            <div class="stat-icon">👥</div>
                        </div>
                        <div class="stat-value"><?php echo number_format($overview['total_users']); ?></div>
                        <div class="stat-change"><?php echo $overview['active_users']; ?> active users</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">Total Deposits</div>
                            <div class="stat-icon">💰</div>
                        </div>
                        <div class="stat-value">₹<?php echo number_format($overview['total_deposits'], 0); ?></div>
                        <div class="stat-change">Approved deposits</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">Pending Withdrawals</div>
                            <div class="stat-icon">💳</div>
                        </div>
                        <div class="stat-value">₹<?php echo number_format($overview['pending_withdrawals'], 0); ?></div>
                        <div class="stat-change">Awaiting approval</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">Active Bets</div>
                            <div class="stat-icon">🎲</div>
                        </div>
                        <div class="stat-value"><?php echo number_format($overview['active_bets']); ?></div>
                        <div class="stat-change">₹<?php echo number_format($overview['total_bets_amount'], 0); ?> wagered</div>
                    </div>
                </div>
                
                <!-- Content Grid -->
                <div class="content-grid">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Recent Deposits</div>
                            <a href="deposits.php" class="card-action">View All →</a>
                        </div>
                        
                        <table class="recent-table">
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentDeposits as $deposit): ?>
                                <tr>
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
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Recent Withdrawals</div>
                            <a href="withdrawals.php" class="card-action">View All →</a>
                        </div>
                        
                        <table class="recent-table">
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentWithdrawals as $withdrawal): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($withdrawal['byabaharkarta_id'] ?? 'N/A'); ?></td>
                                    <td>₹<?php echo number_format($withdrawal['ruchika'] ?? 0, 0); ?></td>
                                    <td>
                                        <?php 
                                        $status = $withdrawal['sthiti'] ?? '0';
                                        $badgeClass = $status == '1' ? 'status-approved' : ($status == '2' ? 'status-rejected' : 'status-pending');
                                        $statusText = $status == '1' ? 'Approved' : ($status == '2' ? 'Rejected' : 'Pending');
                                        ?>
                                        <span class="status-badge <?php echo $badgeClass; ?>"><?php echo $statusText; ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
