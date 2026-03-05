<?php
/**
 * Users Management View
 * List and manage all users
 */

// Get pagination
$page = (int)($_GET['p'] ?? 1);
$limit = 20;
$offset = ($page - 1) * $limit;
$search = $_GET['search'] ?? '';

// Get users
$users = $userModel->getAll($limit, $offset, $search);
$totalUsers = $userModel->count();
$totalPages = ceil($totalUsers / $limit);

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = postParam('action');
    $userId = (int)postParam('user_id');
    
    if ($action === 'update_status') {
        $newStatus = postParam('status');
        $userModel->update($userId, ['status' => $newStatus]);
        setFlash('success', 'User status updated successfully');
        header('Location: dashboard.php?page=users');
        exit;
    } elseif ($action === 'delete') {
        $userModel->delete($userId);
        setFlash('success', 'User deleted successfully');
        header('Location: dashboard.php?page=users');
        exit;
    }
}
?>

<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Users Management</h1>
            <p class="text-muted">Manage all user accounts</p>
        </div>
        <button class="btn btn-primary" onclick="openAddUserModal()">Add User</button>
    </div>
</div>

<!-- Search -->
<div class="card" style="margin-bottom: var(--spacing-lg);">
    <form method="GET" style="display: flex; gap: var(--spacing-md);">
        <input type="hidden" name="page" value="users">
        <input 
            type="text" 
            name="search" 
            placeholder="Search by name, email, or phone..." 
            value="<?php echo Security::sanitize($search); ?>"
            style="flex: 1;"
        >
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-body">
        <?php if (count($users) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td style="font-weight: 600;"><?php echo $user['ID']; ?></td>
                        <td><?php echo Security::sanitize($user['full_name'] ?? ''); ?></td>
                        <td><?php echo Security::sanitize($user['email_id'] ?? ''); ?></td>
                        <td><?php echo Security::sanitize($user['phone'] ?? ''); ?></td>
                        <td>
                            <span class="badge badge-primary"><?php echo ucfirst($user['role'] ?? 'user'); ?></span>
                        </td>
                        <td>
                            <?php if ($user['status'] === 'active'): ?>
                                <span class="badge badge-success">Active</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo Formatter::date($user['created_at'], 'M d, Y'); ?></td>
                        <td>
                            <button class="btn btn-secondary btn-sm" onclick="editUser(<?php echo $user['ID']; ?>)">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteUser(<?php echo $user['ID']; ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted" style="text-align: center; padding: var(--spacing-xl) 0;">No users found</p>
        <?php endif; ?>
    </div>
</div>

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
<div style="margin-top: var(--spacing-lg); text-align: center;">
    <div class="flex" style="justify-content: center; gap: var(--spacing-sm);">
        <?php if ($page > 1): ?>
            <a href="dashboard.php?page=users&p=1" class="btn btn-secondary btn-sm">First</a>
            <a href="dashboard.php?page=users&p=<?php echo $page - 1; ?>" class="btn btn-secondary btn-sm">Previous</a>
        <?php endif; ?>
        
        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
            <?php if ($i === $page): ?>
                <span style="padding: 8px 12px; background: var(--primary); color: white; border-radius: var(--radius-md);"><?php echo $i; ?></span>
            <?php else: ?>
                <a href="dashboard.php?page=users&p=<?php echo $i; ?>" class="btn btn-secondary btn-sm"><?php echo $i; ?></a>
            <?php endif; ?>
        <?php endfor; ?>
        
        <?php if ($page < $totalPages): ?>
            <a href="dashboard.php?page=users&p=<?php echo $page + 1; ?>" class="btn btn-secondary btn-sm">Next</a>
            <a href="dashboard.php?page=users&p=<?php echo $totalPages; ?>" class="btn btn-secondary btn-sm">Last</a>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<script>
function editUser(userId) {
    alert('Edit user ' + userId + ' - Feature coming soon');
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="user_id" value="${userId}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function openAddUserModal() {
    alert('Add user - Feature coming soon');
}
</script>
