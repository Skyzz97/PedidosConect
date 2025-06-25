FROM php:8.1-apache

# Ativa mod_rewrite, se você tiver URLs amigáveis
RUN a2enmod rewrite

# Copia todos os arquivos do projeto
COPY . /var/www/html/

# (Opcional) Adiciona .htaccess com prioridade de index.html
RUN echo "DirectoryIndex index.html index.php" > /var/www/html/.htaccess

# Expondo porta padrão
EXPOSE 80

CMD ["apache2-foreground"]
