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

echo "Starting original command: $@"
exec "$@"
