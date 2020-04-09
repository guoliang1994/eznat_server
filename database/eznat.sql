/*
Navicat MySQL Data Transfer

Source Server         : docker_PRE_mysql
Source Server Version : 50729
Source Host           : 10.20.1.80:3306
Source Database       : eznat

Target Server Type    : MYSQL
Target Server Version : 50729
File Encoding         : 65001

Date: 2020-04-08 10:13:56
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for client
-- ----------------------------
DROP TABLE IF EXISTS `client`;
CREATE TABLE `client` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(32) NOT NULL,
  `data_bus` varchar(128) NOT NULL COMMENT '数据传输总线',
  `user_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `description` text,
  `is_online` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '软删除字段',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of client
-- ----------------------------
INSERT INTO `client` VALUES ('23', 'windows', 'f25c3161ad05993ed5bb9f269cac8506', '16', '第三方的', '无', '0', '2020-04-01 09:13:29', '2020-03-30 14:56:08', '2020-04-01 17:13:29');
INSERT INTO `client` VALUES ('29', 'windows', '68507c0b5dbe5e511d5b90eea7fde1e9', '21', 'dfdsfdsfsd', '无', '0', '2020-04-01 09:13:29', '2020-03-30 15:18:13', '2020-04-01 17:13:29');
INSERT INTO `client` VALUES ('30', 'windows', '3f6c9b2aa1d88374985b96a1e001acca', '1', '公司的服务器', '无', '0', '2020-04-02 02:09:23', '2020-03-30 15:50:16', '2020-04-02 10:09:23');

-- ----------------------------
-- Table structure for port_map
-- ----------------------------
DROP TABLE IF EXISTS `port_map`;
CREATE TABLE `port_map` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `remote_port` int(5) NOT NULL,
  `local_ip` varchar(32) NOT NULL,
  `local_port` int(5) NOT NULL,
  `description` text,
  `client_id` int(11) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL COMMENT '软删除',
  `is_online` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `script_file` varchar(64) NOT NULL COMMENT '脚本文件名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of port_map
-- ----------------------------
INSERT INTO `port_map` VALUES ('25', 'fdsfds', '2224', '127.0.0.1', '3224', '无', '28', '2020-03-30 15:18:28', '2020-04-01 17:13:29', null, '0', '2224_3224.php');
INSERT INTO `port_map` VALUES ('26', '数据库', '9901', '10.20.1.80', '3306', '无', '30', '2020-03-30 15:50:36', '2020-04-02 09:48:53', null, '1', '9901_3306.php');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `password` varchar(128) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `mobile` char(11) DEFAULT NULL,
  `frozen` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `updated_at` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `token` varchar(64) DEFAULT NULL COMMENT '用户登录的token',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'admin', '九九一十八', '259e9e336f6fa9f2adb6ef12073d8fa7', 'https://weixing.istiny.cc/upload/5cefca787313b.jpg', '15870399165', '0', '2020-01-16 08:42:09', null, '2020-04-07 05:43:27', '4be7e98b998184c01122b02e79060d8b');
INSERT INTO `user` VALUES ('22', 'jiujiu', '郭梁', 'e10adc3949ba59abbe56e057f20f883e', '', '15870399165', '0', '2020-03-30 15:17:29', null, '2020-04-02 01:47:00', 'db4dcc4da980d2f643c8c464b69ede15');

-- ----------------------------
-- Table structure for web_map
-- ----------------------------
DROP TABLE IF EXISTS `web_map`;
CREATE TABLE `web_map` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `domain` varchar(64) NOT NULL,
  `local_ip` varchar(32) NOT NULL,
  `local_port` smallint(6) NOT NULL,
  `protocol` char(5) NOT NULL DEFAULT 'http' COMMENT '协议类型',
  `created_at` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of web_map
-- ----------------------------
INSERT INTO `web_map` VALUES ('5', '28', 'http://www.baidu.com', '127.0.0.1', '5555', 'http', '2020-03-30 15:19:25', '2020-03-30 15:19:25', null);
INSERT INTO `web_map` VALUES ('6', '30', 'www.eznat.com', '10.20.1.80', '8000', 'http', '2020-04-07 05:34:50', '2020-04-07 05:34:50', null);
