<?php
/**
 * Header Layout Component
 * Top navigation and user info
 */
$currentUser = $_SESSION['user_name'] ?? 'Admin';
$currentEmail = $_SESSION['user_email'] ?? '';
?>
<header class="header">
    <div class="header-left">
        <h3 style="margin: 0; color: var(--text-primary);">Admin Dashboard</h3>
    </div>
    
    <div class="header-right">
        <div class="flex" style="gap: var(--spacing-md); align-items: center;">
            <span class="text-secondary" style="font-size: 13px;"><?php echo Security::sanitize($currentUser); ?></span>
            <div style="position: relative;">
                <button class="btn btn-secondary btn-sm" onclick="toggleUserMenu(event)" style="padding: 8px 12px;">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor" style="margin-right: 4px;">
                        <path d="M8 8C9.1 8 10 7.1 10 6C10 4.9 9.1 4 8 4C6.9 4 6 4.9 6 6C6 7.1 6.9 8 8 8ZM8 10C6.7 10 4.5 10.8 4.5 12V14H11.5V12C11.5 10.8 9.3 10 8 10Z"/>
                    </svg>
                    Menu
                </button>
                <div id="userMenu" class="user-menu" style="display: none; position: absolute; top: 100%; right: 0; background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-md); margin-top: 8px; z-index: 1000; min-width: 160px; box-shadow: var(--shadow-md); overflow: hidden;">
                    <a href="dashboard.php?page=settings" style="display: block; padding: 10px 16px; color: var(--text-secondary); border-bottom: 1px solid var(--border-color); text-decoration: none; transition: all 0.2s ease; font-size: 13px;">Settings</a>
                    <a href="dashboard.php?action=logout" style="display: block; padding: 10px 16px; color: #F87171; text-decoration: none; transition: all 0.2s ease; font-size: 13px;">Logout</a>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
function toggleUserMenu(e) {
    e.stopPropagation();
    const menu = document.getElementById('userMenu');
    menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
}

document.addEventListener('click', function(event) {
    const userMenu = document.getElementById('userMenu');
    if (userMenu && !userMenu.contains(event.target) && !event.target.closest('button')) {
        userMenu.style.display = 'none';
    }
});
</script>
