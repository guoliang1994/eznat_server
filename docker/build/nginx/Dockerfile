FROM alpine:latest
# 设置源
RUN set -eux \
   && echo http://mirrors.aliyun.com/alpine/v3.10/main/ > /etc/apk/repositories \
   && echo http://mirrors.aliyun.com/alpine/v3.10/community/ >> /etc/apk/repositories \
   && apk update && apk upgrade

# 安装修改请求头的扩展
RUN set -eux \
    && apk add gcc \
    && apk add g++ \
    && apk add make \
    && apk add git

RUN set -eux \
    && cd / \
    && wget 'http://nginx.org/download/nginx-1.17.6.tar.gz' \
    && tar -xzvf nginx-1.17.6.tar.gz

RUN set -eux \
    && apk add pcre-dev && apk add zlib-dev && apk add  openssl-dev && apk add  openssl \
    && cd nginx-1.17.6 \
    && ./configure \
    && make && make install
RUN set -eux \
     && cp -f /usr/local/nginx/sbin/nginx /usr/sbin

RUN set -eux \
 && apk del gcc \
 && apk del g++ \
 && apk del make \
 && apk del git \
