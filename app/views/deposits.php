<?php
/**
 * Deposits Management View
 */

$page = (int)($_GET['p'] ?? 1);
$limit = 20;
$offset = ($page - 1) * $limit;
$status = $_GET['status'] ?? '';

$deposits = $transactionModel->getDeposits($limit, $offset, $status);
?>

<div class="page-header">
    <h1>Deposits Management</h1>
    <p class="text-muted">Manage user deposits and payments</p>
</div>

<!-- Filter -->
<div class="card" style="margin-bottom: var(--spacing-lg);">
    <form method="GET" style="display: flex; gap: var(--spacing-md);">
        <input type="hidden" name="page" value="deposits">
        <select name="status">
            <option value="">All Status</option>
            <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="approved" <?php echo $status === 'approved' ? 'selected' : ''; ?>>Approved</option>
            <option value="rejected" <?php echo $status === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>
</div>

<!-- Deposits Table -->
<div class="card">
    <div class="card-body">
        <?php if (count($deposits) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($deposits as $d): ?>
                    <tr>
                        <td style="font-weight: 600;">#<?php echo $d['id']; ?></td>
                        <td><?php echo $d['user_id']; ?></td>
                        <td><?php echo Formatter::currency($d['amount']); ?></td>
                        <td><?php echo Security::sanitize($d['payment_method'] ?? ''); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $d['status'] === 'pending' ? 'warning' : ($d['status'] === 'approved' ? 'success' : 'danger'); ?>">
                                <?php echo ucfirst($d['status']); ?>
                            </span>
                        </td>
                        <td><?php echo Formatter::date($d['created_at'], 'M d, Y H:i'); ?></td>
                        <td>
                            <?php if ($d['status'] === 'pending'): ?>
                                <button class="btn btn-secondary btn-sm" onclick="approveDeposit(<?php echo $d['id']; ?>)">Approve</button>
                                <button class="btn btn-danger btn-sm" onclick="rejectDeposit(<?php echo $d['id']; ?>)">Reject</button>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted" style="text-align: center; padding: var(--spacing-xl) 0;">No deposits found</p>
        <?php endif; ?>
    </div>
</div>

<script>
function approveDeposit(id) {
    if (confirm('Approve this deposit?')) {
        alert('Deposit ' + id + ' approved');
    }
}

function rejectDeposit(id) {
    if (confirm('Reject this deposit?')) {
        alert('Deposit ' + id + ' rejected');
    }
}
</script>
