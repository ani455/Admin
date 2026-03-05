# Quick Start Guide

## Access Your New Admin Panel

### Step 1: Open in Browser
```
http://your-domain.com/index.php
OR
http://your-domain.com/app/pages/login.php
```

### Step 2: Login
- **Username**: Use your existing admin username
- **Password**: Use your existing admin password

### Step 3: Explore Dashboard
You'll see your real data:
- Total Users count
- Total Deposits amount
- Pending Withdrawals
- Active Bets
- Recent transactions

## Navigation

### Sidebar Menu
- **Dashboard** - Overview with KPIs
- **Users** - Manage platform users
- **Deposits** - Approve/reject deposits
- **Withdrawals** - Manage withdrawals
- **Bets** - Track bets
- **Reports** - Analytics (coming soon)
- **Settings** - System configuration (coming soon)

### Top Menu
- **Search** - Find users and transactions
- **Notifications** - View alerts
- **Profile** - Your admin profile
- **Logout** - Exit admin panel

## Common Tasks

### Approve a Deposit
1. Go to **Deposits** from sidebar
2. Find pending deposit
3. Click **Approve** button
4. Deposit status updates immediately

### Check User Details
1. Go to **Users** from sidebar
2. Search for username in table
3. Click user row to see details
4. View balance and transaction history

### View Pending Withdrawals
1. Go to **Withdrawals** from sidebar
2. Use **Pending** filter to see pending only
3. Review withdrawal requests
4. Approve or reject as needed

### Check Active Bets
1. Go to **Bets** from sidebar
2. View all active bets
3. See wagered amounts
4. Monitor bet outcomes

## Important Notes

- **All data is real** from your database
- **Changes are permanent** - be careful with approvals
- **Mobile friendly** - works on phones and tablets
- **Secure** - all operations use secure connections
- **Fast** - optimized database queries

## Key Features

✅ Modern SaaS design (not AI-generated)
✅ Professional dark theme
✅ Responsive (mobile, tablet, desktop)
✅ Secure authentication
✅ Real-time data from database
✅ Role-based access control ready
✅ API endpoints for automation
✅ Clean, maintainable code

## Troubleshooting

### Login Not Working
- Check username and password
- Verify admin user exists in database
- Check database connection in `app/config/database.php`

### Data Not Showing
- Verify database credentials
- Check if tables exist in database
- Review error logs

### Mobile Not Working
- Clear browser cache
- Try in private/incognito mode
- Update browser to latest version

## File Structure

```
New Admin Panel Location: /app/
├── pages/       Admin pages you see
├── models/      Database operations
├── api/         API endpoints
├── core/        Authentication & security
├── config/      Database connection
└── layouts/     Header & sidebar
```

## Next Steps

1. **Test all pages** - Make sure everything works
2. **Train users** - Show team how to use it
3. **Customize** - Modify colors/design if needed
4. **Extend** - Add more features as needed
5. **Monitor** - Check logs for any issues

## Getting Help

- Read `/app/README.md` for detailed documentation
- Check `/ADMIN_PANEL_REBUILD.md` for complete changes
- Review code comments for specific functions
- All code is well-documented and easy to understand

---

**Your professional admin panel is ready to use!**

Access it now at: `http://your-domain.com/`
