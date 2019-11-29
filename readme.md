## eznat
简单快捷的把内网映射到外网
# 重要说明
    1.服务端暂时只支持部署到linux系统。
    2.客户端只测试了windows和linux
    3.代码中包含windows和linux的运行环境，不需要自己下载和配置php，肥肠方便呢。
    4.服务端的web服务需要自行配置。
# 服务端使用说明
    1.git clone https://github.com/guoliang1994/eznat.git
    2.chown www:www eznat【更改目录的所有者，我的是www用户】
    2.配置网站指向eznat/public目录
    3.重启你的web服务
    4.登录后端管理端口映射
# 常见问题
    1.未使用正确用户执行启动，造成端口一直被占用，就算更改为www也无法启动进程
    答：ps -ef | grep php 查看启动的worker，全部停止后即可。
        fuser -k -n tcp port 强制关闭占用目标端口的程序。
    2.后台登录账号：admin，密码为123456
# 客户端使用说明
     1.git clone https://github.com/guoliang1994/eznat.git
     2.cd eznat/eznat
     3.windows 新增计划任务【【循环】】运行hide_run.vbs
     4.linux 新增计划任务每分钟 nohup 运行 keep_alive.php
# 简介
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
## 捐赠支持
    这是支付宝
    这是微信
    
