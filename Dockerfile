FROM php:8.1-apache

# Habilita mod_rewrite, se necessário
RUN a2enmod rewrite

# Copia seus arquivos para o servidor Apache
COPY . /var/www/html/

# Define permissões (opcional)
RUN chown -R www-data:www-data /var/www/html

# Expõe a porta padrão
EXPOSE 80

# Comando de inicialização
CMD ["apache2-foreground"]
