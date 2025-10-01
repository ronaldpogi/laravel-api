# ---------- PHP-FPM (Alpine only) ----------
FROM php:8.3-fpm-alpine

# System deps for pdo_pgsql (Alpine needs postgresql-dev)
RUN apk add --no-cache \
      postgresql-dev postgresql-client \
      libzip-dev libpng-dev bash git

# PHP extensions (pdo_pgsql is the key one)
RUN set -eux; \
    docker-php-ext-install pdo_pgsql bcmath gd zip

# Redis extension via PECL (needs phpize toolchain)
# $PHPIZE_DEPS is provided by the official PHP images (autoconf, make, g++, etc.)
RUN set -eux; \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
 && pecl install redis \
 && docker-php-ext-enable redis \
 && apk del .build-deps

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /usr/share/nginx/html

# Composer first (better caching)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# App source
COPY . .

# Storage perms
RUN mkdir -p storage/framework/{cache,views,sessions} \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# Entrypoint (make sure this file exists, is LF, and executable)
COPY ./php-fpm-entrypoint /usr/local/bin/php-entrypoint
RUN chmod a+x /usr/local/bin/php-entrypoint

EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/php-entrypoint"]
CMD ["php-fpm"]
