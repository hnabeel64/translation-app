# Use official PHP 8.2 FPM image
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl git unzip libpng-dev libjpeg-dev \
    libfreetype6-dev libonig-dev libzip-dev \
    && docker-php-ext-configure gd \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Laravel files
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www

# Expose port
EXPOSE 9000
