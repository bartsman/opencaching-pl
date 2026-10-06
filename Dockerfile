FROM php:8.3-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        gd \
        gettext \
        mysqli \
        pdo_mysql \
        zip \
    && pecl install apcu-5.1.23 \
    && docker-php-ext-enable apcu \
    && a2enmod rewrite headers expires \
    && rm -rf /var/lib/apt/lists/*

COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/opencaching.ini
COPY docker/entrypoint.sh /usr/local/bin/opencaching-entrypoint
RUN sed -i 's/\r$//' /usr/local/bin/opencaching-entrypoint \
    && chmod +x /usr/local/bin/opencaching-entrypoint

WORKDIR /var/www/html

ENTRYPOINT ["opencaching-entrypoint"]
CMD ["apache2-foreground"]
