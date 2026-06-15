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

# Instalar dependencias Node (caché de capas)
COPY package.json package-lock.json ./
RUN npm ci

# Copiar la aplicación completa
COPY . .

# Post-install scripts y assets
RUN composer run-script post-autoload-dump 2>/dev/null || true
RUN npm run build

# Permisos Laravel
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD sh -c "php artisan storage:link --quiet && \
    php artisan migrate --force && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"
