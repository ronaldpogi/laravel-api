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

# (Optional but recommended) OPcache for prod
RUN docker-php-ext-install opcache
# You can also COPY a tuned opcache.ini if you have one:
# COPY ./docker/php/conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /usr/share/nginx/html

# Composer first (better caching)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# App source
COPY . .

# Storage perms (writable for Laravel)
RUN set -eux; \
  mkdir -p storage/framework/cache storage/framework/views storage/framework/sessions; \
  chown -R www-data:www-data storage bootstrap/cache; \
  chmod -R 775 storage bootstrap/cache

# PUBLIC perms (readable by Nginx; dirs 755, files 644)
# Avoid making everything 755—files shouldn't be executable.
RUN set -eux; \
  if [ -d public ]; then \
    find public -type d -exec chmod 755 {} +; \
    find public -type f -exec chmod 644 {} +; \
  fi

# Ensure project tree owned by www-data (safe for PHP-FPM writes in cache/storage)
RUN chown -R www-data:www-data /usr/share/nginx/html

# Entrypoint (make sure this file exists, is LF, and executable)
COPY ./php-fpm-entrypoint /usr/local/bin/php-entrypoint
RUN chmod a+x /usr/local/bin/php-entrypoint

EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/php-entrypoint"]
CMD ["php-fpm"]
