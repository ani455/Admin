# Admin Panel Migration Guide

## What Changed?

Your entire admin panel has been professionally rebuilt with modern architecture, clean code organization, and security best practices.

## Old vs New File Structure

### Old Structure (Messy)
```
├── dashboard.php           ❌ Large, cluttered
├── manage_user.php         ❌ Unclear naming
├── deposit_update.php      ❌ Confusing names
├── manage_withdraw.php     ❌ Unclear naming
├── compass.php             ❌ Cryptic name
├── logout.php              ❌ Scattered files
└── css/style.css           ❌ Limited styling
```

### New Structure (Clean)
```
├── dashboard.php           ✅ Entry point (clean)
├── index.php               ✅ Login page
├── app/
│   ├── config/
│   │   ├── config.php
│   │   └── database.php
│   ├── models/
│   │   ├── Auth.php
│   │   ├── User.php
│   │   ├── Transaction.php
│   │   ├── Game.php
│   │   └── Database.php
│   ├── views/
│   │   ├── dashboard.php
│   │   ├── users.php
│   │   ├── deposits.php
│   │   ├── withdrawals.php
│   │   ├── bets.php
│   │   └── settings.php
│   ├── layouts/
│   │   ├── header.php
│   │   └── sidebar.php
│   ├── utils/
│   │   ├── Security.php
│   │   ├── Formatter.php
│   │   └── Functions.php
│   └── assets/
│       ├── css/main.css
│       └── js/main.js
```

## Key Improvements

### 1. File Organization
- **Before**: 15+ scattered files at root level
- **After**: Organized in logical folders by function

### 2. Code Quality
- **Before**: Inline code, mixed concerns
- **After**: Separated models, views, controllers, utilities

### 3. Security
- **Before**: Basic mysqli queries
- **After**: Prepared statements everywhere, password hashing, input validation

### 4. Database Connection
- **Before**: Inline connections in every file
- **After**: Centralized in `/app/config/database.php`

### 5. UI Design
- **Before**: Bootstrap template (old styling)
- **After**: Custom professional SaaS dark theme

### 6. File Naming
- **Before**: `manage_user.php`, `deposit_update.php`, `compass.php`
- **After**: `users.php`, `deposits.php`, `dashboard.php`

## How to Use

### 1. Update Your Database Connection

Edit `/app/config/database.php`:

```php
define('DB_HOST', 'your_host');
define('DB_USER', 'your_user');
define('DB_PASS', 'your_password');
define('DB_NAME', 'your_database');
```

### 2. Map Your Database Tables

Update `/app/models/` to match your current table names:

```php
// In User.php
private $table = 'users';  // Change to your table name

// In Transaction.php
private $table = 'transactions';  // Change to your table name
```

### 3. Update Authentication Logic

Modify `/app/models/Auth.php` to match your user table structure:

```php
public function login($email, $password) {
    // Match this to your actual table columns
    $query = "SELECT * FROM users WHERE email = ?";
    // ...
}
```

### 4. Customize Pages

Edit `/app/views/` files to add your specific business logic and features.

### 5. Add Styling

Modify `/app/assets/css/main.css` for your brand colors and styles.

## URL Changes

| Feature | Old URL | New URL |
|---------|---------|---------|
| Dashboard | `/dashboard.php` | `/dashboard.php` |
| Login | `/app/pages/login.php` | `/index.php` |
| Users | `/manage_user.php` | `/dashboard.php?page=users` |
| Deposits | `/deposit_update.php` | `/dashboard.php?page=deposits` |
| Withdrawals | `/manage_withdraw.php` | `/dashboard.php?page=withdrawals` |
| Bets | `/bets.php` | `/dashboard.php?page=bets` |
| Settings | `/settings.php` | `/dashboard.php?page=settings` |

## Session Variables

All session variables have been standardized:

```php
$_SESSION['user_id']       // User ID
$_SESSION['user_email']    // User email
$_SESSION['user_name']     // User name
$_SESSION['user_role']     // User role (admin, manager, etc)
```

## Database Compatibility

Your existing database tables will work. Just update the models:

```php
// /app/models/User.php
class User extends Database {
    private $table = 'shonu_subjects';  // Your existing table
    
    public function getAll($limit = 20, $offset = 0) {
        $query = "SELECT * FROM {$this->table} LIMIT ? OFFSET ?";
        // ...
    }
}
```

## Old Files to Remove

After migration, you can remove (backup first!):
- Old view files (`manage_user.php`, `deposit_update.php`, etc.)
- Old CSS files in `/css/` folder
- Old JS files in `/js/` folder
- `compass.php` and other helper files
- `/vendors/` folder if not needed

## New Features Available

1. **Professional UI**: Dark theme with modern components
2. **JavaScript Interactivity**: Table sorting, modals, notifications
3. **Better Security**: Prepared statements, input validation, hashing
4. **Cleaner Code**: Models, views, utilities separation
5. **API Ready**: Helper functions for API calls
6. **Responsive Design**: Mobile-friendly interface

## Support

All files include documentation comments. Check:
- `/ADMIN_PANEL_README.md` - Full reference
- `MIGRATION_GUIDE.md` - This file
- Inline PHP comments in each class

## Questions?

Key files to understand:
- `dashboard.php` - Entry point and router
- `/app/models/Auth.php` - Authentication logic
- `/app/views/dashboard.php` - Dashboard example
- `/app/assets/css/main.css` - All styling

---

**Your admin panel is now modern, secure, and professional!**
