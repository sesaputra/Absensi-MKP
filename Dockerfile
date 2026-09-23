# Stage 1: PHP dependencies
FROM composer:2.8 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    --no-autoloader
COPY . .
# Generate optimized autoloader; skip scripts that need .env/DB
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative \
    && composer install --no-dev --no-interaction --optimize-autoloader --no-scripts --prefer-dist

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

# Install system dependencies in single layer with cleanup
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    default-mysql-client \
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
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# PHP production config
COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini

# Copy application code (respects .dockerignore)
COPY . /var/www
# Copy built vendor and frontend artifacts (overrides what was copied)
COPY --from=vendor /app/vendor /var/www/vendor
COPY --from=frontend /app/public/build /var/www/public/build

# Permissions for Laravel writable dirs - run as root before switching user
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache \
    && mkdir -p /var/www/storage/logs /var/www/storage/framework/{sessions,views,cache} \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copy entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

USER www-data

EXPOSE 9000

HEALTHCHECK --interval=30s --timeout=10s --retries=3 --start-period=40s \
    CMD php -r "if(@fsockopen('127.0.0.1',9000)) exit(0); exit(1);"

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
