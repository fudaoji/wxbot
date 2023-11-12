-- ----------------------------
-- 2.3.1
-- ----------------------------
ALTER TABLE `__PREFIX__bot` CHANGE COLUMN `app_key` `app_key` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '';
ALTER TABLE `__PREFIX__keyword` ADD COLUMN `match_type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0完全匹配  1模糊匹配' AFTER `need_at`;
ALTER TABLE `__PREFIX__keyword` ADD COLUMN `medias` text COMMENT '素材' AFTER `match_type`;