#!/usr/bin/env bash
set -euo pipefail

role="${IS_WORKER:-false}"

prepare_permissions() {
  chown -R www:www storage bootstrap/cache || true
  chmod -R ug+rwX storage bootstrap/cache || true
}

prepare_storage() {
  php artisan storage:link || true
}

wait_for_db() {
  echo "Waiting for database..."
  until php -r 'try { new PDO("mysql:host=\"".getenv("DB_HOST")."\";dbname=\"".getenv("DB_DATABASE")."\"", getenv("DB_USERNAME"), getenv("DB_PASSWORD")); echo "ok\n"; } catch (Throwable $e) { exit(1);}'; do
    sleep 2
  done
}

optimize() {
  php artisan config:cache || true
  php artisan route:cache || true
  php artisan view:cache || true
  php artisan optimize || true
}

run_migrations_and_seeders() {
  php artisan migrate --force
  # comment out next line if you don’t want auto seeding in prod:
  php artisan db:seed --force || true
}

if [ "$role" = "true" ]; then
  # Queue worker mode
  prepare_permissions
  wait_for_db
  optimize
  exec php artisan queue:work --sleep=1 --tries=3 --max-time=3600
else
  # Web app mode
  prepare_permissions
  wait_for_db
  run_migrations_and_seeders
  prepare_storage
  optimize
  exec "$@"
fi
