## 1.2
### 数据表
ALTER TABLE `bgf_agent` ADD COLUMN `staff_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所属员工id' AFTER `groups`;
ALTER TABLE `ky_bgf_task` ADD COLUMN `medias` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '介绍素材' AFTER `goods_cover`;