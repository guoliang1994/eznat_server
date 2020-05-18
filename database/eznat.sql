/*
Navicat MySQL Data Transfer

Source Server         : docker_PRE_mysql
Source Server Version : 50729
Source Host           : 10.20.1.80:3306
Source Database       : eznat

Target Server Type    : MYSQL
Target Server Version : 50729
File Encoding         : 65001

Date: 2020-05-14 14:54:54
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of client
-- ----------------------------
INSERT INTO `client` VALUES ('1', 'windows', '2d49b68bd35a1784f05b9d931b9f2433', '1', '公司的服务器', '无', '1', '2020-05-14 06:49:07', '2020-05-14 02:47:26', '2020-05-14 14:49:07');

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
  `script_file` varchar(64) NOT NULL DEFAULT '0' COMMENT '脚本文件名称',
  `i` bigint(20) NOT NULL DEFAULT '0' COMMENT '输入',
  `o` bigint(20) NOT NULL DEFAULT '0' COMMENT '输出',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of port_map
-- ----------------------------
INSERT INTO `port_map` VALUES ('1', '远程桌面', '9940', '127.0.0.1', '3389', '无', '1', '2020-05-14 02:48:28', '2020-05-14 14:54:55', null, '0', '9940_3389.php', '391802', '5903480');
INSERT INTO `port_map` VALUES ('2', '数据库', '9941', '10.20.1.80', '3306', '无', '1', '2020-05-14 03:12:16', '2020-05-14 14:49:06', null, '0', '9941_3306.php', '0', '0');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'admin', '九九一十八', 'e10adc3949ba59abbe56e057f20f883e', 'https://weixing.istiny.cc/upload/5cefca787313b.jpg', '15870399165', '0', '2020-01-16 08:42:09', null, '2020-05-14 05:19:16', 'ba76b49f26bc3d9d76b782655de09554');

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
  `i` bigint(20) unsigned NOT NULL DEFAULT '0',
  `o` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of web_map
-- ----------------------------
INSERT INTO `web_map` VALUES ('1', '1', 'www.eznat.com', '10.20.1.80', '8004', 'https', '2020-05-14 06:49:21', '2020-05-14 14:49:21', '2020-05-14 06:49:21', '1579', '2811');
