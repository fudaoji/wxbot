-- ----------------------------
-- 2.2.3 手动更新请务必手动替换__PREFIX__为你的真实表前缀
-- ----------------------------
ALTER TABLE `__PREFIX__admin_group` ADD COLUMN `addons` text NULL COMMENT '角色应用' AFTER `tenant_group`;