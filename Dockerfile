FROM php:8.4-fpm-alpine

# Dependencias del sistema
RUN apk add --no-cache \
    git curl zip unzip libzip-dev \
    libpng-dev oniguruma-dev libxml2-dev \
    nodejs npm

# Extensiones PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Instalar dependencias PHP (caché de capas)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copiar la aplicación completa (incluye public/build pre-compilado)
COPY . .

# Post-install scripts (assets ya vienen compilados del repo)
RUN composer run-script post-autoload-dump 2>/dev/null || true

# Permisos Laravel
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD sh -c "php artisan storage:link --quiet && \
    php artisan migrate --force && \
    php artisan db:seed --force && \
    php artisan config:cache && \
    php artisan route:cache && \
    php -S 0.0.0.0:${PORT:-8000} -t /var/www/html/public /var/www/html/server.php"
