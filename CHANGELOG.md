# 变更日志
本项目所有值得注意的更改都会记录在这个文件
> The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

> Unreleased Added Changed Fixed Removed
## Unreleased
### Added
+ UDP 协议支持
+ WS协议支持，对应的 WSS 也应该支持
+ 增加真正可独立运行的php环境。

## 2020/12/31 14:14 周四 v4.0.1
### Fixed
+ 修复配置文件读取路径错误

## 2020/12/02 16:16 周三 v4.0.0
### Changed
- 删除web界面控制，这就意味着不需要安装nginx
- 不使用数据库作为映射数据存储，这就意味着不需要安装mysql
- 删除编译好的 php 环境，这就意味着需要自行安装 php
- 删除 laravel 框架，这样增加了开源版本的复杂度。
- 删除客户端上线状态维护

### Added
+ 实现了 TCP 端口映射
+ 实现了 Http，Https 网站映射。
+ 客户端【eznat_client】代码与服务端【eznat_sever】代码放到一起，方便管理。
- 不再依赖 eznat_client 仓库，eznat_client 仓库不再维护。想要使用之前版本的小伙伴可自行修改。
- 暂不支持 UDP，WS 协议

### Fixed
+ 之前的版本管理比较混乱，现在增加 CHANGELOG.MD 来规范版本号以及系统变更
+ 这将会是一个新的开始。
+ 为了保留之前的版本代码，EZNAT将从 v4.0.0 作为起点版本。后续所有的改动都遵循语义化的版本控制。


###  2020-04-09 15:16 v2.0.0 
### Changed
+ 移除docker运行环境，因为很多人误解这个只能在docker环境中运行。
+ 统一运行所有端口映射改为可单独控制每一个映射
+ 不使用SQLit，改用使用mysql数据库。

### Added
+ 多用户使用，相互不影响
+ 网站单独使用80和443端口映射
+ 单独管控每一个服务，而不是统一启动停止

##  2019-12-19 11:34  v1.0.0
### Added
+ 完成内网穿透端口映射功能
+ 使用docker一键启动
