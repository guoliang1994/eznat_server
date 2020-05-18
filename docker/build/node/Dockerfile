FROM mhart/alpine-node
RUN  set -eux \
    && echo http://mirrors.aliyun.com/alpine/v3.10/main/ > /etc/apk/repositories  \
    && echo http://mirrors.aliyun.com/alpine/v3.10/community/ >> /etc/apk/repositories \
    && apk update && apk upgrade
# system local config
RUN set -eux \
    && npm config set registry https://registry.npm.taobao.org \
    && apk add python \
    && apk add make \
    && apk add git \
    && apk add g++ \
    && npm install node-sass -g --unsafe-perm

