<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<aside class="admin-sidebar">
    <nav class="sidebar-nav">
        <div class="nav-section">
            <h4 class="nav-title">MAIN</h4>
            <ul class="nav-list">
                <li>
                    <a href="dashboard.php" class="nav-link <?php echo $currentPage === 'dashboard.php' ? 'active' : ''; ?>">
                        <span class="nav-icon">📊</span>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="nav-section">
            <h4 class="nav-title">MANAGEMENT</h4>
            <ul class="nav-list">
                <li>
                    <a href="users.php" class="nav-link <?php echo $currentPage === 'users.php' ? 'active' : ''; ?>">
                        <span class="nav-icon">👥</span>
                        <span class="nav-text">Users</span>
                    </a>
                </li>
                <li>
                    <a href="deposits.php" class="nav-link <?php echo $currentPage === 'deposits.php' ? 'active' : ''; ?>">
                        <span class="nav-icon">💰</span>
                        <span class="nav-text">Deposits</span>
                    </a>
                </li>
                <li>
                    <a href="withdrawals.php" class="nav-link <?php echo $currentPage === 'withdrawals.php' ? 'active' : ''; ?>">
                        <span class="nav-icon">💳</span>
                        <span class="nav-text">Withdrawals</span>
                    </a>
                </li>
                <li>
                    <a href="bets.php" class="nav-link <?php echo $currentPage === 'bets.php' ? 'active' : ''; ?>">
                        <span class="nav-icon">🎲</span>
                        <span class="nav-text">Bets</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="nav-section">
            <h4 class="nav-title">SYSTEM</h4>
            <ul class="nav-list">
                <li>
                    <a href="reports.php" class="nav-link <?php echo $currentPage === 'reports.php' ? 'active' : ''; ?>">
                        <span class="nav-icon">📈</span>
                        <span class="nav-text">Reports</span>
                    </a>
                </li>
                <li>
                    <a href="settings.php" class="nav-link <?php echo $currentPage === 'settings.php' ? 'active' : ''; ?>">
                        <span class="nav-icon">⚙️</span>
                        <span class="nav-text">Settings</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</aside>

<style>
    .admin-sidebar {
        width: 260px;
        background: rgba(15, 23, 42, 0.8);
        border-right: 1px solid rgba(226, 232, 240, 0.1);
        padding: 24px 0;
        height: calc(100vh - 65px);
        overflow-y: auto;
        position: sticky;
        top: 65px;
    }
    
    .sidebar-nav {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    
    .nav-section {
        padding: 0 16px;
    }
    
    .nav-title {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        padding: 0 12px;
        margin-bottom: 8px;
        letter-spacing: 0.5px;
    }
    
    .nav-list {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    .nav-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        color: #cbd5e1;
        text-decoration: none;
        border-radius: 6px;
        transition: all 0.3s ease;
        font-size: 14px;
        font-weight: 500;
        border-left: 3px solid transparent;
    }
    
    .nav-link:hover {
        background: rgba(226, 232, 240, 0.08);
        color: #f1f5f9;
    }
    
    .nav-link.active {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        border-left-color: #3b82f6;
    }
    
    .nav-icon {
        font-size: 18px;
        min-width: 24px;
    }
    
    .nav-text {
        flex: 1;
    }
    
    /* Scrollbar styling */
    .admin-sidebar::-webkit-scrollbar {
        width: 6px;
    }
    
    .admin-sidebar::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .admin-sidebar::-webkit-scrollbar-thumb {
        background: rgba(226, 232, 240, 0.1);
        border-radius: 3px;
    }
    
    .admin-sidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(226, 232, 240, 0.2);
    }
    
    @media (max-width: 768px) {
        .admin-sidebar {
            width: 70px;
            padding: 16px 0;
        }
        
        .nav-text {
            display: none;
        }
        
        .nav-title {
            display: none;
        }
        
        .nav-link {
            justify-content: center;
            padding: 12px;
        }
    }
</style>
