FROM php:7.4.13-zts-alpine3.12

MAINTAINER Longjianghu <215241062@qq.com>

# 更换国内源
RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.aliyun.com/g' /etc/apk/repositories && apk update

# 环境配置
ENV TIMEZONE=Asia/Shanghai \
    AMQP_VERSION=1.10.2 \
    IMAGICK_VERSION=3.4.3 \
    MONGODB_VERSION=1.9.0 \
    MSGPACK_VERSION=2.1.2 \
    NSQ_VERSION=3.5.0 \
    REDIS_VERSION=5.3.2 \
    SWOOLE_VERSION=4.5.2 \
    TIDEWAYS_VERSION=5.0.4

# 安装扩展
COPY ./src/* /tmp/
COPY ./composer.phar /usr/local/bin/composer

RUN set -xe \
    && apk add --no-cache --virtual .build-deps tzdata g++ make autoconf git \
    freetype-dev libwebp-dev libpng-dev jpeg-dev gmp-dev libzip-dev openssl-dev icu-dev nghttp2-dev imagemagick-dev libevent-dev rabbitmq-c-dev \
    && docker-php-ext-configure gd --with-webp --with-jpeg --with-freetype \
    && docker-php-ext-install -j$(nproc) gd zip gmp opcache pdo_mysql mysqli intl pcntl exif sockets bcmath \
    && mkdir /tmp/swoole && tar -zxf /tmp/swoole-src-${SWOOLE_VERSION}.tar.gz -C /tmp/swoole --strip-components=1 \
    && ( \
        cd /tmp/swoole \
        && phpize \
        && ./configure --enable-openssl --enable-http2 --enable-sockets --enable-mysqlnd \
        && make -j$(nproc) && make install \
    ) \
    && mkdir /tmp/tideways && tar -zxf /tmp/tideways-${TIDEWAYS_VERSION}.tar.gz -C /tmp/tideways --strip-components=1 \
    && ( \
        cd /tmp/tideways \
        && phpize \
        && ./configure && make -j$(nproc) && make install \
    ) \
    && pecl install /tmp/amqp-${AMQP_VERSION}.tgz \
    && pecl install /tmp/imagick-${IMAGICK_VERSION}.tgz \
    && pecl install /tmp/mongodb-${MONGODB_VERSION}.tgz \
    && pecl install /tmp/msgpack-${MSGPACK_VERSION}.tgz \
    && pecl install /tmp/nsq-${NSQ_VERSION}.tgz \
    && pecl install /tmp/redis-${REDIS_VERSION}.tgz \
    && docker-php-ext-enable tideways_xhprof swoole redis mongodb imagick msgpack nsq amqp \
    && ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
    && apk del --purge *-dev \
    && rm /var/cache/apk/* -rf \
    && rm /usr/src/* -rf \
    && rm /tmp/* -rf \
    && chmod a+x /usr/local/bin/composer

WORKDIR /data/htdocs