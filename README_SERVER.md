# Futuristic School Management ERP - Server Setup

## Using PHP Built-in Server (Recommended)

Since you're experiencing issues with WAMP, you can use PHP's built-in development server instead.

### Prerequisites
- PHP 7.4.33 installed and available in your PATH
- MySQL/MariaDB server running

### Quick Start

1. **Initialize the database** (run once):
   ```
   php init_db.php
   ```

2. **Start the development server**:
   ```
   php -S localhost:8000 -t public/ public/router.php
   ```

3. **Access the application**:
   Open your browser and go to: http://localhost:8000

### Alternative Methods

#### Using the Batch Files

1. **Quick Start**:
   Double-click on `start_server.bat` to start the server immediately.

2. **Development Setup**:
   Double-click on `start_dev.bat` to initialize the database and start the server.

#### Manual Command Line

From the project root directory:
```bash
php -S localhost:8000 -t public/ public/router.php
```

### Default Login Credentials

- **Username**: admin
- **Password**: admin123

Please change the password after your first login.

### Troubleshooting

1. **Port already in use**:
   If port 8000 is already in use, try a different port:
   ```bash
   php -S localhost:8080 -t public/ public/router.php
   ```

2. **Database Connection Issues**:
   - Ensure MySQL/MariaDB is running
   - Check your database credentials in `config/config.php`
   - Run `php test_connection.php` to diagnose connection issues

3. **Permission Issues**:
   Make sure PHP has read/write permissions to the project directory.