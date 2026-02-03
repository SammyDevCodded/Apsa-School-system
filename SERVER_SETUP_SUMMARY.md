# Server Setup Summary

## What We've Done

1. **Created Router Script**: We created a [router.php](file:///c%3A/wamp64/www/f2/public/router.php) file in the public directory to handle URL rewriting for the PHP built-in server, since it doesn't support .htaccess files.

2. **Updated Configuration**: We updated the [config.php](file:///c%3A/wamp64/www/f2/config/config.php) file to use `http://localhost:8000` as the application URL.

3. **Created Batch Files**: We created two batch files to make it easier to start the server:
   - [start_server.bat](file:///c%3A/wamp64/www/f2/start_server.bat): Starts the server immediately
   - [start_dev.bat](file:///c%3A/wamp64/www/f2/start_dev.bat): Initializes the database and starts the server

4. **Created Documentation**: We created README files with instructions on how to use the PHP built-in server.

## How to Use the PHP Built-in Server

### Method 1: Using Batch Files (Recommended)
1. Double-click on [start_server.bat](file:///c%3A/wamp64/www/f2/start_server.bat) to start the server
2. Open your browser and go to: http://localhost:8000

### Method 2: Command Line
1. Open a terminal/command prompt
2. Navigate to the project root directory (`c:\wamp64\www\f2`)
3. Run the following command:
   ```
   php -S localhost:8000 -t public/ public/router.php
   ```
4. Open your browser and go to: http://localhost:8000

## Default Login Credentials

- **Username**: admin
- **Password**: admin123

Please change the password after your first login.

## Troubleshooting

1. **Port Conflicts**: If port 8000 is already in use, try a different port:
   ```
   php -S localhost:8080 -t public/ public/router.php
   ```

2. **Database Issues**: Make sure your MySQL/MariaDB server is running and the database is initialized:
   ```
   php init_db.php
   ```

3. **Connection Test**: You can test the database connection with:
   ```
   php test_connection.php
   ```