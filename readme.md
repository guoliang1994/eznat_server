## eznat
简单快捷的把内网映射到外网
## 重要说明,兄弟别光fork代码，给个star先。
    1.服务端暂时只支持部署到linux系统。
    2.客户端只测试了windows和linux
    3.代码中包含windows和linux的运行环境，不需要自己下载和配置php，肥肠方便呢。
    4.服务端的web服务需要自行配置。
## 服务端使用说明
    1.git clone https://github.com/guoliang1994/eznat.git
    2.chown www:www eznat【更改目录的所有者，我的是www用户】
    2.配置网站指向eznat/public目录
    3.重启你的web服务
    4.登录后端管理端口映射
[文档](https://gitee.com/FYDEV/eznat.wiki.git)
## 客户端使用说明
     1.git clone https://github.com/guoliang1994/eznat.git
     2.cd eznat/eznat
     3.windows 新增计划任务【【循环】】运行hide_run.vbs
     4.linux 新增计划任务每分钟 nohup 运行 keep_alive.php
[文档](https://gitee.com/FYDEV/eznat.wiki.git)
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