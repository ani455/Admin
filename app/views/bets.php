<?php
/**
 * Bets/Games Management View
 */

$page = (int)($_GET['p'] ?? 1);
$limit = 20;
$offset = ($page - 1) * $limit;
$status = $_GET['status'] ?? '';

$bets = $gameModel->getAllBets($limit, $offset, $status);
?>

<div class="page-header">
    <h1>Bets & Games</h1>
    <p class="text-muted">Monitor all betting activity</p>
</div>

<!-- Filter -->
<div class="card" style="margin-bottom: var(--spacing-lg);">
    <form method="GET" style="display: flex; gap: var(--spacing-md);">
        <input type="hidden" name="page" value="bets">
        <select name="status">
            <option value="">All Status</option>
            <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Active</option>
            <option value="settled" <?php echo $status === 'settled' ? 'selected' : ''; ?>>Settled</option>
            <option value="cancelled" <?php echo $status === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>
</div>

<!-- Bets Table -->
<div class="card">
    <div class="card-body">
        <?php if (count($bets) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Bet ID</th>
                        <th>User ID</th>
                        <th>Game</th>
                        <th>Amount</th>
                        <th>Prediction</th>
                        <th>Result</th>
                        <th>Payout</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bets as $bet): ?>
                    <tr>
                        <td style="font-weight: 600;">#<?php echo $bet['id']; ?></td>
                        <td><?php echo $bet['user_id']; ?></td>
                        <td><?php echo $bet['game_id']; ?></td>
                        <td><?php echo Formatter::currency($bet['amount']); ?></td>
                        <td><?php echo Security::sanitize($bet['prediction'] ?? ''); ?></td>
                        <td>
                            <?php if ($bet['result'] === 'win'): ?>
                                <span class="badge badge-success">Win</span>
                            <?php elseif ($bet['result'] === 'loss'): ?>
                                <span class="badge badge-danger">Loss</span>
                            <?php else: ?>
                                <span class="badge badge-info">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo Formatter::currency($bet['payout'] ?? 0); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $bet['status'] === 'active' ? 'warning' : 'success'; ?>">
                                <?php echo ucfirst($bet['status']); ?>
                            </span>
                        </td>
                        <td><?php echo Formatter::date($bet['created_at'], 'M d, Y H:i'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted" style="text-align: center; padding: var(--spacing-xl) 0;">No bets found</p>
        <?php endif; ?>
    </div>
</div>
