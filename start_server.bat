@echo off
echo Starting PHP Built-in Server for Futuristic School Management ERP
echo ===============================================================
echo.
echo Server will be available at: http://localhost:8000
echo Press Ctrl+C to stop the server
echo.
echo Make sure you're in the project root directory
echo.
php -S localhost:8000 -t public/ public/router.php