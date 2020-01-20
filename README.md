### 项目说明

PHP除了安装基础扩展外还额外安装了MongoDB、Redis、Swoole和Tideways扩展。

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

进入PHP容器内执行

su www-data -c "composer config -g repo.packagist composer https://packagist.phpcomposer.com"

Mac和Windows挂载导致性能低下可以使用docker-sync方案 [https://github.com/EugenMayer/docker-sync](https://github.com/EugenMayer/docker-sync "https://github.com/EugenMayer/docker-sync")

### 构建容器

docker build -t mysql:8.0 ./app/mysql/

docker build -t mongodb:4.2 ./app/mongodb/

docker build -t php:7.4.1 ./app/php/

docker build -t redis:5.0 ./app/redis/

docker build -t nginx:1.17 ./app/nginx/

docker build -t swoft:1.2.1 ./app/swoft/

docker build -t swoft-tracker:1.2.1 ./app/swoft-tracker/

### 容器运行方法

MySQL:

docker run --name mysql -p 3306:3306 -v /data/var/etc/mysql:/etc/mysql/conf.d -v /data/var/lib/mysql:/var/lib/mysql -v /data/var/log/mysql:/var/log/mysql -e MYSQL_ROOT_PASSWORD=123456 -d mysql:8.0

Mongodb:

docker run --name mongodb -p 27017:27017 -v /data/var/lib/mongodb:/data/db -e MONGO_INITDB_ROOT_USERNAME=root -e MONGO_INITDB_ROOT_PASSWORD=123456 -d mongodb:4.2

Redis:

docker run --name redis -p 6379:6379 -v /data/var/etc/redis/redis.conf:/etc/redis.conf -d redis:5.0

PHP:

docker run --name php -p 9000:9000 -v /data/var/etc/php/php.ini:/usr/local/etc/php/php.ini -v /data/var/www:/var/www/html -v /data/var/log/php:/var/log/php -d php:7.4.1

Nginx:

docker run --name nginx -p 80:80 -p 443:443 -v /data/var/www:/var/www/html -v /data/var/etc/nginx/conf.d/:/etc/nginx/conf.d/ -v /data/var/etc/nginx/nginx.conf:/etc/nginx/nginx.conf -v /data/var/log/nginx/:/var/log/nginx/ -d nginx:1.17

使用[let’s encrypt](https://github.com/longjianghu/scripts/tree/master/le-dns "let’s encrypt")证书

docker run --name nginx -p 80:80 -p 443:443 -v /data/var/www:/var/www/html -v /data/var/etc/nginx/conf.d/:/etc/nginx/conf.d/ -v /data/var/etc/nginx/nginx.conf:/etc/nginx/nginx.conf -v /data/var/etc/nginx/cert/:/etc/nginx/cert/ -v /data/var/log/nginx/:/var/log/nginx/ -d nginx:1.17

PHPMyadmin：

docker run --name phpmyadmin -p 8000:80 -e PMA_HOST=172.17.0.1 -d phpmyadmin/phpmyadmin

Swoft:

docker run --rm -it -v /data/var/www/swoft:/data swoft:1.2.1 composer install -d /data

docker run --name swoft -p 8080:18306 -v /data/var/www/swoft:/data -d swoft:1.2.1 php /data/bin/swoft http:start