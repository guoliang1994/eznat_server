/*
Navicat SQLite Data Transfer

Source Server         : eznat
Source Server Version : 30808
Source Host           : :0

Target Server Type    : SQLite
Target Server Version : 30808
File Encoding         : 65001

Date: 2020-01-08 09:49:41
*/

PRAGMA foreign_keys = OFF;

-- ----------------------------
-- Table structure for client
-- ----------------------------
DROP TABLE IF EXISTS "main"."client";
CREATE TABLE "client" (
"id"  INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
"type"  TEXT(32) NOT NULL,
"channel"  TEXT(128) NOT NULL,
"user_id"  INTEGER NOT NULL,
"name"  TEXT(32) NOT NULL,
"description"  TEXT(255),
"is_online"  INTEGER NOT NULL DEFAULT 0
);

-- ----------------------------
-- Records of client
-- ----------------------------
INSERT INTO "main"."client" VALUES (1, 'window', '41105316ccadea84dec6acee9519d2d1', 1, '公司的服务器', '10.20.1.80内网穿透', 1);
INSERT INTO "main"."client" VALUES (4, 'windows', '1c4243c118e344520a539f8076017fcd', 1, '家里的服务器', '家里的服务器', 0);
INSERT INTO "main"."client" VALUES (6, '树莓派', 'f2ea7d96de13dc688c9a0ae26a655631', 1, '家里的树莓派', '树莓派', 1);

-- ----------------------------
-- Table structure for port_map
-- ----------------------------
DROP TABLE IF EXISTS "main"."port_map";
CREATE TABLE "port_map" (
"id"  INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
"name"  TEXT(32) NOT NULL,
"remote_port"  TEXT(5) NOT NULL,
"local_ip"  TEXT(16) NOT NULL,
"local_port"  TEXT(5) NOT NULL,
"description"  TEXT(255),
"channel"  TEXT(32) NOT NULL,
"created_at"  TEXT,
"updated_at"  TEXT,
"sever_script"  TEXT,
"user_id"  INTEGER NOT NULL
);

-- ----------------------------
-- Records of port_map
-- ----------------------------
INSERT INTO "main"."port_map" VALUES (3, '家里的远程桌面', 9920, '127.0.0.1', 3389, '无', '1c4243c118e344520a539f8076017fcd', '2019-11-14 06:47:20', '2019-12-23 07:43:40', null, 1);
INSERT INTO "main"."port_map" VALUES (4, '远程桌面', 9930, '127.0.0.1', 3389, '无', '41105316ccadea84dec6acee9519d2d1', '2019-11-14 06:48:23', '2019-12-09 07:11:21', null, 1);
INSERT INTO "main"."port_map" VALUES (8, 'Xdebug', 9931, '127.0.0.1', 9001, '无', '41105316ccadea84dec6acee9519d2d1', '2019-12-09 07:33:04', '2019-12-09 07:46:53', null, 1);
INSERT INTO "main"."port_map" VALUES (9, 'ILO1', 9940, '192.168.1.101', 9940, '无', 'f2ea7d96de13dc688c9a0ae26a655631', '2019-12-09 08:02:24', '2019-12-14 11:27:45', null, 1);
INSERT INTO "main"."port_map" VALUES (11, 'ILO2', 9941, '192.168.1.101', 9941, '无', 'f2ea7d96de13dc688c9a0ae26a655631', '2019-12-09 08:40:32', '2019-12-23 01:43:49', null, 1);
INSERT INTO "main"."port_map" VALUES (12, 'ILO3', 9942, '192.168.1.101', 9942, '无', 'f2ea7d96de13dc688c9a0ae26a655631', '2019-12-09 08:54:54', '2019-12-14 11:27:57', null, 1);
INSERT INTO "main"."port_map" VALUES (13, 'ILO4', 9943, '192.168.1.101', 9943, '无', 'f2ea7d96de13dc688c9a0ae26a655631', '2019-12-09 09:05:13', '2019-12-14 11:28:04', null, 1);
INSERT INTO "main"."port_map" VALUES (14, 'ILO5', 9944, '192.168.1.101', 9944, '无', 'f2ea7d96de13dc688c9a0ae26a655631', '2019-12-09 09:09:37', '2019-12-14 11:28:09', null, 1);
INSERT INTO "main"."port_map" VALUES (15, 'ILO6', 9945, '192.168.1.101', 9945, '无', 'f2ea7d96de13dc688c9a0ae26a655631', '2019-12-09 09:11:07', '2019-12-14 11:28:14', null, 1);
INSERT INTO "main"."port_map" VALUES (20, '家里服务器VNC', 9921, '127.0.0.1', 5900, '无', '1c4243c118e344520a539f8076017fcd', '2019-12-15 10:38:39', '2019-12-15 10:38:39', null, 1);
INSERT INTO "main"."port_map" VALUES (23, 'yax', 9932, '127.0.0.1', 8000, '无', '41105316ccadea84dec6acee9519d2d1', '2019-12-19 08:06:27', '2019-12-19 08:06:27', null, 1);
INSERT INTO "main"."port_map" VALUES (24, 'yax_db', 9933, '192.168.99.100', 32769, '无', '41105316ccadea84dec6acee9519d2d1', '2019-12-19 08:27:19', '2019-12-19 08:27:19', null, 1);

-- ----------------------------
-- Table structure for sqlite_sequence
-- ----------------------------
DROP TABLE IF EXISTS "main"."sqlite_sequence";
CREATE TABLE sqlite_sequence(name,seq);

-- ----------------------------
-- Records of sqlite_sequence
-- ----------------------------
INSERT INTO "main"."sqlite_sequence" VALUES ('user', 3);
INSERT INTO "main"."sqlite_sequence" VALUES ('_client_old_20191223', 7);
INSERT INTO "main"."sqlite_sequence" VALUES ('port_map', 26);
INSERT INTO "main"."sqlite_sequence" VALUES ('client', 7);

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS "main"."user";
CREATE TABLE "user" (
"id"  INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
"account"  TEXT(16) NOT NULL,
"name"  TEXT(64),
"password"  TEXT(64) NOT NULL,
"avatar"  TEXT(255),
"mobile"  TEXT(11)
);

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO "main"."user" VALUES (1, 'admin', '超级管理员', '259e9e336f6fa9f2adb6ef12073d8fa7', 'https://weixing.istiny.cc/upload/5cefca787313b.jpg', 15870399165);

-- ----------------------------
-- Table structure for _client_old_20191223
-- ----------------------------
DROP TABLE IF EXISTS "main"."_client_old_20191223";
CREATE TABLE "_client_old_20191223" (
"id"  INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
"type"  TEXT(32) NOT NULL,
"channel"  TEXT(128) NOT NULL,
"user_id"  INTEGER NOT NULL,
"name"  TEXT(32) NOT NULL,
"description"  TEXT(255)
);

-- ----------------------------
-- Records of _client_old_20191223
-- ----------------------------
INSERT INTO "main"."_client_old_20191223" VALUES (1, 'window', '41105316ccadea84dec6acee9519d2d1', 1, '公司的服务器', '10.20.1.80内网穿透');
INSERT INTO "main"."_client_old_20191223" VALUES (4, 'windows', '1c4243c118e344520a539f8076017fcd', 1, '家里的服务器', '家里的服务器');
INSERT INTO "main"."_client_old_20191223" VALUES (6, '树莓派', 'f2ea7d96de13dc688c9a0ae26a655631', 1, '家里的树莓派', '树莓派');
