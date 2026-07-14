FROM php:8.2.29-zts-alpine3.21

RUN apk update && apk add --no-cache \
    curl \
    zip \
    unzip \
    espeak \
    postgresql-dev

RUN docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000
