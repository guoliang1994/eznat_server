### Docker PRE (Docker PHP Runtime Environment )
Docker PHP运行时环境，快速搭建PHP开发环境，这个更底层，需要了解Linux，Nginx配置，比宝塔，PHPStudy
WAMP这些集成环境 "难用"。但是如果你会用这个，宝塔，PHPStudy什么的，你也会用。相信你也会像我一样爱上
在docker中搞开发。
### 体验感
win10 + PHPStorm + DockerPRE搞PHP开发，体验感简直不要太好。如丝般顺滑~
### PHP开发交流群
    QQ群：169244254
### 目录结构
```
www  WEB部署目录（或者子目录）
├─build      构建镜像脚本目录
│  ├─mysql      构建mysql脚本
│  ├─nginx      构建nginx脚本
│  ├─ ...       更多构建脚本
│
├─mysql         mysql相关目录
│  ├─backup         数据备份目录
│  ├─config         配置目录
│  ├─data           MySQL运行时数据目录
│  ├─log            MySQL日志目录
│
├─php            路由定义目录
│  ├─conf          配置文件目录
│  └─ log           php日志目录   
│
├─ ...           类似的其他目录
│  ├─
│  ├─
│  └─
│
├─.env.example          环境变量示例文件
├─docker-composer.yml    构建容器的脚本
├─README.md             README 文件
```
### 如何使用
    安装docker，自行百度，文档很多 [官方文档](https://docs.docker.com/)
    配置为国内镜像提升镜像下载速度，自行百度。
    git clone  https://gitee.com/FYDEV/docker_PRE.git
    切换到 docker_PRE 目录
    复制.env.example 到 .env
.env 文件内容
```
# 应用信息
APP_NAME=DockerPRE
PROJECT_DIR=D:\p_php  我是在widows上装的，这个地方按需修改。
# 网站端口两个，默认http，若需要https，请到./nginx/conf.d/下,cp https.eznat.notuse https.eznat.conf
# 然后把证书放到conf目录，修改https.eznat.conf证书指向即可
# nginx配置，端口映射，docker内部固定使用80和443
# 这里配置的是宿主机到容器的端口映射  8000->80
HTTP_PORT=80
HTTPS_PORT=443

# 数据库
MYSQL_VERSION=5.7
MYSQL_PORT=3306  
MYSQL_PASSWORD=root

# php
PHP_VERSION=7.4.1

# redis
REDIS_PORT=3679

# solr
SOLR_PORT=8983
```
### 新增网站
    切到nginx/conf/conf.d目录下，里面预先有许多配置好的网站。
    随便找一个修改一下下面的两项
    ```
        server_name www.xxx.com 改成你想要的
        set $root xxx/path(改成你自己的目录);
    ```
    每次修改完记得docker-compose up -d nginx
docker-compose up 等待容器启动.
### php
    xdebug  php调试神器
    redis，memcache   内存缓存
    solor   全文搜索
    
### nginx
    headers-more-nginx-module 修改请求头扩展，用于和xdebug搭配完成web调试功能
    
### 如何使用xdebug进行调试？
    配置PHPstorm监听调试端口
    
    修改php.ini中的xdebug配置
    [Xdebug]
    xdebug.profiler_enable=on
    xdebug.trace_output_dir="/usr/local/php/xdebug/"
    xdebug.profiler_output_dir="/usr/local/php/xdebug/"
    xdebug.remote_enable=on
    xdebug.remote_handler=dbgp
    xdebug.remote_host=10.20.1.80 (修改此处为你的本机IP)
    xdebug.remote_port=9001 （PHPstorm监听的xdebug端口）
    xdebug.remote_autostart=false
    xdebug.remote_log=/www/xdebug.log
至此，无论你是使用异步请求还是web请求，都会在PHPStorm断点处停下。你会发现找bug更快了~。

### 变得更强
    使用内网穿透调试第三方接口回调，如微信支付回调等。
    你是否有这样的困惑，微信对接时，没对接成功之前，都不知道微信到底回调没有，
    然后大量的file_put_contents(),有时候写得有问题，要搞很久，采用内网穿透+docker环境
    中的xdebug，在回调的地方打上断点，你会无比开心的~
    
### EZNAT内网穿透开源地址
    https://gitee.com/FYDEV/eznat_server.git
### 我们一起
    让PHP开发变成一种享受吧，把更多的精力用来学习更新的知识。一起加油...
   