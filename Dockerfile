FROM php:7.0-apache

RUN mkdir /var/wwww/html/MyScheduleLite/

COPY . /var/www/html/MyScheduleLite/

RUN docker-php-ext-install pdo pdo_mysql mysqli