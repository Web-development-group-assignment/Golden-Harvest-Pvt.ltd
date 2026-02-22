# Rice Factory Inventory Management System (IMS)

A PHP (PDO) + MySQL app for managing a rice factory's inventory with Admin/Manager/Clerk roles.

## Features
- Authentication, role-based access (Admin, Manager, Clerk)
- Items (CRUD), Suppliers (CRUD)
- Inbound/Outbound stock movements; live balances
- Stock ledger per item
- Reports: Low stock, Daily movements
- Audit logs for key actions
- Golden Harvest theme with dark/light toggle (persistent)

## Setup
1. Create MySQL DB `rice_inventory`.
2. Place project under web root: `htdocs/rice-factory-inventory/`.
3. Edit `config/config.php` and set DB credentials + APP_URL.
4. Run `http://localhost/rice-factory-inventory/config/init_db.php` (creates tables and seeds admin `admin@ricefactory.local / Admin@123`).
5. Open `http://localhost/rice-factory-inventory/public/` and login.

## Notes
- All write operations use prepared statements and CSRF tokens.
- Soft delete pattern for items/suppliers; deactivate instead of hard delete.
- `.htaccess` restricts direct access to `config/` and `lib/`.

## License
MIT (for learning/demo)
