-- ----------------------------
-- 2.3.2
-- ----------------------------
ALTER TABLE `__PREFIX__reply` ADD COLUMN `medias` text COMMENT '素材';

CREATE TABLE `__PREFIX__media_xml_1` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '备注',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文本内容',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分组id',
  PRIMARY KEY (`id`),
  KEY `uid` (`admin_id`),
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='xml素材';
CREATE TABLE `__PREFIX__media_xml_2` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '备注',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文本内容',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分组id',
  PRIMARY KEY (`id`),
  KEY `uid` (`admin_id`),
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='xml素材';
CREATE TABLE `__PREFIX__media_xml_3` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '备注',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文本内容',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分组id',
  PRIMARY KEY (`id`),
  KEY `uid` (`admin_id`),
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='xml素材';
CREATE TABLE `__PREFIX__media_xml_4` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '备注',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文本内容',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分组id',
  PRIMARY KEY (`id`),
  KEY `uid` (`admin_id`),
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='xml素材';
CREATE TABLE `__PREFIX__media_xml_5` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '备注',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文本内容',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分组id',
  PRIMARY KEY (`id`),
  KEY `uid` (`admin_id`),
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='xml素材';

CREATE TABLE `__PREFIX__msg_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL,
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'bot id',
  `msg_id` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '消息ID',
  `msg_type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '素材类型',
  `content` text COLLATE utf8mb4_unicode_ci,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `year` bigint(6) unsigned NOT NULL DEFAULT '2023',
  `from_wxid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '发送人wxid',
  `from_nickname` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '发送人nickname',
  `group_wxid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '群wxid',
  `group_nickname` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '发送人nickname',
  PRIMARY KEY (`id`,`year`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='消息记录'
PARTITION BY RANGE (`year`)
(PARTITION p2022 VALUES LESS THAN (2023) ENGINE = InnoDB,
 PARTITION p2023 VALUES LESS THAN (2024) ENGINE = InnoDB,
 PARTITION p2024 VALUES LESS THAN (2025) ENGINE = InnoDB);

 CREATE TABLE `__PREFIX__msg_gather` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL,
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'bot id',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `wxids` text COLLATE utf8mb4_unicode_ci COMMENT '采集对象',
  `member_tags` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分组标签',
  `msg_types` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `to_media` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否转为素材',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='消息记录规则';