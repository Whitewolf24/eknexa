FROM php:8.2-fpm

# Install necessary system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    curl \
    nginx \
    && apt-get clean

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory
WORKDIR /var/www

# Copy your application files into the container
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Install PostgreSQL PDO extension
RUN apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql

# Set up Nginx configuration
COPY nginx.conf /etc/nginx/nginx.conf

# Ensure nginx and php-fpm have the correct permissions
RUN chown -R www-data:www-data /var/www && \
    chmod -R 755 /var/www && \
    chown -R www-data:www-data /etc/nginx /var/log/nginx

# Expose HTTP port
EXPOSE 80

# Start Nginx and PHP-FPM in the foreground
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]