FROM php:8.4-fpm AS builder

# Set environment variable for APP_ENV
ARG APP_ENV=local
ARG UID=1000
ARG GID=1000
ENV APP_ENV=$APP_ENV

RUN groupadd -g ${GID} planexx && useradd -m -u ${UID} -g ${GID} planexx

# Set working directory
WORKDIR /var/www

RUN uname -a
RUN apt-get update && apt-get install -y \
    build-essential \
    curl \
    git \
    gifsicle \
    jpegoptim \
    libgmp-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libonig-dev \
    libpng-dev \
    libzip-dev \
    libwebp-dev \
    libxpm-dev \
    libmagickwand-dev \
    locales \
    optipng \
    pngquant \
    libmpc-dev \
    unzip \
    vim \
    ffmpeg \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY ./docker/install_extensions.sh /var/www
RUN chmod +x install_extensions.sh && \
    docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm && \
    sh install_extensions.sh $APP_ENV

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir -p /.composer

# Runtime stage
FROM php:8.4-fpm

ARG APP_ENV=local
ARG UID=1000
ARG GID=1000

# Create user
RUN groupadd -g ${GID} planexx && useradd -m -u ${UID} -g ${GID} planexx

# Install runtime dependencies and git
RUN apt-get update && apt-get install -y \
    libfreetype6 \
    libjpeg62-turbo \
    libpng16-16 \
    libwebp7 \
    libxpm4 \
    libmagickwand-7.q16-10 \
    libgmp10 \
    libonig5 \
    libzip5 \
    git \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copy extensions and binaries from builder
COPY --from=builder /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/
COPY --from=builder /usr/local/bin/composer /usr/local/bin/composer
COPY --from=builder /.composer /.composer

# Add Xdebug configuration
COPY ./docker/php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN if [ "$APP_ENV" = "production" ]; then rm /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; fi

# Git global config
RUN git config --global --replace-all safe.directory /var/www

# Start php-fpm server
CMD ["php-fpm"]
