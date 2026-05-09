FROM php:8.2-apache

RUN a2enmod rewrite

COPY apache.conf /etc/apache2/sites-available/000-default.conf
