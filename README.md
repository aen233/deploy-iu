小白折腾服务器，docker-compose + deployer   

今天优化了一下，学着用docker-compose，将dockerfiles和deploy文件放在一起，  
docker-compose.yml和nginx的配置文件通过scp命令传到服务器上，  
执行docker-compose up -d，然后装pdo_mysql的扩展  
最后在本地执行dep deploy。结束，正常访问。

在 mysql + php + nginx 的基础上增加了 redis 和 adminer。

5个文件，5个命令，5个docker容器  

5个文件  
```
|-- deploy-iu   
    |-- deploy    
        |-- .artisan_env
        |-- .env
        |-- deploy.php
    |-- dockerfiles  
        |-- nginx
            |-- default.conf
        |-- docker-compose.yml
```
5个命令

```
// 本地
scp -r ~/Sites/deploy-iu/dockerfiles  root@111.22.3.4:/var/www/html/iu-docker

// 服务器(cd /var/www/html/iu-docker/dockerfiles)  
docker-compose up -d
docker-compose exec iu_phpfpm docker-php-ext-install pdo_mysql
docker-compose restart  iu_phpfpm

// 本地(cd ~/Sites/deploy-iu/deploy)    
dep deploy
```

5个容器
```
mysql
redis
php
nginx
adminer
```

其它笔记
```
// 进入redis容器
docker exec -it 51 redis-cli
```




