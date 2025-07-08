FROM php:8.1-apache
RUN docker-php-ext-install pdo pdo_sqlite
COPY . /var/www/html
WORKDIR /var/www/html
ENTRYPOINT ["/var/www/html/runner.sh"]
