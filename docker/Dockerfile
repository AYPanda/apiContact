FROM php:7.3.10-apache

RUN ln -sf /usr/share/zoneinfo/Europe/Moscow /etc/localtime

RUN apt-get update \
&& apt-get install -y git \
                      cron \
                      zlib1g-dev \
                      libpq-dev \
                      libzip-dev \
&& pecl install xdebug \
&& docker-php-ext-enable xdebug \
&& docker-php-ext-install pdo pdo_mysql zip bcmath \
&& curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer


WORKDIR /var/www/html

#COPY /api/composer.json .
COPY docker/php.ini /usr/local/etc/php/php.ini

RUN usermod -u 1000 www-data
RUN a2enmod rewrite
#RUN composer install --no-scripts --no-autoloader

EXPOSE 9000