#!/bin/sh
# Runs as ROOT (no USER directive in Dockerfile) so volume ownership can be
# repaired. Privileges are dropped via gosu before handing off to php-fpm.
set -e

# Wait for DB if DB_HOST is set (default to db service)
DB_HOST=${DB_HOST:-db}
DB_PORT=${DB_PORT:-3306}

echo "Waiting for database $DB_HOST:$DB_PORT..."
until php -r "
try {
  \$c = @fsockopen(getenv('DB_HOST') ?: 'db', getenv('DB_PORT') ?: 3306, \$e, \$m, 2);
  if (\$c) { fclose(\$c); exit(0); } exit(1);
} catch(Throwable \$e){ exit(1); }
" 2>/dev/null; do
  echo "  -> DB not ready, retrying in 2s..."
  sleep 2
done
echo "Database is reachable."

# Ensure .env exists (compose provides it via env_file; the file copy is a fallback)
if [ ! -f /var/www/.env ]; then
  echo "No .env file found, copying .env.example as fallback..."
  cp /var/www/.env.example /var/www/.env || true
fi

# Repair ownership of Laravel writable dirs (named volumes seed from image,
# but first-boot or foreign-owned volumes need normalizing). Must run as root.
mkdir -p /var/www/storage/logs /var/www/storage/framework/sessions \
  /var/www/storage/framework/views /var/www/storage/framework/cache \
  /var/www/bootstrap/cache
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Fail fast with a clear message instead of a cryptic "view does not exist" 500
if [ ! -w /var/www/bootstrap/cache ]; then
  echo "FATAL: /var/www/bootstrap/cache is not writable by www-data. Check volume ownership." >&2
  exit 1
fi
if [ ! -w /var/www/storage ]; then
  echo "FATAL: /var/www/storage is not writable by www-data. Check volume ownership." >&2
  exit 1
fi

# Clear stale framework caches FIRST - before any artisan command that boots the app.
# A broken config.php would otherwise kill migrate:status and every later step.
echo "Clearing framework caches..."
gosu www-data php /var/www/artisan config:clear || true
gosu www-data php /var/www/artisan route:clear || true
gosu www-data php /var/www/artisan view:clear || true
gosu www-data php /var/www/artisan event:clear || true

# Generate APP_KEY if missing from BOTH environment and file.
# (Compose injects the real key via env_file; the file copy is only a fallback.)
if [ -z "${APP_KEY:-}" ] && ! grep -q "^APP_KEY=.*[A-Za-z0-9]" /var/www/.env 2>/dev/null; then
  echo "Generating APP_KEY..."
  gosu www-data php /var/www/artisan key:generate --force || {
    echo "FATAL: could not generate APP_KEY. Set APP_KEY in .env (php artisan key:generate --show)." >&2
    exit 1
  }
fi

# Run migrations only if DB is accessible
if gosu www-data php /var/www/artisan migrate:status >/dev/null 2>&1; then
  echo "Running migrations..."
  gosu www-data php /var/www/artisan migrate --force || echo "Migrations failed (will retry next restart)"
else
  echo "Skipping migrations - DB not fully ready or not configured"
fi

# Cache config for production
if [ "$APP_ENV" = "production" ]; then
  echo "Caching config for production..."
  gosu www-data php /var/www/artisan config:cache || true
  gosu www-data php /var/www/artisan route:cache || true
  gosu www-data php /var/www/artisan view:cache || true
  gosu www-data php /var/www/artisan event:cache || true
fi

# Storage link (public/storage for user uploads)
gosu www-data php /var/www/artisan storage:link || true

# Hand off. The FPM master stays root on purpose: it must re-open
# error_log (/proc/self/fd/2, a root-owned pipe) and bind :9000.
# Workers run as www-data per the pool config. All artisan work above
# already ran via gosu www-data, so generated files are correctly owned.
exec "$@"
