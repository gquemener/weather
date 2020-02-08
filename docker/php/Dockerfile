# Install php dependencies
FROM composer as composer
ARG APP_ENV=dev
WORKDIR /root/
COPY composer.json .
COPY composer.lock .
RUN if [ "$APP_ENV" = "dev" ] ; then composer install --ignore-platform-reqs --no-scripts ; else composer install --ignore-platform-reqs --no-scripts --no-dev ; fi

# Prepare php environment
FROM php:7.4-apache as php

ARG APP_ENV=dev
ENV APP_ENV=$APP_ENV
ENV APACHE_DOCUMENT_ROOT /app/public

WORKDIR /app/
COPY --from=composer /root/vendor /app/vendor

RUN apt-get update && apt-get install -y --no-install-recommends libz-dev && rm -rf /var/lib/apt/lists/*
RUN pecl install grpc
RUN pecl install protobuf
RUN docker-php-ext-install -j$(nproc) pcntl opcache
RUN if [ "$APP_ENV" = "dev" ] ; then pecl install xdebug; fi
COPY docker/php/config/*.ini /usr/local/etc/php/conf.d/
RUN if [ "$APP_ENV" = "prod" ] ; then rm /usr/local/etc/php/conf.d/xdebug-cli.ini; fi
COPY docker/php/apache/site.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php/apache/ports.conf /etc/apache2/ports.conf
RUN a2enmod rewrite
RUN if [ "$APP_ENV" = "dev" ] ; then mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"; fi
RUN if [ "$APP_ENV" = "prod" ] ; then mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"; fi

COPY . .
RUN php bin/console cache:warmup
RUN php bin/console cache:clear
