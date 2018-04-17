FROM php:7.2-fpm

RUN apt-get update && apt-get install -y git libgearman-dev

WORKDIR /tmp
RUN git clone https://github.com/wcgallego/pecl-gearman \
    && cd pecl-gearman \
    && phpize \
    && ./configure \
    && make \
    && make install

RUN docker-php-ext-install -j$(nproc) opcache pdo_mysql
RUN docker-php-ext-enable gearman

WORKDIR /var/www/html
COPY docker-entrypoint /usr/local/bin/
RUN ln -s /usr/local/bin/docker-entrypoint / && chmod +x /docker-entrypoint

COPY php.ini /usr/local/etc/php

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]
