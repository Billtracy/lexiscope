#!/bin/sh

# Give the database some time to start up
echo "Waiting 5 seconds for the database..."
sleep 5

echo "Running migrations..."
php artisan migrate --force

echo "Optimizing framework..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Setting directory permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "Starting original command: $@"
exec "$@"
