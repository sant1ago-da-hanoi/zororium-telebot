FROM ubuntu:latest
FROM php:8.1-apache

RUN docker-php-ext-install pdo pdo_mysql sockets mysqli
RUN curl -sS https://getcomposer.org/installer | php -- \
     --install-dir=/usr/local/bin --filename=composer

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .
RUN composer install
# RUN php artisan short-schedule:run >> /dev/null 2>&1