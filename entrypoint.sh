#!/bin/bash
set -e

# Clear caches for fresh environment
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Run database migration and seed
echo "Running migrations..."
php artisan migrate --seed --force || true

# Start web server on Railway PORT
PORT="${PORT:-8000}"
echo "Starting Laravel on port $PORT..."
exec php artisan serve --host=0.0.0.0 --port="$PORT"

