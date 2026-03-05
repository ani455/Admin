<?php
/**
 * Settings View
 */

$currentUser = $authModel->getUser();
?>

<div class="page-header">
    <h1>Settings</h1>
    <p class="text-muted">Manage admin profile and system settings</p>
</div>

<!-- Admin Profile Card -->
<div class="card" style="max-width: 600px;">
    <div class="card-header">
        <h3 style="margin: 0;">Admin Profile</h3>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label>Name</label>
            <p><?php echo Security::sanitize($currentUser['full_name'] ?? ''); ?></p>
        </div>
        
        <div class="form-group">
            <label>Email</label>
            <p><?php echo Security::sanitize($currentUser['email_id'] ?? ''); ?></p>
        </div>
        
        <div class="form-group">
            <label>Role</label>
            <p><?php echo ucfirst($currentUser['role'] ?? 'Admin'); ?></p>
        </div>
        
        <div class="form-group">
            <label>Last Login</label>
            <p><?php echo Formatter::date($currentUser['last_login'] ?? '', 'M d, Y H:i'); ?></p>
        </div>
    </div>
    <div class="card-footer">
        <button class="btn btn-primary">Edit Profile</button>
        <button class="btn btn-secondary">Change Password</button>
    </div>
</div>

<!-- System Settings Card -->
<div class="card" style="max-width: 600px; margin-top: var(--spacing-lg);">
    <div class="card-header">
        <h3 style="margin: 0;">System Settings</h3>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label>Maintenance Mode</label>
            <div style="display: flex; gap: var(--spacing-md); align-items: center; margin-top: var(--spacing-sm);">
                <input type="checkbox" id="maintenanceMode">
                <label for="maintenanceMode" style="margin: 0;">Enable maintenance mode</label>
            </div>
        </div>
        
        <div class="form-group">
            <label>Email Notifications</label>
            <div style="display: flex; gap: var(--spacing-md); align-items: center; margin-top: var(--spacing-sm);">
                <input type="checkbox" id="emailNotifications" checked>
                <label for="emailNotifications" style="margin: 0;">Send email notifications</label>
            </div>
        </div>
        
        <div class="form-group">
            <label>Auto Logout (minutes)</label>
            <input type="number" value="30" min="5" max="120" style="max-width: 150px;">
        </div>
    </div>
    <div class="card-footer">
        <button class="btn btn-primary">Save Settings</button>
    </div>
</div>

<!-- Danger Zone -->
<div class="card" style="max-width: 600px; margin-top: var(--spacing-lg); border-color: var(--danger);">
    <div class="card-header" style="border-bottom-color: var(--danger);">
        <h3 style="margin: 0; color: var(--danger);">Danger Zone</h3>
    </div>
    <div class="card-body">
        <p class="text-muted">Irreversible actions. Use with caution.</p>
    </div>
    <div class="card-footer">
        <button class="btn btn-danger">Clear All Logs</button>
    </div>
</div>

<style>
    .form-group p {
        margin: var(--spacing-sm) 0 0 0;
        color: var(--text-secondary);
        padding: 10px 12px;
        background: var(--bg-dark);
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
    }
    
    .form-group input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
    
    .form-group input[type="number"] {
        padding: 10px 12px;
        background: var(--bg-dark);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        color: var(--text-primary);
    }
</style>
