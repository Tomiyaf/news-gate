/*
 Navicat Premium Dump SQL

 Source Server         : MySQL
 Source Server Type    : MySQL
 Source Server Version : 90200 (9.2.0)
 Source Host           : localhost:3307
 Source Schema         : berita_db

 Target Server Type    : MySQL
 Target Server Version : 90200 (9.2.0)
 File Encoding         : 65001

 Date: 26/11/2025 19:19:02
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for bookmarks
-- ----------------------------
DROP TABLE IF EXISTS `bookmarks`;
CREATE TABLE `bookmarks`  (
  `id_news` int NOT NULL,
  `id_user` int NOT NULL,
  `created_at` timestamp NULL DEFAULT (now()),
  PRIMARY KEY (`id_news`, `id_user`) USING BTREE,
  INDEX `fk_bookmarks_id_user`(`id_user` ASC) USING BTREE,
  CONSTRAINT `fk_bookmarks_id_news` FOREIGN KEY (`id_news`) REFERENCES `news` (`id_news`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_bookmarks_id_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories`  (
  `id_category` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `id_parent` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT (now()),
  `updated_at` timestamp NULL DEFAULT (now()),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_category`) USING BTREE,
  UNIQUE INDEX `name`(`name` ASC) USING BTREE,
  INDEX `fk_categories_id_parent`(`id_parent` ASC) USING BTREE,
  CONSTRAINT `fk_categories_id_parent` FOREIGN KEY (`id_parent`) REFERENCES `categories` (`id_category`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for comments
-- ----------------------------
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments`  (
  `id_comment` int NOT NULL AUTO_INCREMENT,
  `id_news` int NOT NULL,
  `id_user` int NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `id_parent` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT (now()),
  `updated_at` timestamp NULL DEFAULT (now()),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_comment`) USING BTREE,
  INDEX `fk_comments_id_news`(`id_news` ASC) USING BTREE,
  INDEX `fk_comments_id_user`(`id_user` ASC) USING BTREE,
  CONSTRAINT `fk_comments_id_news` FOREIGN KEY (`id_news`) REFERENCES `news` (`id_news`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_comments_id_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for news
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news`  (
  `id_news` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `thumbnail_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `status` enum('draft','published','archived') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'draft',
  `id_category` int NOT NULL,
  `views` int NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT (now()),
  `updated_at` timestamp NULL DEFAULT (now()),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_news`) USING BTREE,
  INDEX `fk_news_id_category`(`id_category` ASC) USING BTREE,
  INDEX `fk_news_id_user`(`id_user` ASC) USING BTREE,
  CONSTRAINT `fk_news_id_category` FOREIGN KEY (`id_category`) REFERENCES `categories` (`id_category`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_news_id_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for news_tags
-- ----------------------------
DROP TABLE IF EXISTS `news_tags`;
CREATE TABLE `news_tags`  (
  `id_news` int NOT NULL,
  `id_tag` int NOT NULL,
  `created_at` timestamp NULL DEFAULT (now()),
  PRIMARY KEY (`id_news`, `id_tag`) USING BTREE,
  INDEX `fk_news_tags_id_tag`(`id_tag` ASC) USING BTREE,
  CONSTRAINT `fk_news_tags_id_news` FOREIGN KEY (`id_news`) REFERENCES `news` (`id_news`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_news_tags_id_tag` FOREIGN KEY (`id_tag`) REFERENCES `tags` (`id_tag`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for otp
-- ----------------------------
DROP TABLE IF EXISTS `otp`;
CREATE TABLE `otp`  (
  `id_otp` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `code` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `payload` json NOT NULL,
  `purpose` enum('reset_password','email_verification') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT (now()),
  PRIMARY KEY (`id_otp`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id_role` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT (now()),
  `updated_at` timestamp NULL DEFAULT (now()),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_role`) USING BTREE,
  UNIQUE INDEX `name`(`name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tags
-- ----------------------------
DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags`  (
  `id_tag` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT (now()),
  `updated_at` timestamp NULL DEFAULT (now()),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_tag`) USING BTREE,
  UNIQUE INDEX `name`(`name` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `avatar_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `id_role` int NOT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT (now()),
  `updated_at` timestamp NULL DEFAULT (now()),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_user`) USING BTREE,
  UNIQUE INDEX `username`(`username` ASC) USING BTREE,
  UNIQUE INDEX `email`(`email` ASC) USING BTREE,
  INDEX `fk_users_id_role`(`id_role` ASC) USING BTREE,
  CONSTRAINT `fk_users_id_role` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id_role`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
