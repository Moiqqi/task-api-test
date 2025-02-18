# Use PHP image with Apache
FROM php:8.2-apache

# Set the working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear the package cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable Apache modules as root
RUN a2enmod rewrite

# Add ServerName directive to suppress FQDN warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copy the Apache configuration file. You should first copy 000-default.conf.example
# into a new created file docker/apache/000-default.conf. (don't commit it)
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable site configuration
RUN a2ensite 000-default.conf

# Create a non-root user and switch to it
RUN useradd -m laravel && chown -R laravel:laravel /var/www/html
USER laravel

# Copy the Laravel application files
COPY --chown=laravel:laravel . .

# Install Composer dependencies
RUN composer install --optimize-autoloader

# Set permissions for Laravel storage and bootstrap cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Fix Apache permissions (run as root)
USER root
RUN chown -R www-data:www-data /var/www/html/public
RUN chmod -R 755 /var/www/html/public
USER laravel

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
