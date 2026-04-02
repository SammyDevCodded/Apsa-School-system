FROM php:7.4-apache

# Install dependencies and extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql gd zip

# Enable Apache mod_rewrite and fix MPM conflict
RUN a2dismod mpm_event mpm_worker mpm_itk 2>/dev/null || true && \
    a2enmod mpm_prefork rewrite && \
    a2dismod mpm_event mpm_worker mpm_itk 2>/dev/null || true

# Update Apache configuration to point to public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Verify only one MPM is enabled
RUN apache2ctl -M 2>&1 | grep -c "mpm_" | grep -q "^1$" || \
    (echo "ERROR: Multiple MPM modules detected" && exit 1)

# Set working directory
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy application files (ignoring files in .dockerignore)
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Make storage directories writable
RUN mkdir -p storage/uploads storage/backups storage/logs storage/framework/views storage/framework/cache && \
    chmod -R 777 storage

# Create a startup script
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]
