# Admin Panel - Clean Architecture

Modern, professional SaaS-level admin panel for the gaming platform with clean code structure, security best practices, and responsive design.

## Directory Structure

```
app/
├── config/          # Configuration files
│   └── database.php # Database connection & queries
├── core/            # Core functionality
│   ├── Auth.php     # Authentication & session management
│   ├── Functions.php # Utility functions
│   └── Security.php # Security utilities (CSRF, hashing, sanitization)
├── models/          # Database models
│   ├── User.php     # User operations
│   ├── Deposit.php  # Deposit operations
│   ├── Withdrawal.php # Withdrawal operations
│   ├── Bet.php      # Bet operations
│   └── Dashboard.php # Dashboard statistics
├── api/             # API endpoints
│   ├── users.php    # User API
│   ├── deposits.php # Deposit API
│   ├── withdrawals.php # Withdrawal API
│   └── dashboard.php # Dashboard API
├── pages/           # Admin pages
│   ├── login.php    # Login page
│   ├── dashboard.php # Dashboard
│   ├── users.php    # Users management
│   ├── deposits.php # Deposits management
│   ├── withdrawals.php # Withdrawals management
│   ├── bets.php     # Bets management
│   ├── reports.php  # Reports
│   ├── settings.php # Settings
│   └── logout.php   # Logout handler
├── layouts/         # Shared layout components
│   ├── header.php   # Header component
│   └── sidebar.php  # Sidebar navigation
└── assets/          # Static assets (images, fonts, etc)
```

## Key Features

- **Modern Design**: Professional dark theme with responsive layout
- **Security**: Prepared statements, CSRF protection, password hashing
- **Clean Code**: Organized structure with separation of concerns
- **Database Models**: OOP approach to data operations
- **API Endpoints**: RESTful API for frontend operations
- **Authentication**: Secure session management with role-based access
- **Responsive**: Mobile-first design that works on all devices

## Installation

1. Ensure database credentials are correct in `config/database.php`
2. Database tables must exist (users, deposits, withdrawals, bets, etc.)
3. Access via `index.php` or `/app/pages/login.php`
4. Default login redirects to modern dashboard

## Database Tables

The system uses these main tables:
- `nirvahaka_shonu` - Admin users
- `byabaharkarta` - Platform users
- `abramu_deposita` - Deposits
- `nishpatra_edeyisu` - Withdrawals
- `bajikattuttate` - Bets

## Security Features

- **Prepared Statements**: All queries use parameterized statements
- **CSRF Protection**: Token validation on all POST requests
- **Password Hashing**: Bcrypt hashing with fallback for MD5 migration
- **Input Sanitization**: All inputs are sanitized and validated
- **Session Management**: Secure session handling with timeout support

## API Usage

### Get Dashboard Overview
```
GET /app/api/dashboard.php?action=overview
```

### Get Users List
```
GET /app/api/users.php?action=list&limit=50&offset=0
```

### Approve Deposit
```
POST /app/api/deposits.php?action=approve
body: { id: "deposit_id", csrf_token: "token" }
```

## Login Credentials

Use existing admin credentials from `nirvahaka_shonu` table.

## Frontend Architecture

- **Header**: User profile, notifications, logout
- **Sidebar**: Navigation menu with active indicators
- **Dashboard**: Overview cards and recent activity
- **Pages**: Dedicated management pages for each module
- **Responsive**: Mobile sidebar collapses to icons

## Next Steps

1. Implement full CRUD operations for each module
2. Add data pagination and filtering
3. Implement real-time notifications
4. Add comprehensive logging
5. Setup error tracking and monitoring

## Support

For issues or questions, refer to the database schema and existing models.
