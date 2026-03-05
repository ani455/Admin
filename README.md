# Admin Panel - Professional SaaS Dashboard

A completely rebuilt admin panel with professional architecture, modern design, and production-ready code.

## Quick Start

1. **Clone the repository**
   ```bash
   git clone https://github.com/ani455/Admin.git
   cd Admin
   ```

2. **Setup environment**
   ```bash
   cp .env.example .env
   # Edit .env with your database credentials
   ```

3. **Access the panel**
   ```
   http://your-domain.com
   Login with your admin credentials
   ```

## Architecture

```
/app/
├── config/          # Database and app configuration
├── models/          # Database models (User, Auth, Transaction, Game)
├── views/           # Page templates
├── layouts/         # Reusable components (header, sidebar)
├── utils/           # Security, Formatter, Helper functions
└── assets/          # CSS, JavaScript, images, fonts

/index.php           # Login page
/dashboard.php       # Main dashboard entry point
```

## Features

- Professional dark SaaS theme
- Real database integration
- User management
- Transaction handling (deposits/withdrawals)
- Game/betting system
- Security-first architecture
- Responsive design
- Clean, maintainable code

## Database Configuration

Edit `.env` file with your database details:

```
DB_HOST=localhost
DB_PORT=3306
DB_USER=root
DB_PASSWORD=your_password
DB_NAME=admin_panel
APP_ENV=production
TIMEZONE=UTC
```

## Pages

- Dashboard - KPI overview
- Users - User management
- Deposits - Deposit transactions
- Withdrawals - Withdrawal requests
- Bets/Games - Betting system tracking
- Settings - Admin settings

## Security Features

- Password hashing with bcrypt
- Prepared statements (SQL injection prevention)
- Input validation and sanitization
- CSRF protection
- Session management
- Secure headers

## Support

For issues or questions, create an issue on GitHub.

---

Built with modern PHP practices and clean architecture principles.
