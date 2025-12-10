# Database Setup Instructions

## Prerequisites

1. **MySQL/MariaDB** must be installed on your system
2. **PHP 7.4+** with PDO MySQL extension

## Installation Steps

### 1. Install MySQL (if not already installed)

**macOS:**
```bash
brew install mysql
brew services start mysql
```

**Ubuntu/Debian:**
```bash
sudo apt update
sudo apt install mysql-server
sudo systemctl start mysql
```

### 2. Secure MySQL Installation (Recommended)

```bash
sudo mysql_secure_installation
```

Follow the prompts to:
- Set root password
- Remove anonymous users
- Disallow root login remotely
- Remove test database

### 3. Create Database and Tables

**Option A: Using MySQL command line**

```bash
# Login to MySQL
mysql -u root -p

# Run the schema file
source /Users/riteshkhande/pythonp/ARS/database/schema.sql

# Verify tables were created
USE ars_solar;
SHOW TABLES;
DESCRIBE contact_messages;

# Exit MySQL
exit;
```

**Option B: Using command line directly**

```bash
mysql -u root -p < /Users/riteshkhande/pythonp/ARS/database/schema.sql
```

### 4. Configure Database Credentials

Edit `config/database.php` and update with your MySQL credentials:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'ars_solar');
define('DB_USER', 'root');
define('DB_PASS', 'your_mysql_password');
```

### 5. Test Database Connection

```bash
cd /Users/riteshkhande/pythonp/ARS
php -r "require 'config/database.php'; echo 'Database connection successful!';"
```

## Troubleshooting

### Connection Refused
- Ensure MySQL service is running: `brew services list` or `sudo systemctl status mysql`
- Check MySQL is listening on port 3306: `netstat -an | grep 3306`

### Access Denied
- Verify username and password in `config/database.php`
- Check MySQL user permissions: `GRANT ALL PRIVILEGES ON ars_solar.* TO 'root'@'localhost';`

### PDO Extension Not Found
```bash
# Check if PDO is installed
php -m | grep pdo

# If not installed (Ubuntu/Debian)
sudo apt install php-mysql

# Restart PHP-FPM if using it
sudo systemctl restart php-fpm
```

## Default Credentials

**MySQL:**
- Host: localhost
- Database: ars_solar
- User: root
- Password: (your MySQL root password)

**Admin Panel:**
- Username: admin
- Password: admin123 (CHANGE THIS!)

## Security Notes

⚠️ **IMPORTANT**: Before deploying to production:

1. Change the default admin password
2. Use environment variables for database credentials
3. Enable SSL/TLS for MySQL connections
4. Implement proper admin authentication
5. Add CSRF protection
6. Enable prepared statements (already implemented)
7. Set up regular database backups

## Backup Database

```bash
# Create backup
mysqldump -u root -p ars_solar > backup_$(date +%Y%m%d).sql

# Restore from backup
mysql -u root -p ars_solar < backup_20231210.sql
```
