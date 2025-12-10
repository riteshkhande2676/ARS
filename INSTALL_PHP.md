# Installing PHP on macOS

## Quick Installation Guide

### Option 1: Using Homebrew (Recommended)

```bash
# Install Homebrew if not already installed
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Install PHP
brew install php

# Verify installation
php -v

# Check PDO MySQL extension
php -m | grep pdo_mysql
```

### Option 2: Using macOS Built-in PHP (if available)

```bash
# Check if PHP is already installed
php -v

# If available, you may need to add it to PATH
export PATH="/usr/bin:$PATH"
```

### After Installation

1. **Restart Terminal**
   ```bash
   # Close and reopen terminal, or run:
   source ~/.zshrc
   ```

2. **Verify PHP is Working**
   ```bash
   php -v
   # Should show: PHP 8.x.x
   ```

3. **Start the Server**
   ```bash
   cd /Users/riteshkhande/pythonp/ARS
   php -S localhost:8000
   ```

4. **Access the Website**
   - Website: http://localhost:8000
   - Admin Panel: http://localhost:8000/admin.php

## Current Status

✅ **Frontend is running** on Python server (http://localhost:8000)
- Green theme is visible
- All sections are working
- Navigation and animations work

❌ **Backend features require PHP:**
- Contact form submission to database
- Admin panel functionality
- Message storage and retrieval

## Alternative: View Frontend Only

If you just want to preview the green theme design without backend functionality:

```bash
# Python server is already running
# Just open: http://localhost:8000
```

The contact form will show validation but won't save to database without PHP.

## Installing MySQL (After PHP)

```bash
# Install MySQL
brew install mysql

# Start MySQL service
brew services start mysql

# Secure installation (set root password)
mysql_secure_installation

# Create database
mysql -u root -p < database/schema.sql
```

## Troubleshooting

### "command not found: php"
- PHP is not installed
- Follow Option 1 above to install via Homebrew

### "command not found: brew"
- Homebrew is not installed
- Install from: https://brew.sh

### Port 8000 already in use
```bash
# Use a different port
php -S localhost:8080

# Or find and kill the process using port 8000
lsof -ti:8000 | xargs kill -9
```
