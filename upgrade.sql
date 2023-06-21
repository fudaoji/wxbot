-- ----------------------------
-- 2.2.4 手动更新请务必手动替换__PREFIX__为你的真实表前缀
-- ----------------------------
ALTER TABLE `__PREFIX__addon` DROP COLUMN `groups`, ADD COLUMN `sort_reply` int(10) UNSIGNED NOT NULL DEFAULT 1 AFTER `cates`;