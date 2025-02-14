FROM php:8.2-fpm

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    vim \
    librabbitmq-dev \
    libssl-dev \
    && pecl install amqp \
    && docker-php-ext-enable amqp \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

RUN docker-php-ext-install pdo pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

WORKDIR /var/www/html

COPY . .
COPY ./project/backend/symfony/package.json ./

RUN mkdir -p /var/www/html/var/cache
RUN mkdir -p /var/www/html/var/log
RUN chown -R www-data:www-data /var/www/html/var/cache /var/www/html/var/log
RUN #chmod +x /var/www/html/entrypoint.sh

EXPOSE 9000

CMD ["php-fpm"]
