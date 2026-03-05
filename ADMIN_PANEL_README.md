# Professional Admin Panel - Complete Documentation

## Project Overview

This is a fully rebuilt admin panel with modern architecture, clean separation of concerns, and professional SaaS-level design. The entire codebase has been reorganized with clear naming, security best practices, and scalable structure.

## Directory Structure

```
/app
├── config/              # Configuration files
│   ├── config.php      # Application settings
│   └── database.php    # Database connection
├── models/             # Database models
│   ├── Database.php    # Base database class
│   ├── Auth.php        # Authentication model
│   ├── User.php        # User CRUD operations
│   ├── Transaction.php # Deposits/Withdrawals
│   └── Game.php        # Gaming/Betting system
├── views/              # Page templates
│   ├── dashboard.php   # Main dashboard
│   ├── users.php       # User management
│   ├── deposits.php    # Deposit management
│   ├── withdrawals.php # Withdrawal management
│   ├── bets.php        # Betting activity
│   └── settings.php    # Admin settings
├── layouts/            # Reusable components
│   ├── header.php      # Top navigation
│   └── sidebar.php     # Sidebar navigation
├── utils/              # Utility classes
│   ├── Security.php    # Security & sanitization
│   ├── Formatter.php   # Data formatting
│   └── Functions.php   # Helper functions
└── assets/             # Static files
    ├── css/
    │   └── main.css    # Professional SaaS styling
    └── js/
        └── main.js     # Frontend interactivity
```

## Key Features

### 1. **Authentication & Security**
- Session-based authentication with secure hashing
- Role-based access control (RBAC)
- CSRF protection on forms
- Input validation and sanitization
- Prepared statements for SQL injection prevention

### 2. **Database Layer**
- Abstract Database class for all queries
- Model-based architecture (User, Transaction, Game)
- Prepared statements throughout
- Transaction support

### 3. **Admin Features**
- **Dashboard**: KPI cards, recent activity, statistics
- **User Management**: Search, filter, view all users
- **Deposits**: Approve/reject payment deposits
- **Withdrawals**: Process withdrawal requests
- **Bets & Games**: Monitor all betting activity
- **Settings**: Admin profile and system configuration

### 4. **Professional UI**
- Dark theme SaaS design
- Responsive layout (mobile-friendly)
- Component-based styling
- Smooth animations and transitions
- Proper contrast and accessibility

### 5. **Frontend Features**
- Table sorting and filtering
- Modal dialogs
- Toast notifications
- API helper functions
- Utility functions (debounce, throttle, validation)

## Getting Started

### 1. Database Setup

Connect your database in `/app/config/database.php`:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'password');
define('DB_NAME', 'admin_panel');
```

### 2. Access the Panel

- **URL**: `http://localhost/dashboard.php`
- **Login**: `http://localhost/index.php`
- **Default Credentials**: Configure in your database

### 3. File Naming Convention

All files now use clear English naming:
- ✅ `users.php` instead of `manage_user.php`
- ✅ `deposits.php` instead of `deposit_update.php`
- ✅ `withdrawals.php` instead of `manage_withdraw.php`
- ✅ `dashboard.php` instead of `compass.php`

## Architecture Patterns

### Models

All database operations use the Model pattern:

```php
$userModel = new User($conn);
$users = $userModel->getAll($limit, $offset);
$user = $userModel->getById($id);
$userModel->create($data);
$userModel->update($id, $data);
$userModel->delete($id);
```

### Views

Views are simple templates that use data passed from the router:

```php
<?php
// /app/views/dashboard.php
$totalUsers = $userModel->count();
$revenue = $gameModel->getTodaysRevenue();
?>
<h1>Dashboard</h1>
<!-- Display data -->
```

### Layouts

Reusable components included in every page:

```php
<?php include __DIR__ . '/app/layouts/header.php'; ?>
<?php include __DIR__ . '/app/layouts/sidebar.php'; ?>
```

### Security

Use utility classes for safe operations:

```php
$email = Security::sanitize($_POST['email']);
$hashed = Security::hash($password);
$verified = Security::verifyPassword($password, $hashed);
$formatted = Formatter::currency($amount);
```

## Color Scheme

Professional dark theme with carefully selected colors:

```css
--primary: #3B82F6         /* Blue - Actions & Links */
--secondary: #10B981       /* Green - Success */
--danger: #EF4444          /* Red - Errors & Alerts */
--warning: #F59E0B         /* Yellow - Warnings */
--info: #06B6D4            /* Cyan - Information */

--bg-dark: #0F172A         /* Main Background */
--bg-surface: #1E293B      /* Cards & Surfaces */
--text-primary: #F1F5F9    /* Main Text */
--text-secondary: #CBD5E1  /* Secondary Text */
--text-muted: #94A3B8      /* Muted Text */
```

## Components Reference

### Buttons

```html
<button class="btn btn-primary">Primary</button>
<button class="btn btn-secondary">Secondary</button>
<button class="btn btn-danger">Danger</button>
<button class="btn btn-sm">Small</button>
<button class="btn btn-lg">Large</button>
```

### Cards

```html
<div class="card">
    <div class="card-header">
        <h3>Title</h3>
    </div>
    <div class="card-body">
        Content here
    </div>
    <div class="card-footer">
        <button class="btn btn-primary">Save</button>
    </div>
</div>
```

### Badges

```html
<span class="badge badge-primary">Primary</span>
<span class="badge badge-success">Success</span>
<span class="badge badge-danger">Danger</span>
<span class="badge badge-warning">Warning</span>
```

### Alerts

```html
<div class="alert alert-success">Success message</div>
<div class="alert alert-error">Error message</div>
<div class="alert alert-warning">Warning message</div>
<div class="alert alert-info">Info message</div>
```

### Tables

```html
<table class="table">
    <thead>
        <tr>
            <th>Header</th>
            <th>Header</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Data</td>
            <td>Data</td>
        </tr>
    </tbody>
</table>
```

## JavaScript API

### API Helper

```javascript
// GET request
API.get('/api/users').then(data => console.log(data));

// POST request
API.post('/api/users', { name: 'John' }).then(data => console.log(data));

// PUT request
API.put('/api/users/1', { name: 'Jane' }).then(data => console.log(data));

// DELETE request
API.delete('/api/users/1').then(data => console.log(data));
```

### Utilities

```javascript
// Debounce
const search = Utils.debounce((query) => {
    // Search operation
}, 300);

// Throttle
const scroll = Utils.throttle(() => {
    // Scroll handler
}, 1000);

// Validation
Utils.isValidEmail('user@example.com');
Utils.isValidPhone('+1234567890');

// Query Parameters
const userId = Utils.getQueryParam('id');
```

### Notifications

```javascript
showToast('Success message', 'success', 3000);
showToast('Error message', 'error', 3000);
```

### Formatting

```javascript
formatCurrency(1000, 'PKR');      // Rs. 1,000
formatCurrency(1000, 'USD');      // $ 1,000

formatDate('2024-01-01', 'short'); // 1/1/2024
formatDate('2024-01-01', 'long');  // January 1, 2024
```

## Database Requirements

### Users Table
```sql
CREATE TABLE users (
    ID INT PRIMARY KEY,
    full_name VARCHAR(255),
    email_id VARCHAR(255) UNIQUE,
    phone VARCHAR(20),
    password VARCHAR(255),
    role VARCHAR(50),
    status VARCHAR(20),
    created_at TIMESTAMP,
    last_login TIMESTAMP
);
```

### Transactions Table
```sql
CREATE TABLE transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    amount DECIMAL(10,2),
    type VARCHAR(50),
    status VARCHAR(50),
    created_at TIMESTAMP
);
```

### Games/Bets Table
```sql
CREATE TABLE games (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    game_id VARCHAR(100),
    amount DECIMAL(10,2),
    result VARCHAR(50),
    payout DECIMAL(10,2),
    status VARCHAR(50),
    created_at TIMESTAMP
);
```

## Future Enhancements

- [ ] Dashboard charts and analytics
- [ ] Advanced user search and filters
- [ ] Email notifications system
- [ ] Activity logging and audit trail
- [ ] Two-factor authentication
- [ ] Export to CSV/PDF
- [ ] Admin activity dashboard
- [ ] Real-time notifications with WebSockets
- [ ] Multi-language support
- [ ] Dark/Light theme toggle

## Support & Maintenance

This admin panel is built with:
- **PHP 7.4+** (Modern PHP)
- **MySQL/MariaDB** (Database)
- **Vanilla JavaScript** (No frameworks required)
- **CSS3** (Custom styling, no frameworks)

All code follows security best practices and is fully documented.

---

**Built with modern architecture, security-first approach, and professional SaaS design.**
