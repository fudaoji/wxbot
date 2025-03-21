-- ----------------------------
-- 2.3.9
-- ----------------------------
INSERT INTO `__PREFIX__admin_rule` VALUES ('1000', '', '消息记录', '158', '', '/admin/msglog/index', '_self', '1', '1604904482', '1655799164', '0', '1');
ALTER TABLE `__PREFIX__bot` CHANGE COLUMN `title` `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注';