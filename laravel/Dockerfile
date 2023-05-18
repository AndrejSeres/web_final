# Použi oficiálny obraz PHP s Apache
FROM php:7.4-apache

# Nastav pracovný adresár na adresár s kódom Laravel projektu
WORKDIR /laravel

# Nakopíruj obsah Laravel projektu do kontajnera
COPY . /laravel

# Nainštaluj potrebné závislosti
RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install zip

# Nainštaluj Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Inštalácia Laravel/UI balíka
RUN composer require laravel/ui

# Vygenerovanie autentifikačných views
RUN php artisan ui:auth

# Nastavenie Bootstrap šablón
RUN php artisan ui bootstrap

# Inštalácia NPM balíkov
RUN npm install

# Kompilácia assets pre vývoj
RUN npm run dev

# Kompilácia assets pre produkčné prostredie
RUN npm run build

# Generovanie aplikáčneho kľúča
RUN php artisan key:generate

# Migrácia databázy
RUN php artisan migrate

# Nainštaluj PHP závislosti pomocou Composeru
RUN composer install --no-interaction --no-dev --prefer-dist

# Nastav práva pre zápis na úrovni kontajnera
RUN chown -R www-data:www-data /laravel/storage /laravel/storage/framework/cache

# Nastav Apache povolením prepisovania URL
RUN a2enmod rewrite

# Exponuj port 80
EXPOSE 80

# Spusti Apache pri štarte kontajnera
CMD ["apache2-foreground"]
