FROM php:8.1-fpm

ADD php.ini /usr/local/etc/php/conf.d/zzz-php.ini

RUN apt-get update && apt-get install -y libmcrypt-dev libonig-dev \
        curl \
        nano \
        zip libzip-dev \
        default-mysql-client

RUN docker-php-ext-install \
        mbstring \
        pdo_mysql \
        zip \
        iconv

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

EXPOSE 9000

WORKDIR /srv/www
