# Stage 1: Build frontend assets
FROM node:20-alpine AS frontend

WORKDIR /app
COPY package.json package-lock.json* vite.config.js tailwind.config.js postcss.config.js ./
RUN npm ci || npm install
COPY resources/ resources/
COPY public/ public/
RUN npm run build

# Stage 2: Single container with PHP-FPM + Nginx
FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

# Install system dependencies including Nginx and Supervisor
RUN apk add --no-cache \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite-dev \
    oniguruma-dev \
    libzip-dev \
    nginx \
    supervisor \
    && docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the entire codebase
COPY . /var/www/html
COPY --from=frontend /app/public/build /var/www/html/public/build

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy Nginx and Supervisor configs
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set directory permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Ensure nginx pid directory exists
RUN mkdir -p /run/nginx

# Copy Entrypoint
COPY docker/entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["entrypoint.sh"]
