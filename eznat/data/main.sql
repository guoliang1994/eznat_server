/*
Navicat SQLite Data Transfer

Source Server         : eznat
Source Server Version : 30808
Source Host           : :0

Target Server Type    : SQLite
Target Server Version : 30808
File Encoding         : 65001

Date: 2019-11-18 13:46:47
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
"description"  TEXT(255)
);

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
-- Table structure for sqlite_sequence
-- ----------------------------
DROP TABLE IF EXISTS "main"."sqlite_sequence";
CREATE TABLE sqlite_sequence(name,seq);

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
