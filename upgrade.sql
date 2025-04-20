-- ----------------------------
-- 2.3.9
-- ----------------------------
INSERT INTO `__PREFIX__admin_rule` VALUES ('1000', '', '消息记录', '158', '', '/admin/msglog/index', '_self', '1', '1604904482', '1655799164', '0', '1');
ALTER TABLE `__PREFIX__bot` CHANGE COLUMN `title` `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注';

CREATE TABLE `__PREFIX__bot_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0',
  `key` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `value` text COLLATE utf8mb4_unicode_ci,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `bot_id` (`bot_id`,`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='bot其他配置表';

ALTER TABLE `__PREFIX__bot_groupmember` ADD COLUMN `invite_wxid` varchar(64) NOT NULL DEFAULT '' COMMENT '邀请人wxid' AFTER `headimgurl`, ADD COLUMN `invite_nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '邀请人昵称' AFTER `invite_wxid`, ADD COLUMN `invite_group_nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '邀请人群昵称' AFTER `invite_nickname`;
CREATE TABLE `__PREFIX__user_third` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nickname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'nickname',
  `openid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'openid',
  `headimgurl` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT ' 头像',
  `access_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '个人介绍',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建日期',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0.0.0.0' COMMENT '登录IP',
  `gender` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0男 1女',
  `location` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '地址',
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'wx' COMMENT '平台',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `username` (`nickname`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='三方登录表';
