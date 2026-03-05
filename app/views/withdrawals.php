<?php
/**
 * Withdrawals Management View
 */

$page = (int)($_GET['p'] ?? 1);
$limit = 20;
$offset = ($page - 1) * $limit;
$status = $_GET['status'] ?? '';

$withdrawals = $transactionModel->getWithdrawals($limit, $offset, $status);
?>

<div class="page-header">
    <h1>Withdrawals Management</h1>
    <p class="text-muted">Manage user withdrawal requests</p>
</div>

<!-- Filter -->
<div class="card" style="margin-bottom: var(--spacing-lg);">
    <form method="GET" style="display: flex; gap: var(--spacing-md);">
        <input type="hidden" name="page" value="withdrawals">
        <select name="status">
            <option value="">All Status</option>
            <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="approved" <?php echo $status === 'approved' ? 'selected' : ''; ?>>Approved</option>
            <option value="rejected" <?php echo $status === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>
</div>

<!-- Withdrawals Table -->
<div class="card">
    <div class="card-body">
        <?php if (count($withdrawals) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Amount</th>
                        <th>Bank</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($withdrawals as $w): ?>
                    <tr>
                        <td style="font-weight: 600;">#<?php echo $w['id']; ?></td>
                        <td><?php echo $w['user_id']; ?></td>
                        <td><?php echo Formatter::currency($w['amount']); ?></td>
                        <td><?php echo Security::sanitize($w['bank_name'] ?? ''); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $w['status'] === 'pending' ? 'warning' : ($w['status'] === 'approved' ? 'success' : 'danger'); ?>">
                                <?php echo ucfirst($w['status']); ?>
                            </span>
                        </td>
                        <td><?php echo Formatter::date($w['created_at'], 'M d, Y H:i'); ?></td>
                        <td>
                            <?php if ($w['status'] === 'pending'): ?>
                                <button class="btn btn-secondary btn-sm" onclick="approveWithdrawal(<?php echo $w['id']; ?>)">Approve</button>
                                <button class="btn btn-danger btn-sm" onclick="rejectWithdrawal(<?php echo $w['id']; ?>)">Reject</button>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted" style="text-align: center; padding: var(--spacing-xl) 0;">No withdrawals found</p>
        <?php endif; ?>
    </div>
</div>

<script>
function approveWithdrawal(id) {
    if (confirm('Approve this withdrawal?')) {
        // API call would go here
        alert('Withdrawal ' + id + ' approved');
    }
}

function rejectWithdrawal(id) {
    if (confirm('Reject this withdrawal?')) {
        // API call would go here
        alert('Withdrawal ' + id + ' rejected');
    }
}
</script>
