# ARS ENGINEERS - Solar Energy Website

A premium, full-featured solar energy website with MySQL database backend for managing customer inquiries.

## 🌟 Features

### Frontend
- ✨ Modern green-themed design with smooth animations
- 📱 Fully responsive (mobile, tablet, desktop)
- 🎯 Complete sections: Hero, Services, About, Why Us, Journey, Gallery, FAQ, Contact
- ⚡ Interactive features: Mobile menu, FAQ accordion, scroll animations
- 🖼️ Gallery with lightbox functionality
- 📝 Contact form with client-side validation

### Backend
- 🗄️ MySQL database for storing contact messages
- 🔧 PHP REST API for form submissions
- 📊 Admin panel to view and manage messages
- 🔒 SQL injection protection (prepared statements)
- ✅ Input validation and sanitization
- 📧 Optional email notifications

## 📋 Requirements

- **PHP 7.4+** with PDO MySQL extension
- **MySQL 5.7+** or MariaDB
- **Web Server**: Apache, Nginx, or PHP built-in server

## 🚀 Installation

### 1. Clone or Download

```bash
cd /Users/riteshkhande/pythonp/ARS
```

### 2. Install MySQL (if not installed)

**macOS:**
```bash
brew install mysql
brew services start mysql
```

**Ubuntu/Debian:**
```bash
sudo apt update
sudo apt install mysql-server php-mysql
sudo systemctl start mysql
```

### 3. Create Database

```bash
# Login to MySQL
mysql -u root -p

# Run the schema file
source database/schema.sql

# Or use command line directly
mysql -u root -p < database/schema.sql
```

### 4. Configure Database Credentials

Edit `config/database.php` and update:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'ars_solar');
define('DB_USER', 'root');
define('DB_PASS', 'your_mysql_password');
```

### 5. Start PHP Development Server

```bash
php -S localhost:8000
```

### 6. Access the Website

- **Main Website**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin.php

## 📁 Project Structure

```
/Users/riteshkhande/pythonp/ARS/
├── index.html              # Main website
├── styles.css              # Green-themed design system
├── script.js               # Interactive JavaScript with AJAX
├── admin.php               # Admin panel
├── config/
│   └── database.php        # Database configuration
├── api/
│   └── submit_contact.php  # Form submission handler
├── admin/
│   └── styles.css          # Admin panel styles
├── database/
│   ├── schema.sql          # Database schema
│   └── setup_instructions.md
├── images/
│   ├── solar-panel-hero.jpg
│   ├── solar-installation.jpg
│   ├── residential-solar.jpg
│   └── commercial-solar.jpg
└── logs/
    └── php_errors.log      # Error logs
```

## 🎨 Color Theme

The website uses a green solar energy theme:

- **Primary Gradient**: `#10b981` → `#22c55e` (Emerald to Green)
- **Secondary Gradient**: `#059669` → `#16a34a` (Dark Emerald to Green)
- **Background**: Dark theme (`#0f172a`, `#1e293b`)
- **Accents**: Green (`#10b981`), Orange (`#f59e0b`)

## 📊 Database Schema

### contact_messages Table

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Auto-increment primary key |
| name | VARCHAR(255) | Customer name |
| email | VARCHAR(255) | Customer email |
| phone | VARCHAR(20) | Customer phone number |
| subject | VARCHAR(255) | Message subject (optional) |
| message | TEXT | Customer message |
| created_at | TIMESTAMP | Submission timestamp |
| is_read | BOOLEAN | Read status (default: false) |

## 🔧 Admin Panel Features

Access at: `http://localhost:8000/admin.php`

- 📋 View all contact messages in a table
- 📞 Display customer phone numbers and emails
- ✉️ Click to call/email directly
- ✓ Mark messages as read
- 🗑️ Delete messages
- 🔄 Auto-refresh every 30 seconds
- 📱 Responsive design

## 🔒 Security Features

### Implemented
- ✅ Prepared statements (SQL injection prevention)
- ✅ Input validation (email, phone, message length)
- ✅ XSS prevention (htmlspecialchars)
- ✅ CSRF-safe form submissions
- ✅ Error logging (not displayed to users)

### Recommended for Production
- 🔒 Admin authentication system
- 🔒 HTTPS/SSL encryption
- 🔒 Rate limiting on form submissions
- 🔒 CAPTCHA for spam prevention
- 🔒 Environment variables for credentials
- 🔒 Regular database backups

## 📧 Email Notifications (Optional)

To enable email notifications when customers submit the form:

1. Edit `api/submit_contact.php`
2. Uncomment the `mail()` function call (line ~120)
3. Configure your server's mail settings

## 🧪 Testing

### Test Contact Form

1. Open http://localhost:8000
2. Scroll to contact section
3. Fill out the form:
   - Name: Test User
   - Email: test@example.com
   - Phone: 9876543210
   - Message: Test message
4. Submit and verify success message
5. Check admin panel for the message

### Test Admin Panel

1. Open http://localhost:8000/admin.php
2. Verify messages appear in table
3. Test "Mark as Read" button
4. Test "Delete" button
5. Verify phone numbers are clickable

## 🐛 Troubleshooting

### Database Connection Error

**Error**: "Database connection failed"

**Solutions**:
1. Verify MySQL is running: `brew services list` or `sudo systemctl status mysql`
2. Check credentials in `config/database.php`
3. Ensure database exists: `mysql -u root -p -e "SHOW DATABASES;"`

### PDO Extension Not Found

**Error**: "PDO extension not found"

**Solutions**:
```bash
# Check if PDO is installed
php -m | grep pdo

# Install on Ubuntu/Debian
sudo apt install php-mysql
sudo systemctl restart apache2

# Install on macOS (usually pre-installed)
brew reinstall php
```

### Form Submission Not Working

**Solutions**:
1. Check browser console for JavaScript errors
2. Verify `api/submit_contact.php` is accessible
3. Check `logs/php_errors.log` for PHP errors
4. Ensure database connection is working

### Admin Panel Shows No Messages

**Solutions**:
1. Submit a test message first
2. Check database: `mysql -u root -p ars_solar -e "SELECT * FROM contact_messages;"`
3. Verify PHP errors in `logs/php_errors.log`

## 📝 Company Details

**ARS ENGINEERS**
- Address: F-11 B C M CITY BLOCK A, NAVLAKHA, INDORE, MP - 452001
- Phone: +91 9111616975
- Email: arengineers24@gmail.com
- Business Hours: Monday-Saturday 9:00 AM - 7:00 PM

## 🔄 Backup & Restore

### Backup Database

```bash
mysqldump -u root -p ars_solar > backup_$(date +%Y%m%d).sql
```

### Restore Database

```bash
mysql -u root -p ars_solar < backup_20231210.sql
```

## 📚 Additional Resources

- [Database Setup Instructions](database/setup_instructions.md)
- [PHP Documentation](https://www.php.net/manual/en/)
- [MySQL Documentation](https://dev.mysql.com/doc/)

## 📄 License

© 2024 ARS ENGINEERS. All rights reserved.

---

**Built with ❤️ for a sustainable future** 🌞
