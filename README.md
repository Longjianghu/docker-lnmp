### 项目说明

PHP除了安装基础扩展外还额外安装了MongoDB、Redis、Swoole和Tideways扩展。

### 安装 Docker

curl -fsSL https://get.docker.com | bash -s docker --mirror Aliyun

usermod -aG docker  root

systemctl start docker

### 配置加速器

curl -sSL https://get.daocloud.io/daotools/set_mirror.sh | sh -s http://8145ad9d.m.daocloud.io

### 安装 Docker-Compose

curl -L https://github.com/docker/compose/releases/download/1.25.4/docker-compose-`uname -s`-`uname -m` -o /usr/local/bin/docker-compose

chmod a+x /usr/local/bin/docker-compose

### 更换 Composer 镜像

composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/

### 构建容器


docker build -t longjianghu/nginx:1.18.0 ./app/nginx/

> docker build -t longjianghu/openresty:1.19.3 ./app/openresty/

docker build -t longjianghu/mysql:8.0.19 ./app/mysql/

docker build -t longjianghu/php:7.4.13 ./app/php/

docker build -t longjianghu/redis:5.0.7 ./app/redis/

docker build -t longjianghu/mongo:4.4.0 ./app/mongo/

docker build -t longjianghu/hyperf:2.0 ./app/hyperf/

### 容器运行方法

Nginx:

docker run --name nginx -p 80:80 -p 443:443 -v /data/var/www:/data/htdocs -v /data/var/etc/nginx/conf.d/:/etc/nginx/conf.d/ -v /data/var/etc/nginx/nginx.conf:/etc/nginx/nginx.conf -v /data/var/log/nginx/:/var/log/nginx/ -d longjianghu/nginx:1.19.4

MySQL:

docker run --name mysql -p 3306:3306 -v /data/var/etc/mysql:/etc/mysql/conf.d -v /data/var/lib/mysql:/var/lib/mysql -v /data/var/log/mysql:/var/log/mysql -e MYSQL_ROOT_PASSWORD=123456 -d longjianghu/mysql:8.0.19

PHP:

docker run --name php -p 9000:9000 -v /data/var/etc/php/php.ini:/usr/local/etc/php/php.ini -v /data/var/www:/data/htdocs -v /data/var/log/php:/var/log/php -d longjianghu/php:7.4.13

Redis:

docker run --name redis -p 6379:6379 -v /data/var/etc/redis/redis.conf:/etc/redis.conf -d longjianghu/redis:5.0.7

Mongo:

docker run --name mongo -p 27017:27017 -v /data/var/lib/mongo:/data/db -e MONGO_INITDB_ROOT_USERNAME=root -e MONGO_INITDB_ROOT_PASSWORD=123456 -d longjianghu/mongo:4.4.0

PHPMyadmin：

docker run --name phpmyadmin -p 8000:80 -e PMA_HOST=172.17.0.1 -d phpmyadmin/phpmyadmin

Swoft:

docker run --rm -it -v /data/var/www/hyperf:/data longjianghu/hyperf:2.0 composer install -d /data

docker run --name hyperf -p 8080:9501 -v /data/var/www/hyperf:/data -d longjianghu/hyperf:4.5.2