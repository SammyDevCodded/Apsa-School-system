#!/bin/bash
echo "Starting Application..."

# Run database setup/migrations if the database is empty
echo "Checking database..."
php /var/www/html/install_prod_db.php

# Start apache in foreground
echo "Starting Apache..."
exec apache2-foreground
