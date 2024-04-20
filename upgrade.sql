-- ----------------------------
-- 2.3.4
-- ----------------------------
ALTER TABLE `__PREFIX__msg_log` ADD COLUMN `gather_id` int(10) COMMENT '规则ID' DEFAULT '0';
ALTER TABLE `__PREFIX__msg_gather` ADD COLUMN `title` varchar(100) NOT NULL DEFAULT '' COMMENT '规则名称' AFTER `bot_id`,ADD COLUMN `expire_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '过期时间' AFTER `to_media`,ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '规则分组' AFTER `expire_time`,ADD COLUMN `keyword` varchar(200) NOT NULL DEFAULT '' COMMENT '监控关键词' AFTER `group_id`;