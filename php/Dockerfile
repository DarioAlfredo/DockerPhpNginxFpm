# php/Dockerfile
FROM php:8.2-fpm-alpine

# Install build dependencies and required libs
RUN apk add --no-cache \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    zip \
    pkgconfig \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        gd \
        pdo_mysql \
        mysqli \
        bcmath \
        opcache \
        zip

# Expose default PHP-FPM port
EXPOSE 9000

CMD ["php-fpm"]
