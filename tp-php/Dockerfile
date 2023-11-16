FROM php:8.1-apache

# Updates
# Installation de Zip, unzip et Git
RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        zip \
        unzip \
        git \
    && docker-php-ext-install zip

# Installation de Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Copie du code source dans le conteneur
COPY . /var/www/html/

# Installation des dépendances avec Composer
WORKDIR /var/www/html/
# RUN composer update --no-interaction

# Configuration des permissions pour le dossier de stockage
RUN chmod 777 /var/www/html/storage

EXPOSE 80
