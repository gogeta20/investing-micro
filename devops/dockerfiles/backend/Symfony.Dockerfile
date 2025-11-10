FROM php:8.2-fpm

# ------------------------------
# ENV & ARG
# ------------------------------
ENV COMPOSER_ALLOW_SUPERUSER=1
ARG MICRO_ENV=production
ENV MICRO_ENV=${MICRO_ENV}

# ------------------------------
# Dependencias generales
# ------------------------------
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    librabbitmq-dev \
    libssl-dev

# ------------------------------
# Extensiones PHP
# ------------------------------
RUN pecl install amqp && docker-php-ext-enable amqp \
    && pecl install mongodb && docker-php-ext-enable mongodb \
    && docker-php-ext-install pdo pdo_mysql zip

# ------------------------------
# Condicional: solo en entorno local
# ------------------------------
RUN if [ "$MICRO_ENV" = "local" ]; then \
    apt-get install -y vim && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug ; \
    fi

# ------------------------------
# Composer
# ------------------------------
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# ------------------------------
# Proyecto Symfony
# ------------------------------
WORKDIR /var/www/html

COPY ./project/backend/symfony/ .

RUN mkdir -p var/cache var/log \
    && chown -R www-data:www-data var/cache var/log

EXPOSE 9000

CMD ["php-fpm"]
