# Use official PHP image as a base
FROM php:8.1-fpm

# Install PHP extensions and dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www

# Copy the existing application directory to the container
COPY . .

# Install PHP dependencies
RUN composer install

# Install Node.js and npm
RUN apt-get install -y nodejs npm

# Install npm dependencies
RUN npm install

# Build the frontend assets
RUN npm run build

# Expose the port the app will run on
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
