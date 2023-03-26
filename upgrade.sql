-- ----------------------------
-- 2.1.2
-- ----------------------------
CREATE TABLE `__PREFIX__moments_follow` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '机器人id',
  `wxids` text COLLATE utf8mb4_unicode_ci COMMENT '接收者wxids，逗号隔开',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `last_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后同步时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `admin_id` (`admin_id`,`bot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='跟圈设置';
