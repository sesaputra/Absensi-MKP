#!/bin/sh
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

# Ensure .env exists and APP_KEY is set
if [ ! -f /var/www/.env ]; then
  echo "No .env found, copying .env.example..."
  cp /var/www/.env.example /var/www/.env || true
fi

# Fix permissions at runtime (handles bind-mount overrides)
mkdir -p /var/www/storage/logs /var/www/storage/framework/{sessions,views,cache} /var/www/bootstrap/cache
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true

# Generate APP_KEY if empty
if ! grep -q "^APP_KEY=.*[A-Za-z0-9]" /var/www/.env 2>/dev/null; then
  echo "Generating APP_KEY..."
  php /var/www/artisan key:generate --force || true
fi

# Run migrations and optimizations only if DB is accessible
if php /var/www/artisan migrate:status >/dev/null 2>&1; then
  echo "Running migrations..."
  php /var/www/artisan migrate --force || echo "Migrations failed (will retry next restart)"
else
  echo "Skipping migrations - DB not fully ready or not configured"
fi

echo "Clearing and caching config..."
php /var/www/artisan config:clear || true
php /var/www/artisan route:clear || true
php /var/www/artisan view:clear || true
# Only cache in production
if [ "$APP_ENV" = "production" ]; then
  php /var/www/artisan config:cache || true
  php /var/www/artisan route:cache || true
  php /var/www/artisan view:cache || true
fi

# Storage link
php /var/www/artisan storage:link || true

exec "$@"
