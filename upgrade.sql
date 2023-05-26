-- ----------------------------
-- 2.2.0 手动更新请务必手动替换__PREFIX__为你的真实表前缀
-- ----------------------------
CREATE TABLE `__PREFIX__addon` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `title` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT '应用名称',
  `name` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT '标识名',
  `desc` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '简介',
  `version` varchar(10) CHARACTER SET utf8 NOT NULL COMMENT '版本号',
  `author` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT '作者姓名',
  `logo` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'LOGO',
  `admin_url` varchar(160) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '后台入口',
  `admin_url_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '管理后台：1使用系统 2自建后台',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT 'mp' COMMENT '支持平台',
  `cates` varchar(200) NOT NULL DEFAULT '' COMMENT '分类标签',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `cates` (`cates`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='应用表';