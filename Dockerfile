FROM php:8.3-fpm

# Install required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    curl \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/* \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy the Symfony application code into the container
COPY . /var/www/html

# Set the correct permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 9000 to communicate with Nginx
EXPOSE 9000

# Start the PHP-FPM service
CMD ["php-fpm"]
