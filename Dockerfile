FROM php:8.2-apache

RUN a2enmod rewrite

# GD con soporte freetype para generar imágenes OG dinámicas
RUN apt-get update && apt-get install -y \
    libpng-dev libfreetype6-dev libjpeg62-turbo-dev fonts-dejavu-core \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY apache.conf /etc/apache2/sites-available/000-default.conf
