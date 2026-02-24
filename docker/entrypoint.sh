#!/bin/sh

echo "=== Lexiscope Container Starting ==="

# Wait for the database to be ready
echo "Waiting for database..."
sleep 5

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Optimize the framework
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ensure correct permissions
echo "Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create supervisor log directory
mkdir -p /var/log/supervisor

echo "=== Starting Nginx + PHP-FPM via Supervisord ==="
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
