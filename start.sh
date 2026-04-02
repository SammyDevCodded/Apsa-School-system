#!/bin/bash
echo "Starting Application..."

# 1. Forcefully eradicate any conflicting MPMs loaded by Railway caching
echo "Configuring Apache MPM..."
rm -f /etc/apache2/mods-enabled/mpm_event.load /etc/apache2/mods-enabled/mpm_event.conf
rm -f /etc/apache2/mods-enabled/mpm_worker.load /etc/apache2/mods-enabled/mpm_worker.conf
a2dismod mpm_event mpm_worker 2>/dev/null || true
a2enmod mpm_prefork 2>/dev/null || true

# 2. Bind to Railway's dynamic PORT
if [ -n "$PORT" ]; then
    echo "Binding Apache to Railway port $PORT..."
    sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf
    sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/*.conf
fi

# 3. Check if database exists and run migrations
echo "Checking database..."
php /var/www/html/install_prod_db.php || true

# 4. Start apache in foreground
echo "Starting Apache..."
source /etc/apache2/envvars
exec apache2 -D FOREGROUND
