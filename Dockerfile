FROM php:7.4-fpm
# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www/html/fidibo-test

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
# The MySQL client was not installed on the PHP container. So to fix my issue I added the following to my Dockerfile
#RUN apt-get install -y default-mysql-client

#RUN apt-get install -y libmysqlclient-dev

# Install extensions
RUN docker-php-ext-install pdo_mysql exif pcntl bcmath gd

#RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
RUN pecl install -o -f redis \
        &&  rm -rf /tmp/pear \
        &&  docker-php-ext-enable redis


# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


# Copy existing application directory contents
COPY . /var/www

# RUN mkdir -p $PHP_CONFIG_TEMPLATE


# Copy existing application directory permissions
# COPY --chown=www:www . /var/www
# COPY php-fpm.conf php.ini php-cli.ini ${PHP_CONFIG_TEMPLATE}/

# Change current user to www
USER 1000

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]

