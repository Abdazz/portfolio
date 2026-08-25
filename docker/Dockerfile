# syntax=docker/dockerfile:1.7
# Multi-stage build.
#   --target dev      → dev image: source mounted at runtime, no baked assets
#   --target runtime  → production image: composer + Vite assets baked in

# ─── shared base ─────────────────────────────────────────────────────────────
FROM php:8.4-fpm-alpine AS base

ARG WWWUSER=1000
ARG WWWGROUP=1000

# renovate: datasource=repology depName=alpine_3_21/chromium versioning=loose
ARG CHROMIUM_VERSION=151.0.7922.173-r0

# renovate: datasource=github-releases depName=phpredis/phpredis
ARG PHPREDIS_VERSION=6.1.0

WORKDIR /var/www/html

RUN apk add --no-cache \
        bash \
        chromium=${CHROMIUM_VERSION} \
        git \
        icu-data-full \
        icu-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        libzip-dev \
        nodejs \
        npm \
        oniguruma-dev \
        postgresql-client \
        postgresql-dev \
        linux-headers \
        $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        exif \
        gd \
        intl \
        mbstring \
        opcache \
        pcntl \
        pdo \
        pdo_pgsql \
        pgsql \
        sockets \
        zip \
    && pecl install redis-${PHPREDIS_VERSION} \
    && docker-php-ext-enable redis \
    && apk del --no-cache $PHPIZE_DEPS

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN addgroup -g ${WWWGROUP} -S app \
    && adduser -u ${WWWUSER} -S -G app -s /bin/bash app

# Official Playwright browser downloads don't ship musl/Alpine-compatible builds,
# and .npmrc sets ignore-scripts=true so `npm install` never runs Playwright's
# postinstall downloader anyway. Point Playwright's expected browser cache paths
# at the Alpine `chromium` package instead. The revision number below (1223) is
# tied to the installed playwright/playwright-core version (see package.json);
# bumping that package requires updating it here too, or pest-plugin-browser's
# `visit()`/`evaluate()`/`screenshot()` calls will fail with a misleading
# "Playwright is outdated" error pointing at a missing chrome-headless-shell/chrome binary.
RUN PW_REVISION=1223 \
    && PW_CACHE=/home/app/.cache/ms-playwright \
    && mkdir -p "$PW_CACHE/chromium-$PW_REVISION/chrome-linux64" \
    && ln -s /usr/bin/chromium "$PW_CACHE/chromium-$PW_REVISION/chrome-linux64/chrome" \
    && touch "$PW_CACHE/chromium-$PW_REVISION/INSTALLATION_COMPLETE" \
    && mkdir -p "$PW_CACHE/chromium_headless_shell-$PW_REVISION/chrome-headless-shell-linux64" \
    && ln -s /usr/bin/chromium "$PW_CACHE/chromium_headless_shell-$PW_REVISION/chrome-headless-shell-linux64/chrome-headless-shell" \
    && touch "$PW_CACHE/chromium_headless_shell-$PW_REVISION/INSTALLATION_COMPLETE" \
    && chown -R app:app /home/app/.cache

# ─── dev ─────────────────────────────────────────────────────────────────────
FROM base AS dev

# Source is mounted at runtime via docker-compose. Nothing baked in.
USER app
EXPOSE 9000
CMD ["php-fpm"]

# ─── builder (bakes composer deps + Vite assets) ─────────────────────────────
FROM base AS builder

COPY --chown=app:app . .

USER app

RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

RUN npm ci && npm run build && rm -rf node_modules

# ─── runtime (production PHP-FPM) ────────────────────────────────────────────
FROM base AS runtime

COPY --chown=app:app --from=builder /var/www/html /var/www/html

COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

USER app

EXPOSE 9000
CMD ["php-fpm"]
