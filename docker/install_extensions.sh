#!/bin/bash

# Install PHP extensions using docker-php-ext-install
docker-php-ext-install pdo_mysql mbstring zip exif pcntl gd gmp bcmath

# Install PECL extensions
pecl install imagick redis
if [ "$1" != "production" ]; then
    pecl install xdebug
fi

# Enable PECL extensions
docker-php-ext-enable imagick redis
if [ "$1" != "production" ]; then
    docker-php-ext-enable xdebug
fi
