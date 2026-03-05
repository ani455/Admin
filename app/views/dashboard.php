<?php
/**
 * Dashboard View
 * Main dashboard with KPI cards and statistics
 */

// Get dashboard data
$totalUsers = $userModel->count();
$pendingWithdrawals = $transactionModel->countPendingWithdrawals();
$totalRevenue = $gameModel->getTodaysRevenue();
$betStats = $gameModel->getBetStats();
?>

<div class="page-header">
    <h1>Dashboard</h1>
    <p class="text-muted">Welcome back to your admin panel</p>
</div>

<!-- KPI Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: var(--spacing-lg); margin-bottom: var(--spacing-xl);">
    <!-- Total Users Card -->
    <div class="kpi-card">
        <div class="kpi-label">Total Users</div>
        <div class="kpi-value"><?php echo number_format($totalUsers); ?></div>
        <div class="kpi-change positive">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="vertical-align: middle; margin-right: 4px;">
                <path d="M7 14s1.5-2 5-2 5 2 5 2M9 9h.01M15 9h.01M23 12a11 11 0 1 1-22 0 11 11 0 0 1 22 0Z"/>
            </svg>
            Active users
        </div>
    </div>
    
    <!-- Today's Revenue Card -->
    <div class="kpi-card">
        <div class="kpi-label">Today's Revenue</div>
        <div class="kpi-value"><?php echo Formatter::currency($totalRevenue); ?></div>
        <div class="kpi-change positive">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="vertical-align: middle; margin-right: 4px;">
                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/>
            </svg>
            From bets
        </div>
    </div>
    
    <!-- Pending Withdrawals Card -->
    <div class="kpi-card">
        <div class="kpi-label">Pending Withdrawals</div>
        <div class="kpi-value"><?php echo $pendingWithdrawals; ?></div>
        <div class="kpi-change negative">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="vertical-align: middle; margin-right: 4px;">
                <circle cx="12" cy="12" r="10"/>
            </svg>
            Awaiting approval
        </div>
    </div>
    
    <!-- Active Bets Card -->
    <div class="kpi-card">
        <div class="kpi-label">Active Bets</div>
        <div class="kpi-value"><?php echo number_format($gameModel->countActiveBets()); ?></div>
        <div class="kpi-change positive">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="vertical-align: middle; margin-right: 4px;">
                <path d="M9 11l3 3L22 4m0 0l-3-3m3 3l-6 6m6-6h7V7m0 0v7"/>
            </svg>
            In progress
        </div>
    </div>
</div>

<!-- Recent Activity Section -->
<div class="card">
    <div class="card-header">
        <h3 style="margin: 0;">Recent Bets</h3>
        <a href="dashboard.php?page=bets" class="btn btn-secondary btn-sm">View All</a>
    </div>
    <div class="card-body">
        <?php 
        $recentBets = $gameModel->getRecentBets(5);
        if (count($recentBets) > 0):
        ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Bet ID</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Result</th>
                        <th>Payout</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentBets as $bet): ?>
                    <tr>
                        <td style="font-weight: 600;">#<?php echo $bet['id']; ?></td>
                        <td><?php echo $bet['user_id']; ?></td>
                        <td><?php echo Formatter::currency($bet['amount']); ?></td>
                        <td>
                            <?php if ($bet['status'] === 'active'): ?>
                                <span class="badge badge-info">Active</span>
                            <?php elseif ($bet['result'] === 'win'): ?>
                                <span class="badge badge-success">Win</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Loss</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo Formatter::currency($bet['payout'] ?? 0); ?></td>
                        <td><?php echo Formatter::date($bet['created_at'], 'M d, Y H:i'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted" style="text-align: center; padding: var(--spacing-xl) 0;">No recent bets found</p>
        <?php endif; ?>
    </div>
</div>

<style>
    .kpi-card {
        background: linear-gradient(135deg, var(--bg-surface) 0%, rgba(59, 130, 246, 0.05) 100%);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: var(--spacing-xl);
        text-align: center;
        transition: all 0.2s ease;
    }
    
    .kpi-card:hover {
        border-color: var(--primary);
        box-shadow: var(--shadow-md);
    }
    
    .kpi-value {
        font-size: 32px;
        font-weight: 700;
        color: var(--text-primary);
        margin: var(--spacing-md) 0;
    }
    
    .kpi-label {
        font-size: 12px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .kpi-change {
        font-size: 12px;
        margin-top: var(--spacing-sm);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .kpi-change.positive {
        color: var(--secondary);
    }
    
    .kpi-change.negative {
        color: var(--danger);
    }
</style>
