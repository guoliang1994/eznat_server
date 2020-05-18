ARG PHP_VERSION=${PHP_VERSION}
FROM php:7.4.1-fpm-alpine

COPY resource /resource

RUN echo http://mirrors.aliyun.com/alpine/v3.10/main/ > /etc/apk/repositories && \
  echo http://mirrors.aliyun.com/alpine/v3.10/community/ >> /etc/apk/repositories
RUN apk update && apk upgrade

# 安装编译依赖
RUN set -eux \
     && apk add autoconf pkgconf  gcc g++ make \
     && apk add curl-dev libxml2 libxml2-dev \
     && apk add zlib-dev libpng-dev freetype freetype-dev \
     && apk add libjpeg libjpeg-turbo-dev libzip-dev

# 通过 docker-ext-php-install
RUN set -eux \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql fileinfo zip pcntl opcache

# 安装composer
RUN set -eux \
    && mv /resource/composer.phar /usr/local/bin/composer \
    && chmod 755 /usr/local/bin/composer \
    && composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/

## 移除编译依赖
RUN set -eux \
   && apk del autoconf pkgconf  gcc g++ make \
