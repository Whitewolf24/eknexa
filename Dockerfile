# Use the official PHP 8.2 FPM image as the base
FROM php:8.2-fpm

# Install system dependencies, PHP extensions, and Nginx
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    zip \
    git \
    curl \
    nginx \
    libpq-dev \
    libcurl4-openssl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd mbstring opcache \
    && apt-get clean

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory
WORKDIR /var/www

# Copy application files into the container
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader && \
    composer clear-cache

# Set correct permissions for Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache && \
    chmod -R 755 /var/www/storage /var/www/bootstrap/cache

# Copy the Nginx configuration
COPY nginx.conf /etc/nginx/nginx.conf

# Configure Laravel cache for performance
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan storage:link   

# Expose the HTTP port
EXPOSE 80

# Start PHP-FPM and Nginx together
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
