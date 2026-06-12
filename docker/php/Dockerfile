FROM composer:2 AS composer

FROM php:8.4-fpm-alpine

USER root

RUN apk add --no-cache \
        bash \
        postgresql-dev \
        oniguruma-dev \
        libzip-dev \
        php84-pecl-redis \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        linux-headers \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_pgsql \
        pgsql \
        mbstring \
        zip \
        bcmath \
        pcntl \
        sockets \
        opcache \
    && apk del .build-deps

COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

USER www-data

COPY --chown=www-data:www-data . /var/www/html
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN mkdir -p /var/www/html/storage/logs

RUN chmod 775 /var/www/html/public

RUN composer install --no-scripts --no-autoloader --prefer-dist --no-interaction
RUN composer dump-autoload --optimize --no-interaction

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
