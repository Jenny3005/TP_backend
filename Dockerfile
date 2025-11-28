FROM php:8.2-apache

WORKDIR /var/www/html

# Installer les dépendances système POUR POSTGRESQL
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier d'abord seulement composer.json pour cache Docker
COPY composer.json composer.lock ./

# Installer les dépendances (cache cette étape)
RUN composer install --no-dev --no-scripts --no-autoloader

# Copier tout le code
COPY . .

# Générer l'autoload et configurer
RUN composer dump-autoload --optimize
RUN chmod -R 755 storage bootstrap/cache

EXPOSE 80
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
