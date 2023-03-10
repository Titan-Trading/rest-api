FROM php:8.0-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Make future work directory as root
RUN mkdir -p /var/www/

# Change owner of work directory
# RUN chown $user /var/www

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

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
    libpq-dev \
    libmcrypt-dev \
    librdkafka-dev \
    openssl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN pecl install rdkafka

# Install extensions
RUN docker-php-ext-install pdo_mysql zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
RUN docker-php-ext-install opcache
RUN docker-php-ext-enable rdkafka

# Install composer
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . /var/www

# Add user for laravel application
RUN groupadd $user
RUN useradd -g $user -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user && \
    chown -R $user:$user /var/www

# Copy existing application directory permissions
COPY --chown=$user . /var/www

# change file permissions
RUN chmod 777 /var/www

# Set working directory
WORKDIR /var/www

# Set entrypoint permissions
RUN chmod a+x ./start_up.sh

# Change current user to what the setting in docker-compose.yml
USER $user

# Install dependencies
RUN composer install

# Expose port 9000 and start php-fpm server
EXPOSE 9000
# ENTRYPOINT ["./start_up.sh"]
ENTRYPOINT ["php-fpm"]