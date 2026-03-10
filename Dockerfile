FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libzip-dev \
    libxml2-dev libonig-dev nodejs npm \
    && docker-php-ext-install gd zip pdo pdo_mysql mbstring xml ctype fileinfo \
    && apt-get clean

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader
RUN npm ci && npm run build

EXPOSE 8080

CMD php artisan migrate --force && \
    php artisan db:seed --force && \
    php artisan config:clear && \
    php artisan config:cache && \
    php artisan route:clear && \
    php artisan view:clear && \
    php artisan storage:link 2>/dev/null || true && \
    php -S 0.0.0.0:8080 public/server.php