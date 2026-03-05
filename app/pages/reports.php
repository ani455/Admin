<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../core/Auth.php');

Auth::requireLogin();
$admin = Auth::getCurrentAdmin($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Admin Panel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #0f172a; color: #e2e8f0; }
        .admin-layout { display: flex; height: 100vh; }
        .admin-main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        .admin-content { flex: 1; overflow-y: auto; padding: 24px; }
        .page-header h1 { font-size: 28px; font-weight: 700; margin-bottom: 8px; color: #f1f5f9; }
        .card { background: rgba(30, 41, 59, 0.8); border: 1px solid rgba(226, 232, 240, 0.1); border-radius: 12px; padding: 24px; }
        .empty-state { text-align: center; padding: 48px; color: #64748b; }
    </style>
</head>
<body>
    <div class="admin-layout">
        <?php require_once(__DIR__ . '/../layouts/sidebar.php'); ?>
        <div class="admin-main">
            <?php require_once(__DIR__ . '/../layouts/header.php'); ?>
            <div class="admin-content">
                <div class="page-header">
                    <h1>Reports</h1>
                </div>
                <div class="card">
                    <div class="empty-state">
                        <p>Reports coming soon...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
