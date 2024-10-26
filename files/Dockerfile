# Use the official PHP image from Docker Hub
FROM php:7.4-apache

# Install dependencies and PHP extensions
RUN apt-get update && \
    apt-get install -y \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libzip-dev \
        unzip \
        && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd mysqli pdo_mysql zip

COPY files/ /var/www/html

# Set working directory
WORKDIR /var/www/html



# Create uploads directory and set permissions
RUN mkdir -p uploads && \
    chown -R www-data:www-data uploads && \
    chmod -R 755 uploads


RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
