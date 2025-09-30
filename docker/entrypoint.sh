#!/usr/bin/env bash
set -e

# If this container is for queue worker, skip web bootstrap
if [ "${IS_WORKER}" = "true" ]; then
  # Minimal prep for worker
  php artisan config:cache || true
  php artisan queue:work --sleep=2 --tries=3 --timeout=120
  exit $?
fi

# Fix permissions every boot (safe + idempotent)
chown -R www-data:www-data /usr/share/nginx/html/storage /usr/share/nginx/html/bootstrap/cache
chmod -R 775 /usr/share/nginx/html/storage /usr/share/nginx/html/bootstrap/cache

# Wait for DB
echo "Waiting for database at ${DB_HOST:-db}:${DB_PORT:-3306}..."
until nc -z "$DB_HOST" 3306; do
  echo "⏳ Waiting for DB at $DB_HOST:3306..."
  sleep 2
done
echo 'DB ready.'

# Run migrations + seeders (safe with --force)
php artisan migrate --force || true
php artisan db:seed --force || true

# Optimize caches
php artisan event:cache || true
php artisan route:cache || true
php artisan config:cache || true
php artisan view:cache || true
php artisan optimize || true

# Start php-fpm (CMD)
exec "$@"
