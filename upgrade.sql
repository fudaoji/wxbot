-- ----------------------------
-- 2.1.4 手动更新请务必手动替换__PREFIX__为你的真实表前缀
-- ----------------------------
CREATE TABLE `__PREFIX__media_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `title` (`title`,`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='素材分组';

ALTER TABLE `__PREFIX__media_file_1` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' AFTER `location`;
ALTER TABLE `__PREFIX__media_file_2` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' AFTER `location`;
ALTER TABLE `__PREFIX__media_file_3` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' AFTER `location`;
ALTER TABLE `__PREFIX__media_file_4` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' AFTER `location`;
ALTER TABLE `__PREFIX__media_file_5` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' AFTER `location`;

ALTER TABLE `__PREFIX__media_image_1` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' AFTER `location`;
ALTER TABLE `__PREFIX__media_image_2` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' AFTER `location`;
ALTER TABLE `__PREFIX__media_image_3` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' AFTER `location`;
ALTER TABLE `__PREFIX__media_image_4` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' AFTER `location`;
ALTER TABLE `__PREFIX__media_image_5` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' AFTER `location`;

ALTER TABLE `__PREFIX__media_link_1` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;
ALTER TABLE `__PREFIX__media_link_2` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;
ALTER TABLE `__PREFIX__media_link_3` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;
ALTER TABLE `__PREFIX__media_link_4` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;
ALTER TABLE `__PREFIX__media_link_5` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;

ALTER TABLE `__PREFIX__media_text_1` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;
ALTER TABLE `__PREFIX__media_text_2` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;
ALTER TABLE `__PREFIX__media_text_3` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;
ALTER TABLE `__PREFIX__media_text_4` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;
ALTER TABLE `__PREFIX__media_text_5` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;

ALTER TABLE `__PREFIX__media_video_1` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;
ALTER TABLE `__PREFIX__media_video_2` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;
ALTER TABLE `__PREFIX__media_video_3` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;
ALTER TABLE `__PREFIX__media_video_4` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;
ALTER TABLE `__PREFIX__media_video_5` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;