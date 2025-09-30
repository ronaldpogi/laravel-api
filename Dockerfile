# ---------- Build stage: install PHP deps ----------
FROM composer:2.7 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-progress --optimize-autoloader
COPY . .
RUN composer dump-autoload --optimize

# ---------- Runtime stage ----------
FROM php:8.3-fpm-alpine

# System deps
RUN apk add --no-cache bash fcgi nginx-mod-http-headers-more curl\
    icu-dev libzip-dev oniguruma-dev libpng-dev git libxml2-dev

# PHP extensions
RUN docker-php-ext-install pdo_mysql bcmath intl zip pcntl \
    && docker-php-ext-enable opcache

# OPcache for performance
COPY ./docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Set workdir
WORKDIR /usr/share/nginx/html

# Copy app from build stage
COPY --from=vendor /app ./

# Ensure storage/bootstrap exist with correct perms
RUN mkdir -p storage/framework/{cache,views,sessions} \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# App entrypoint to:
# - wait for DB
# - run migrations + seeders (once, idempotent)
# - cache config/routes/views
# - start php-fpm
COPY ./docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm", "-F", "-R"]
