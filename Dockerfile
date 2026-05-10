# syntax=docker/dockerfile:1.7
# Dev-oriented PHP-FPM image. Source code is mounted at runtime via docker-compose.
# A multi-stage production image will be added in Phase 4.

FROM php:8.4-fpm-alpine

ARG WWWUSER=1000
ARG WWWGROUP=1000

WORKDIR /var/www/html

RUN apk add --no-cache \
        bash \
        git \
        icu-data-full \
        icu-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        libzip-dev \
        oniguruma-dev \
        postgresql-dev \
        linux-headers \
        $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        gd \
        intl \
        mbstring \
        opcache \
        pcntl \
        pdo \
        pdo_pgsql \
        pgsql \
        zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del --no-cache $PHPIZE_DEPS

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN addgroup -g ${WWWGROUP} -S app \
    && adduser -u ${WWWUSER} -S -G app -s /bin/bash app

USER app

EXPOSE 9000
CMD ["php-fpm"]
