#!/usr/bin/env bash
set -e

# ==============================
# 🔧 Storage Directory Setup
# ==============================
ensure_storage_dirs() {
  echo "📁 Ensuring storage and cache directories exist safely..."

  # Use || true in case volume already contains these folders/files
  mkdir -p storage/framework/cache     || true
  mkdir -p storage/framework/views     || true
  mkdir -p storage/framework/sessions  || true
  mkdir -p bootstrap/cache             || true

  # Fix permissions
  chown -R www-data:www-data storage bootstrap/cache || true
  chmod -R 775 storage bootstrap/cache || true
}

ensure_storage_dirs

# ==============================
# ⏳ Wait for Database
# ==============================
wait_for_db() {
  DB_HOST="${DB_HOST:-db}"
  DB_PORT="${DB_PORT:-3306}"

  echo "⏳ Waiting for database at ${DB_HOST}:${DB_PORT}..."
  until nc -z "${DB_HOST}" "${DB_PORT}"; do
    echo "   Still waiting for DB..."
    sleep 2
  done
  echo "✅ Database is ready."
}

# ==============================
# 🧵 Queue Worker Logic
# ==============================
if [ "${IS_WORKER}" = "true" ]; then
  echo "🚀 Starting Laravel Queue Worker..."
  wait_for_db
  exec php artisan queue:work --sleep=2 --tries=3 --timeout=120
fi

# ==============================
# 🚀 Main App Container Logic
# ==============================
echo "🔧 Bootstrapping Laravel app container..."
wait_for_db

# Safely attempt migrations only if table exists and not locked
echo "📦 Running migrations..."
php artisan migrate --force || true

echo "🌱 Running seeders..."
php artisan db:seed --force || true

# ==============================
# ⚡ Cache Optimization
# ==============================
echo "🧹 Cleaning old caches..."
php artisan optimize:clear || true

echo "⚡ Optimizing Laravel caches..."
php artisan event:cache || true
php artisan route:cache || true
php artisan config:cache || true
php artisan view:cache || true

# ==============================
# ✅ Start PHP-FPM
# ==============================
echo "🚀 Starting PHP-FPM..."
exec "$@"
