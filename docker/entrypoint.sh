#!/usr/bin/env bash
set -e

# Always ensure storage and cache directories exist with correct permissions
ensure_storage_dirs() {
  echo "📁 Ensuring storage and cache directories exist..."
  mkdir -p storage/framework/cache
  mkdir -p storage/framework/views
  mkdir -p storage/framework/sessions
  mkdir -p bootstrap/cache

  chown -R www-data:www-data storage bootstrap/cache
  chmod -R 775 storage bootstrap/cache
}

ensure_storage_dirs

# If this container is a queue worker
if [ "${IS_WORKER}" = "true" ]; then
  echo "🚀 Starting Laravel Queue Worker..."

  # ✅ Wait for DB before starting queue worker
  DB_HOST="${DB_HOST:-db}"
  DB_PORT="${DB_PORT:-3306}"
  echo "⏳ Waiting for database at ${DB_HOST}:${DB_PORT}..."
  until nc -z "${DB_HOST}" "${DB_PORT}"; do
    echo "   Still waiting for DB..."
    sleep 2
  done
  echo "✅ Database is ready."

  # ✅ Only run the worker — no caches, no migrations
  exec php artisan queue:work --sleep=2 --tries=3 --timeout=120
fi

# --- If NOT a worker, continue bootstrapping PHP-FPM app container ---

echo "🔧 Bootstrapping Laravel app container..."

# Wait for DB to be ready
DB_HOST="${DB_HOST:-db}"
DB_PORT="${DB_PORT:-3306}"
echo "⏳ Waiting for database at ${DB_HOST}:${DB_PORT}..."
until nc -z "${DB_HOST}" "${DB_PORT}"; do
  echo "   Still waiting for DB..."
  sleep 2
done
echo "✅ Database is ready."

# Run migrations + seeders only on main app container
echo "📦 Running migrations..."
php artisan migrate --force || true

echo "🌱 Running seeders..."
php artisan db:seed --force || true

# Optimize caches
echo "⚡ Optimizing Laravel caches..."
php artisan event:cache || true
php artisan route:cache || true
php artisan config:cache || true
php artisan view:cache || true
php artisan optimize || true

# Hand off to php-fpm (CMD)
echo "🚀 Starting PHP-FPM..."
exec "$@"
