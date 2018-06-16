### 项目说明

当前项目仅用于学习目的，请勿用于生产环境。

### 安装 Docker

curl -fsSL https://get.docker.com | bash -s docker --mirror Aliyun

usermod -aG docker  root

systemctl start docker

### 配置加速器

curl -sSL https://get.daocloud.io/daotools/set_mirror.sh | sh -s http://8145ad9d.m.daocloud.io

### 安装 Docker-Compose

yum -y install epel-release python-pip

pip install docker-compose

### 更换 Composer 镜像

composer config -g repo.packagist composer https://packagist.phpcomposer.com

### 构建容器

docker build -t docker-mysql:5.7 ./app/mysql/

docker build -t docker-php:7.2 ./app/php/

docker build -t docker-redis:4.0 ./app/redis/

docker build -t docker-nginx:1.14 ./app/nginx/

### MySQL容器运行方法

MySQL:

docker run --name docker-mysql -p 3306:3306 -v /data/var/etc/mysql/mysqld.cnf:/etc/mysql/mysql.conf.d/mysqld.cnf -v /data/var/lib/mysql:/var/lib/mysql -e MYSQL_ROOT_PASSWORD=123456 -d docker-mysql:5.7

Redis:

docker run --name docker-redis -p 6379:6379 -v /data/var/etc/redis/redis.conf:/etc/redis.conf -d docker-redis:4.0 redis-server /etc/redis.conf

PHP:

docker run --name docker-php -p 9000:9000 -v /data/var/etc/php/php.ini:/usr/local/etc/php/php.ini -v /data/var/www:/usr/share/nginx/html --link docker-redis:redis --link docker-mysql:mysql -d docker-php:7.2

Nginx:

docker run --name docker-nginx -p 80:80 -p 443:443 -v /data/var/www:/usr/share/nginx/html -v /data/var/etc/nginx/conf.d/:/etc/nginx/conf.d/ -v /data/var/etc/nginx/nginx.conf:/etc/nginx/nginx.conf -v /data/var/log/nginx/:/var/log/nginx/ --link docker-php:php-fpm -d docker-nginx:1.14
