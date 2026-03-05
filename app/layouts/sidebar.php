<?php
/**
 * Sidebar Navigation Component
 * Main navigation menu for admin dashboard
 */
$currentPage = $_GET['page'] ?? 'dashboard';
?>
<aside class="sidebar">
    <div style="margin-bottom: var(--spacing-xl);">
        <h3 style="margin: 0; color: var(--text-primary); font-size: 18px; font-weight: 700;">Admin</h3>
    </div>
    
    <nav>
        <ul class="menu-list">
            <li class="menu-item">
                <a href="dashboard.php" class="menu-link <?php echo $currentPage === 'dashboard' ? 'active' : ''; ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="menu-item">
                <a href="dashboard.php?page=users" class="menu-link <?php echo $currentPage === 'users' ? 'active' : ''; ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span>Users</span>
                </a>
            </li>
            
            <li class="menu-item">
                <a href="dashboard.php?page=withdrawals" class="menu-link <?php echo $currentPage === 'withdrawals' ? 'active' : ''; ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="23 4 23 10 17 10"></polyline>
                        <polyline points="1 20 1 14 7 14"></polyline>
                        <path d="M3.51 9a9 9 0 0 1 14.85-4"></path>
                        <path d="M20.49 15a9 9 0 0 1-14.85 4"></path>
                    </svg>
                    <span>Withdrawals</span>
                </a>
            </li>
            
            <li class="menu-item">
                <a href="dashboard.php?page=deposits" class="menu-link <?php echo $currentPage === 'deposits' ? 'active' : ''; ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2v20M2 12h20"></path>
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    <span>Deposits</span>
                </a>
            </li>
            
            <li class="menu-item">
                <a href="dashboard.php?page=bets" class="menu-link <?php echo $currentPage === 'bets' ? 'active' : ''; ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 9l12 0"></path>
                        <path d="M6 15l12 0"></path>
                        <path d="M6 3l12 0"></path>
                        <path d="M6 21l12 0"></path>
                    </svg>
                    <span>Bets/Games</span>
                </a>
            </li>
            
            <li class="menu-item" style="margin-top: var(--spacing-xl); padding-top: var(--spacing-lg); border-top: 1px solid var(--border-color);">
                <a href="dashboard.php?page=settings" class="menu-link <?php echo $currentPage === 'settings' ? 'active' : ''; ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M12 1v6m0 6v6M4.22 4.22l4.24 4.24m5.08 0l4.24-4.24M19.78 19.78l-4.24-4.24m-5.08 0l-4.24 4.24"></path>
                    </svg>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
