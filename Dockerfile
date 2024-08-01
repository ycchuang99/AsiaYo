FROM php:8.3-fpm-alpine3.20

WORKDIR /var/www/html

RUN set -eux \
    && apk update \
    && apk upgrade \
    && apk add --no-cache \
        $PHPIZE_DEPS \
        zlib-dev \
        linux-headers \
        curl \
        unzip \
        openssl-dev;

COPY . .

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install --optimize-autoloader
