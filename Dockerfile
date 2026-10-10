# Stage 1: PHP dependencies
FROM composer:2.8 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
# Install deps from the lock file first so this layer only busts when the lock changes
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    --no-autoloader
COPY . .
# Optimized authoritative autoloader for the code copied above
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative

# Stage 2: Frontend build (Vite + Tailwind)
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json vite.config.js ./
RUN npm ci
COPY resources ./resources
COPY public ./public
RUN npm run build

# Stage 3: Production PHP-FPM
FROM php:8.3.14-fpm

WORKDIR /var/www

# System deps, single layer:
#  - *-dev headers are build-only and purged below IN THIS LAYER (purging from a
#    later layer would keep their bytes in this one)
#  - runtime libs are named explicitly so apt marks them manual and
#    --auto-remove keeps them (otherwise they vanish as orphans of the -dev pkgs)
#  - no git (composer runs in the vendor stage), no curl (ships with base image),
#    no default-mysql-client (entrypoint waits for MySQL via PHP fsockopen)
#  - build toolchain ($PHPIZE_DEPS) already ships with the official php image
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    libpng16-16 \
    libjpeg62-turbo \
    libfreetype6 \
    libonig5 \
    libzip4 \
    libicu72 \
    libxml2 \
    gosu \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache \
    && gosu nobody true \
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libonig-dev \
        libxml2-dev \
        libzip-dev \
        libicu-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# PHP production config
COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini

# Copy application code as www-data (respects .dockerignore). --chown here means
# no chown -R over the whole tree later, which would duplicate every vendor file
# into an extra layer (+50MB)
COPY --chown=www-data:www-data . /var/www
# Built vendor and frontend artifacts (override what the context copy brought in)
COPY --chown=www-data:www-data --from=vendor /app/vendor /var/www/vendor
COPY --chown=www-data:www-data --from=frontend /app/public/build /var/www/public/build

# Writable dirs only: entrypoint repairs named-volume ownership on every boot
RUN rm -f /var/www/bootstrap/cache/*.php \
    && mkdir -p \
        /var/www/storage/logs \
        /var/www/storage/framework/sessions \
        /var/www/storage/framework/views \
        /var/www/storage/framework/cache \
        /var/www/bootstrap/cache \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Copy entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# NOTE: intentionally no USER directive. The entrypoint starts as root so it can
# repair named-volume ownership, then drops privileges via gosu before exec.
# Runtime process (php-fpm) still runs as www-data.

EXPOSE 9000

HEALTHCHECK --interval=30s --timeout=10s --retries=3 --start-period=40s \
    CMD php -r "if(@fsockopen('127.0.0.1',9000)) exit(0); exit(1);"

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
