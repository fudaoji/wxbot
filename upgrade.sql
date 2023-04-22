-- ----------------------------
-- 2.1.3
-- ----------------------------
CREATE TABLE `__PREFIX__member_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'bot ID',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '标签',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `__PREFIX__bot_member` ADD COLUMN `tags` varchar(200) NOT NULL DEFAULT '' COMMENT '分组标签' AFTER `update_time`;
ALTER TABLE `__PREFIX__task` ADD COLUMN `member_tags` varchar(200) NOT NULL DEFAULT '' COMMENT '分组标签' AFTER `update_time`;
ALTER TABLE `__PREFIX__forward` ADD COLUMN `member_tags` varchar(200) NOT NULL DEFAULT '' COMMENT '分组标签' AFTER `update_time`;