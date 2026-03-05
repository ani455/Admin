<?php
require_once(__DIR__ . '/../core/Auth.php');

$admin = Auth::getCurrentAdmin($conn);
?>
<header class="admin-header">
    <div class="header-left">
        <div class="logo-section">
            <div class="logo-icon">G</div>
            <span class="logo-text">Gaming Admin</span>
        </div>
    </div>
    
    <div class="header-right">
        <div class="search-box">
            <input type="text" placeholder="Search...">
        </div>
        
        <div class="header-actions">
            <button class="notification-btn" title="Notifications">
                <span class="icon">🔔</span>
                <span class="badge">3</span>
            </button>
            
            <div class="admin-menu">
                <button class="admin-btn">
                    <span class="admin-avatar"><?php echo strtoupper(substr($admin['nirvahaka_hesaru'] ?? 'A', 0, 1)); ?></span>
                    <span class="admin-name"><?php echo htmlspecialchars($admin['nirvahaka_hesaru'] ?? 'Admin'); ?></span>
                </button>
                
                <div class="dropdown-menu">
                    <a href="profile.php" class="dropdown-item">Profile</a>
                    <a href="settings.php" class="dropdown-item">Settings</a>
                    <hr class="dropdown-divider">
                    <a href="logout.php" class="dropdown-item logout">Logout</a>
                </div>
            </div>
        </div>
    </div>
</header>

<style>
    .admin-header {
        background: rgba(15, 23, 42, 0.95);
        border-bottom: 1px solid rgba(226, 232, 240, 0.1);
        padding: 16px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        backdrop-filter: blur(10px);
        position: sticky;
        top: 0;
        z-index: 100;
    }
    
    .header-left {
        display: flex;
        align-items: center;
    }
    
    .logo-section {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .logo-icon {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 18px;
    }
    
    .logo-text {
        font-size: 16px;
        font-weight: 600;
        color: #f1f5f9;
    }
    
    .header-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    
    .search-box {
        display: none;
    }
    
    .search-box input {
        background: rgba(226, 232, 240, 0.05);
        border: 1px solid rgba(226, 232, 240, 0.15);
        border-radius: 6px;
        padding: 8px 12px;
        color: #f1f5f9;
        font-size: 13px;
        width: 200px;
        transition: all 0.3s ease;
    }
    
    .search-box input:focus {
        outline: none;
        border-color: #3b82f6;
        background: rgba(226, 232, 240, 0.08);
    }
    
    .header-actions {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    
    .notification-btn {
        position: relative;
        background: none;
        border: none;
        color: #cbd5e1;
        cursor: pointer;
        font-size: 20px;
        transition: color 0.3s ease;
    }
    
    .notification-btn:hover {
        color: #f1f5f9;
    }
    
    .notification-btn .badge {
        position: absolute;
        top: -6px;
        right: -6px;
        background: #ef4444;
        color: white;
        font-size: 10px;
        padding: 2px 5px;
        border-radius: 10px;
        font-weight: bold;
    }
    
    .admin-menu {
        position: relative;
    }
    
    .admin-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        background: none;
        border: none;
        color: #f1f5f9;
        cursor: pointer;
        padding: 6px 12px;
        border-radius: 6px;
        transition: background 0.3s ease;
    }
    
    .admin-btn:hover {
        background: rgba(226, 232, 240, 0.1);
    }
    
    .admin-avatar {
        width: 28px;
        height: 28px;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 12px;
        color: white;
    }
    
    .admin-name {
        font-size: 13px;
        font-weight: 500;
    }
    
    .dropdown-menu {
        position: absolute;
        top: 100%;
        right: 0;
        background: rgba(15, 23, 42, 0.95);
        border: 1px solid rgba(226, 232, 240, 0.15);
        border-radius: 8px;
        min-width: 180px;
        margin-top: 8px;
        display: none;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(10px);
        z-index: 1000;
    }
    
    .admin-menu:hover .dropdown-menu {
        display: block;
    }
    
    .dropdown-item {
        display: block;
        padding: 10px 16px;
        color: #cbd5e1;
        text-decoration: none;
        font-size: 13px;
        transition: all 0.3s ease;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
    }
    
    .dropdown-item:hover {
        background: rgba(226, 232, 240, 0.1);
        color: #f1f5f9;
    }
    
    .dropdown-item.logout {
        color: #fca5a5;
    }
    
    .dropdown-item.logout:hover {
        background: rgba(239, 68, 68, 0.1);
    }
    
    .dropdown-divider {
        border: none;
        border-top: 1px solid rgba(226, 232, 240, 0.1);
        margin: 8px 0;
    }
    
    @media (min-width: 768px) {
        .search-box {
            display: block;
        }
    }
</style>
