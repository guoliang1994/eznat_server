# eznat
史上最简单的内网穿透软件，部署简单，使用简单，传输稳定。
### 重要说明,兄弟别光fork代码，给个star先。
    1.服务端暂时只支持部署到linux系统。
    2.客户端只测试了windows和linux
### 服务端使用
    1. 安装docker
        yum install -y yum-utils device-mapper-persistent-data lvm2
        yum-config-manager --add-repo https://mirrors.aliyun.com/docker-ce/linux/centos/docker-ce.repo
        yum install docker-ce
        yum install docker-compose
        如果遇到错误：请查看下面的文档
        [docker安装文档](https://gitee.com/FYDEV/eznat/wikis/pages)
    1. git clone https://gitee.com/FYDEV/eznat.git
    1. 修改前端请求接口配置 【待编写】
    2. 修改服务器端口配置 【待编写】
    3. 修改通道端口配置 【待编写】
    1. cd eznat/docker 
    1. cp .env.example .evn && docker-composer up 这一步可能消耗的时间会有点长，时间随缘
    1. 耐心等待启动....
    1. 访问
   
### 客户端使用说明
    git clone https://gitee.com/FYDEV/eznat.git
    cd eznat/runenv
    win_install.bat
    等待安装扩展包
    修改配置 eznat/eznat/conf/Conf.php 【待编写】
    测试是否穿透成功
    运行test_win.bat
    
    长期运行
 ## 服务端常见问题
     1.未使用正确用户执行启动，造成端口一直被占用，就算更改为www也无法启动进程
     答：ps -ef | grep php 查看启动的worker，全部停止后即可。
         fuser -k -n tcp port 强制关闭占用目标端口的程序。
     2.后台登录账号：admin，密码为123456
## eznat优势 使用超级简单，使用超级简单
    1.web界面管理服务端【启动，停止，重启，查看状态等】。
    2.客户端下载运行即可，无需任何配置。
    3.支持多端口映射。
    4.支持多台设备穿透。
    5.端口流量转换统计。【未开发】
    6.开源免费，纯php开发，可供学习参考。
## 鸣谢
    laravel 框架
    workerman https://github.com/walkor/Workerman.git
    vue-element-admin https://github.com/PanJiaChen/vue-element-admin.git
## 沟通交流
    QQ群：946192373
## 捐赠支持
微信![微信](https://images.gitee.com/uploads/images/2019/1129/175939_8545619a_1026697.png "微信.png")
支付宝![支付宝](https://images.gitee.com/uploads/images/2019/1129/180417_10104e83_1026697.png "支付宝.png")
## web端截图
![输入图片说明](https://images.gitee.com/uploads/images/2019/1129/180852_88752dc8_1026697.png "登录界面.png")
![输入图片说明](https://images.gitee.com/uploads/images/2019/1129/180907_ce6226d5_1026697.png "设备管理界面.png")
![输入图片说明](https://images.gitee.com/uploads/images/2019/1129/180923_bf4588d5_1026697.png "用户管理界面.png")
![输入图片说明](https://images.gitee.com/uploads/images/2019/1129/180943_42f0c869_1026697.png "管理端界面.png")