-- ----------------------------
-- 2.3.1
-- ----------------------------
ALTER TABLE `__PREFIX__keyword` ADD COLUMN `match_type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0完全匹配  1模糊匹配' AFTER `need_at`;