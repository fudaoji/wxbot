-- ----------------------------
-- 2.3.9
-- ----------------------------
INSERT INTO `__PREFIX__admin_rule` VALUES ('1000', '', '消息记录', '158', '', '/admin/msglog/index', '_self', '1', '1604904482', '1655799164', '0', '1');
ALTER TABLE `__PREFIX__bot` CHANGE COLUMN `title` `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注';

CREATE TABLE `__PREFIX__bot_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0',
  `key` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `value` text COLLATE utf8mb4_unicode_ci,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `bot_id` (`bot_id`,`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='bot其他配置表';