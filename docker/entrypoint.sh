#!/bin/sh
set -e

echo "Waiting for database..."
until php artisan db:show --no-interaction 2>/dev/null; do
    sleep 2
done

echo "Running migrations..."
php artisan migrate --force --no-interaction

echo "Seeding database..."
php artisan db:seed --force --no-interaction

exec "$@"
