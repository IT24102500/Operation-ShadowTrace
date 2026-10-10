FROM php:8.2-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
    libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

COPY src/ /var/www/html/
COPY files/ /var/www/files/
COPY db/ /var/www/db/

RUN mkdir -p /var/www/db /var/www/files \
    && chown -R www-data:www-data /var/www/db /var/www/files \
    && chmod -R 777 /var/www/db /var/www/files

EXPOSE 80