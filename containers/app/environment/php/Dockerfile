FROM php:7.2

RUN apt-get update \
    && apt-get install -y git libgearman-dev supervisor \
    && sed -i 's/^\(\[supervisord\]\)$/\1\nnodaemon=true/' /etc/supervisor/supervisord.conf

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

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

ENTRYPOINT ["docker-entrypoint"]

WORKDIR /etc/supervisor/conf.d

CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]
