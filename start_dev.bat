@echo off
echo Futuristic School Management ERP - Development Setup
echo =====================================================
echo.

echo 1. Initializing database...
php init_db.php
echo.

echo 2. Starting PHP Built-in Server...
echo Server will be available at: http://localhost:8000
echo Press Ctrl+C to stop the server
echo.
php -S localhost:8000 -t public/ public/router.php

pause