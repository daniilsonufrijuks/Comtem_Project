FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    curl \
    git \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    apache2 \
    libapache2-mod-fcgid && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd && \
    docker-php-ext-install pdo pdo_mysql && \
    docker-php-ext-install bcmath

# Add NodeSource repository for Node.js 18.x
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash -

# Install Node.js and npm
RUN apt-get install -y nodejs

# Install Node.js and npm
#RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
 #   apt-get install -y nodejs \

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www

# Copy the code into the container
COPY . .

# Install dependencies
RUN composer install
RUN npm install
RUN npm run build

# Configure Apache to use PHP-FPM
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Enable required Apache modules
RUN a2enmod actions fcgid alias proxy_fcgi

# Expose the port
EXPOSE 80

# Start Apache and PHP-FPM
CMD ["sh", "-c", "service apache2 start && php-fpm"]
