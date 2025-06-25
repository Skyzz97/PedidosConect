FROM php:8.1-apache

RUN a2enmod rewrite

COPY public/ /var/www/html/

EXPOSE 80

CMD ["apache2-foreground"]
