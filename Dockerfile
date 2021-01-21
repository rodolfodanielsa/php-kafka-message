FROM php:7.4.14-fpm-alpine3.12

ENV LIBRDKAFKA_VERSION v0.9.5
ENV BUILD_DEPS \
  autoconf \
        bash \
        build-base \
        git \
        pcre-dev \
        python3 \
        py3-pip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
RUN apk update && apk upgrade
RUN apk --no-cache --virtual .build-deps add ${BUILD_DEPS} \
    && cd /tmp \
    && git clone https://github.com/edenhill/librdkafka.git \
    && cd librdkafka \
    && ./configure --install-deps \
    && make \
    && make install \
    && pecl install rdkafka \
    && docker-php-ext-enable rdkafka \
    && rm -rf /tmp/librdkafka \
    && apk del .build-deps