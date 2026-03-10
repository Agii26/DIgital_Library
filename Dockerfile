FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libzip-dev \
    libxml2-dev libonig-dev nodejs npm \
    && docker-php-ext-install gd zip pdo pdo_mysql mbstring xml ctype fileinfo \
    && apt-get clean

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer update --no-interaction --prefer-dist --optimize-autoloader
RUN npm ci && npm run build

COPY start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8080

CMD ["/start.sh"]