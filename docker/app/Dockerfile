FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
        libzip-dev \
        libpng-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        unzip \
        git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql mysqli gd zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/app/apache-vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-scripts --no-interaction --prefer-dist || true

COPY . .

RUN composer install --no-dev --no-interaction --prefer-dist \
    && chown -R www-data:www-data /var/www/html \
    && mkdir -p application/cache application/logs \
    && chmod -R 775 application/cache application/logs

EXPOSE 80
