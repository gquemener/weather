# Install php dependencies
FROM composer as composer
ARG APP_ENV=dev
WORKDIR /root/
COPY composer.json .
COPY composer.lock .
RUN if [ "$APP_ENV" = "dev" ] ; then composer install --no-scripts ; else composer install --no-scripts --no-dev ; fi

# Prepare php environment
FROM php:7.4-cli-alpine3.10 as php

ARG APP_ENV=dev
ENV APP_ENV=$APP_ENV

WORKDIR /app/
COPY --from=composer /root/vendor /app/vendor

ENV PHPIZE_DEPS \
        autoconf \
        cmake \
        file \
        g++ \
        gcc \
        libc-dev \
        pcre-dev \
        make \
        git \
        pkgconf \
        re2c

RUN apk add --no-cache --virtual .persistent-deps postgresql-dev
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS
RUN docker-php-ext-configure pdo_pgsql --with-pgsql
RUN docker-php-ext-install -j$(nproc) pdo_pgsql pcntl opcache
RUN if [ "$APP_ENV" = "dev" ] ; then pecl -d preferred_state=beta install xdebug; fi
RUN apk del .build-deps && rm -rf /tmp/*
COPY docker/php/config/*.ini /usr/local/etc/php/conf.d/
RUN if [ "$APP_ENV" = "prod" ] ; then rm /usr/local/etc/php/conf.d/xdebug-cli.ini; fi
COPY . .
RUN php bin/console cache:warmup
RUN php bin/console cache:clear

ENTRYPOINT docker/php/server-entrypoint.sh