FROM php:8.0-fpm

# Make future work directory as root
RUN mkdir -p /var/www/

# Add user for laravel application
RUN groupadd www
RUN useradd -ms /bin/bash -g www www

# Change owner of work directory
RUN chown www /var/www

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    git \
    libsasl2-dev \
    libssl-dev \
    python2-minimal \
    zlib1g-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    libzip-dev \
    curl \
    libpq-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
RUN docker-php-ext-install opcache

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www

# Install dependencies
RUN composer install

# Volume to use
VOLUME ["/var/www"]

# Set entrypoint permissions
RUN chmod +x ./start_up.sh

# Expose port 9000 and start php-fpm server
EXPOSE 9000
ENTRYPOINT ["php-fpm"]