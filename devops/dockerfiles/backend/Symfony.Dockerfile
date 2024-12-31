FROM php:8.2-fpm

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    libssl-dev \
    curl \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

RUN docker-php-ext-install pdo pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .

RUN mkdir -p /var/www/html/var/cache
RUN mkdir -p /var/www/html/var/log
RUN chown -R www-data:www-data /var/www/html/var/cache /var/www/html/var/log
RUN #chmod +x /var/www/html/entrypoint.sh

EXPOSE 9000

CMD ["php-fpm"]
