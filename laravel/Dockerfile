FROM php:8.1-apache

RUN apt update

RUN apt upgrade -y

RUN docker-php-ext-install pdo_mysql

RUN apt install -y git

RUN apt install -y zip unzip

RUN curl -sL https://deb.nodesource.com/setup_16.x | bash 

RUN apt install -y nodejs

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD . laravel

WORKDIR /var/www/html/laravel

RUN composer require laravel/ui

RUN php artisan ui:auth --no-interaction

RUN php artisan ui bootstrap --no-interaction

RUN npm install

RUN npm run build

RUN php artisan key:generate

# RUN php artisan migrate

# EXPOSE 8001

# CMD ["php", "artisan", "serve", "--port=8001"]