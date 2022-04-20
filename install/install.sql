SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `ky_admin`
-- ----------------------------
DROP TABLE IF EXISTS `ky_admin`;
CREATE TABLE `ky_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(10) NOT NULL DEFAULT '1' COMMENT '所属部门',
  `username` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '账号',
  `password` char(60) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '密码',
  `email` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '邮箱',
  `mobile` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '手机号',
  `realname` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '姓名',
  `ip` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '登录ip',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `last_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '新增时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
--  Records of `ky_admin`
-- ----------------------------
BEGIN;
INSERT INTO `ky_admin` VALUES ('1', '1', 'admin', '$2y$10$8e/WKStcGxWexB/kfmbQre6uCtMRjpXZB4RH4u70ZKfkScIx.kfCy', '1589856452@qq.com', '15659827559', '超级管理员', '36.249.133.49', '1', '1650466650', '0', '1650466650');
COMMIT;

-- ----------------------------
--  Table structure for `ky_admin_group`
-- ----------------------------
DROP TABLE IF EXISTS `ky_admin_group`;
CREATE TABLE `ky_admin_group` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL DEFAULT '' COMMENT '部门标识',
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` text NOT NULL,
  `sort` tinyint(3) NOT NULL DEFAULT '0',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `remark` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '备注',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `title` (`title`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `ky_admin_group`
-- ----------------------------
BEGIN;
INSERT INTO `ky_admin_group` VALUES ('1', 'super_admin', '超级管理员', '1', '7,160,149,151,150,152,166,153,155,69,79,156,157,158,159,1,3,105,106,107,108,109,4,6,162,148', '0', '0', '1445158837', '拥有系统最高管理权限', '1626581117'), ('2', 'admin', '普通管理员', '1', '169,7,172,171,170,173,152,160,166,162,151', '1', '0', '1456626627', '后台普通管理员权限', '1646876180');
COMMIT;

-- ----------------------------
--  Table structure for `ky_admin_rule`
-- ----------------------------
DROP TABLE IF EXISTS `ky_admin_rule`;
CREATE TABLE `ky_admin_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '标识',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '标题名称',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父级菜单',
  `icon` varchar(30) NOT NULL DEFAULT '' COMMENT '图标',
  `href` varchar(50) NOT NULL DEFAULT '' COMMENT '路径',
  `target` enum('_self','_blank') NOT NULL DEFAULT '_self' COMMENT '打开方式',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  `sort` int(6) unsigned NOT NULL DEFAULT '0' COMMENT '数字越小越靠前',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1菜单 2权限',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=174 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
--  Records of `ky_admin_rule`
-- ----------------------------
BEGIN;
INSERT INTO `ky_admin_rule` VALUES ('1', '', '系统管理', '0', '', '', '_self', '1', '0', '1626845872', '0', '1'), ('3', '', ' 管理员管理', '1', 'fa fa-users', '/admin/admin/index', '_self', '1', '0', '1605337009', '1', '1'), ('4', '', '菜单管理', '1', 'fa fa-align-justify', '/admin/adminrule/index', '_self', '1', '0', '1640017490', '3', '1'), ('6', '', '基础功能', '0', '', '', '_self', '1', '1599463278', '1649926644', '18', '1'), ('7', '', '推品助手', '149', 'fa fa-life-saver', '', '_self', '1', '1599463674', '1648437994', '10', '1'), ('69', '', '好友管理', '6', 'fa fa-users', '', '_self', '0', '1603766642', '1650419615', '20', '1'), ('79', '', '被加好友应答', '69', '', '/admin/botfriend/beadded', '_self', '1', '1603767014', '1640366008', '10', '1'), ('105', '', '关键词回复', '156', '', '/admin/botgroup/keyword', '_self', '1', '1604904482', '1640365666', '9', '1'), ('106', '', '被动回复', '158', '', '/admin/reply/index', '_self', '1', '1604904505', '1640364369', '5', '1'), ('107', '', '角色管理', '1', 'fa fa-align-center', '/admin/admingroup/index', '_self', '1', '1604904540', '1605337113', '2', '1'), ('108', '', '定时群发', '156', '', '/admin/botgroup/sendbatch', '_self', '1', '1604904704', '1640366176', '8', '1'), ('109', '', '私聊自动回复', '69', '', '/admin/botfriend/reply', '_self', '1', '1604904735', '1640366076', '9', '1'), ('148', '', '配置管理', '1', 'fa fa-cogs', '/admin/setting/index', '_self', '1', '1624803694', '1640366685', '4', '1'), ('149', '', '应用中心', '0', '', '', '_self', '1', '1625712595', '1648437554', '10', '1'), ('150', '', '渠道管理', '7', '', '/admin/tpzschannel/index', '_self', '1', '1625712631', '1649233035', '5', '1'), ('151', '', '机器人列表', '162', '', '/admin/bot/index', '_self', '1', '1625725311', '1640018227', '5', '1'), ('152', '', '通讯录', '6', 'fa fa-address-book', '', '_self', '1', '1625737689', '1650419650', '20', '1'), ('153', '', '电商联盟', '7', '', '/admin/tpzsunion/index', '_self', '1', '1625737734', '1648523931', '1', '1'), ('155', '', '定时群发', '69', '', '/admin/botfriend/sendbatch', '_self', '1', '1626505323', '1640366153', '8', '1'), ('156', '', '社群管理', '6', 'fa fa-object-group', '', '_self', '0', '1626505381', '1650419622', '10', '1'), ('157', '', '关键词回复', '158', '', '/admin/keyword/index', '_self', '1', '1626505434', '1650262233', '10', '1'), ('158', '', '快捷设置', '6', 'fa fa-cogs', '', '_self', '1', '1626505455', '1640365845', '40', '1'), ('159', '', '消息群发', '158', '', '/admin/task/index', '_self', '1', '1626505494', '1650459935', '1', '1'), ('160', '', '好友列表', '152', '', '/admin/botfriend/index', '_self', '1', '1626505753', '1646837048', '1', '1'), ('162', '', '机器人管理', '169', 'fa fa-android', '', '_self', '1', '1626507555', '1640844683', '50', '1'), ('166', '', '群组列表', '152', '', '/admin/botgroup/index', '_self', '1', '1626509815', '1646837078', '9', '1'), ('167', '', '入群欢迎语', '156', '', '/admin/botgroup/welcome', '_self', '1', '1626593222', '1640365543', '20', '1'), ('168', '', '素材库', '169', 'fa fa-book', '/admin/mediatext/index', '_self', '1', '1640786676', '1649837988', '1', '1'), ('169', '', '全局设置', '0', '', '', '_self', '1', '1640844673', '1650466761', '20', '1'), ('170', '', '基础设置', '7', '', '/admin/tpzsconfig/index', '_self', '1', '1646839737', '1648534905', '0', '1'), ('171', '', '采品群', '7', '', '/admin/tpzsgather/index', '_self', '1', '1646839758', '1648546385', '45', '1'), ('172', '', '机器人负责群', '7', '', '/admin/tpzsteam/index', '_self', '1', '1646839780', '1648534885', '40', '1'), ('173', '', '发送列表', '7', '', '/admin/tpzstask/index', '_self', '1', '1646839807', '1648439722', '50', '1');
COMMIT;

-- ----------------------------
--  Table structure for `ky_bot`
-- ----------------------------
DROP TABLE IF EXISTS `ky_bot`;
CREATE TABLE `ky_bot` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL DEFAULT '1' COMMENT '所属部门',
  `title` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '账号',
  `app_key` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '密码',
  `nickname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '微信用户昵称',
  `headimgurl` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '新增时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `uuid` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '微信号或会话ID',
  `uin` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '当前登录微信用户的id',
  `is_current` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否当前操作',
  `login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录时间',
  `protocol` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'vlw' COMMENT '使用协议： vlw，web',
  `url` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '接口地址',
  `alive` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否登录',
  `free` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否免费',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `admin_id` (`admin_id`),
  KEY `app_key` (`app_key`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='个微机器人';


-- ----------------------------
--  Table structure for `ky_bot_groupmember`
-- ----------------------------
DROP TABLE IF EXISTS `ky_bot_groupmember`;
CREATE TABLE `ky_bot_groupmember` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '机器人id',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '群id',
  `wxid` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '成员wxid',
  `nickname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '成员微信昵称',
  `group_nickname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '群内昵称',
  `username` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '微信号',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '新增时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `username` (`wxid`,`group_id`) USING BTREE,
  KEY `group_id` (`group_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=329 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='群成员';

-- ----------------------------
--  Table structure for `ky_bot_member`
-- ----------------------------
DROP TABLE IF EXISTS `ky_bot_member`;
CREATE TABLE `ky_bot_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uin` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '机器人id',
  `alias` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '别名',
  `nickname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '微信用户昵称',
  `remark_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注名',
  `username` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'xp:  用户微信号\r\nweb:当前登录下的好友唯一标识（每次登录不一样）',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '新增时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'friend' COMMENT 'friend、group、mp',
  `headimgurl` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `wxid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '微信id',
  `internal` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '企业微信区分内部和外部',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `wxid_botid` (`uin`,`wxid`) USING BTREE,
  KEY `uin` (`uin`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4908 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='通讯录';

-- ----------------------------
--  Table structure for `ky_keyword`
-- ----------------------------
DROP TABLE IF EXISTS `ky_keyword`;
CREATE TABLE `ky_keyword` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL,
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0',
  `keyword` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '关键词',
  `media_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '回复类型',
  `media_id` int(10) NOT NULL DEFAULT '0' COMMENT '触发应用',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1正常 0禁用',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `wxids` text COLLATE utf8mb4_unicode_ci COMMENT '作用对象，好友wxid或群wxid',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`keyword`,`bot_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT COMMENT='被动回复';


-- ----------------------------
--  Table structure for `ky_media_file_1`
-- ----------------------------
DROP TABLE IF EXISTS `ky_media_file_1`;
CREATE TABLE `ky_media_file_1` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` varchar(255) NOT NULL DEFAULT '' COMMENT '格式',
  `location` varchar(20) NOT NULL DEFAULT 'qiniu',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='文件素材库';

-- ----------------------------
--  Table structure for `ky_media_file_2`
-- ----------------------------
DROP TABLE IF EXISTS `ky_media_file_2`;
CREATE TABLE `ky_media_file_2` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` varchar(255) NOT NULL DEFAULT '' COMMENT '格式',
  `location` varchar(20) NOT NULL DEFAULT 'qiniu',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='文件素材库';

-- ----------------------------
--  Table structure for `ky_media_file_3`
-- ----------------------------
DROP TABLE IF EXISTS `ky_media_file_3`;
CREATE TABLE `ky_media_file_3` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` varchar(255) NOT NULL DEFAULT '' COMMENT '格式',
  `location` varchar(20) NOT NULL DEFAULT 'qiniu',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='文件素材库';

-- ----------------------------
--  Table structure for `ky_media_file_4`
-- ----------------------------
DROP TABLE IF EXISTS `ky_media_file_4`;
CREATE TABLE `ky_media_file_4` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` varchar(255) NOT NULL DEFAULT '' COMMENT '格式',
  `location` varchar(20) NOT NULL DEFAULT 'qiniu',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='文件素材库';

-- ----------------------------
--  Table structure for `ky_media_file_5`
-- ----------------------------
DROP TABLE IF EXISTS `ky_media_file_5`;
CREATE TABLE `ky_media_file_5` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` varchar(255) NOT NULL DEFAULT '' COMMENT '格式',
  `location` varchar(20) NOT NULL DEFAULT 'qiniu',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='文件素材库';

-- ----------------------------
--  Table structure for `ky_media_image_1`
-- ----------------------------
DROP TABLE IF EXISTS `ky_media_image_1`;
CREATE TABLE `ky_media_image_1` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` enum('bmp','jpg','jpeg','png','gif') NOT NULL DEFAULT 'jpg' COMMENT '图片格式',
  `location` varchar(50) NOT NULL DEFAULT '' COMMENT '位置Local，Qiniu',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='图片素材库';

-- ----------------------------
--  Table structure for `ky_media_image_2`
-- ----------------------------
DROP TABLE IF EXISTS `ky_media_image_2`;
CREATE TABLE `ky_media_image_2` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` enum('bmp','jpg','jpeg','png','gif') NOT NULL DEFAULT 'jpg' COMMENT '图片格式',
  `location` varchar(50) NOT NULL DEFAULT '' COMMENT '位置Local，Qiniu',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='图片素材库';


-- ----------------------------
--  Table structure for `ky_media_image_3`
-- ----------------------------
DROP TABLE IF EXISTS `ky_media_image_3`;
CREATE TABLE `ky_media_image_3` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` enum('bmp','jpg','jpeg','png','gif') NOT NULL DEFAULT 'jpg' COMMENT '图片格式',
  `location` varchar(50) NOT NULL DEFAULT '' COMMENT '位置Local，Qiniu',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='图片素材库';

-- ----------------------------
--  Table structure for `ky_media_image_4`
-- ----------------------------
DROP TABLE IF EXISTS `ky_media_image_4`;
CREATE TABLE `ky_media_image_4` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` enum('bmp','jpg','jpeg','png','gif') NOT NULL DEFAULT 'jpg' COMMENT '图片格式',
  `location` varchar(50) NOT NULL DEFAULT '' COMMENT '位置Local，Qiniu',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='图片素材库';

-- ----------------------------
--  Table structure for `ky_media_image_5`
-- ----------------------------
DROP TABLE IF EXISTS `ky_media_image_5`;
CREATE TABLE `ky_media_image_5` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` enum('bmp','jpg','jpeg','png','gif') NOT NULL DEFAULT 'jpg' COMMENT '图片格式',
  `location` varchar(50) NOT NULL DEFAULT '' COMMENT '位置Local，Qiniu',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='图片素材库';

-- ----------------------------
--  Table structure for `ky_media_link_1`
-- ----------------------------
DROP TABLE IF EXISTS `ky_media_link_1`;
CREATE TABLE `ky_media_link_1` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '图片url',
  `image_url` varchar(200) NOT NULL,
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`) USING BTREE,
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='分享链接素材库';

-- ----------------------------
--  Table structure for `ky_media_link_2`
-- ----------------------------
DROP TABLE IF EXISTS `ky_media_link_2`;
CREATE TABLE `ky_media_link_2` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '图片url',
  `image_url` varchar(200) NOT NULL,
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`) USING BTREE,
  KEY `title` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='分享链接素材库';

-- ----------------------------
--  Table structure for `ky_media_link_3`
-- ----------------------------
DROP TABLE IF EXISTS `ky_media_link_3`;
CREATE TABLE `ky_media_link_3` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '图片url',
  `image_url` varchar(200) NOT NULL,
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`) USING BTREE,
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='分享链接素材库';

-- ----------------------------
--  Table structure for `ky_media_link_4`
-- ----------------------------
DROP TABLE IF EXISTS `ky_media_link_4`;
CREATE TABLE `ky_media_link_4` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '图片url',
  `image_url` varchar(200) NOT NULL,
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`) USING BTREE,
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='分享链接素材库';

-- ----------------------------
--  Table structure for `ky_media_link_5`
-- ----------------------------
DROP TABLE IF EXISTS `ky_media_link_5`;
CREATE TABLE `ky_media_link_5` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '图片url',
  `image_url` varchar(200) NOT NULL,
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`) USING BTREE,
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='分享链接素材库';

-- ----------------------------
--  Table structure for `ky_media_text_1`
-- ----------------------------
DROP TABLE IF EXISTS `ky_media_text_1`;
CREATE TABLE `ky_media_text_1` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '备注',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文本内容',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='文本素材';

-- ----------------------------
--  Table structure for `ky_media_text_2`
-- ----------------------------
DROP TABLE IF EXISTS `ky_media_text_2`;
CREATE TABLE `ky_media_text_2` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '备注',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文本内容',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='文本素材';

-- ----------------------------
--  Table structure for `ky_media_text_3`
-- ----------------------------
DROP TABLE IF EXISTS `ky_media_text_3`;
CREATE TABLE `ky_media_text_3` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '备注',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文本内容',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='文本素材';

-- ----------------------------
--  Table structure for `ky_media_text_4`
-- ----------------------------
DROP TABLE IF EXISTS `ky_media_text_4`;
CREATE TABLE `ky_media_text_4` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '备注',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文本内容',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='文本素材';

-- ----------------------------
--  Table structure for `ky_media_text_5`
-- ----------------------------
DROP TABLE IF EXISTS `ky_media_text_5`;
CREATE TABLE `ky_media_text_5` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '备注',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文本内容',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='文本素材';

-- ----------------------------
--  Table structure for `ky_media_video_1`
-- ----------------------------
DROP TABLE IF EXISTS `ky_media_video_1`;
CREATE TABLE `ky_media_video_1` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` varchar(8) NOT NULL DEFAULT '' COMMENT '格式',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='视频素材库';

-- ----------------------------
--  Table structure for `ky_media_video_2`
-- ----------------------------
DROP TABLE IF EXISTS `ky_media_video_2`;
CREATE TABLE `ky_media_video_2` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` varchar(8) NOT NULL DEFAULT '' COMMENT '格式',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='视频素材库';


-- ----------------------------
--  Table structure for `ky_media_video_3`
-- ----------------------------
DROP TABLE IF EXISTS `ky_media_video_3`;
CREATE TABLE `ky_media_video_3` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` varchar(8) NOT NULL DEFAULT '' COMMENT '格式',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='视频素材库';

-- ----------------------------
--  Table structure for `ky_media_video_4`
-- ----------------------------
DROP TABLE IF EXISTS `ky_media_video_4`;
CREATE TABLE `ky_media_video_4` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` varchar(8) NOT NULL DEFAULT '' COMMENT '格式',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='视频素材库';

-- ----------------------------
--  Table structure for `ky_media_video_5`
-- ----------------------------
DROP TABLE IF EXISTS `ky_media_video_5`;
CREATE TABLE `ky_media_video_5` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` varchar(8) NOT NULL DEFAULT '' COMMENT '格式',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='视频素材库';

-- ----------------------------
--  Table structure for `ky_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ky_reply`;
CREATE TABLE `ky_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL,
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0',
  `event` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'beadded' COMMENT '事件/消息类型',
  `media_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '回复类型',
  `media_id` int(10) NOT NULL DEFAULT '0' COMMENT '触发应用',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1正常 0禁用',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `wxids` text COLLATE utf8mb4_unicode_ci COMMENT '作用对象，好友wxid或群wxid',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`event`,`bot_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT COMMENT='被动回复';

-- ----------------------------
--  Table structure for `ky_task`
-- ----------------------------
DROP TABLE IF EXISTS `ky_task`;
CREATE TABLE `ky_task` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '机器人id',
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '任务名称',
  `wxids` text COLLATE utf8mb4_unicode_ci COMMENT '接收者wxids，逗号隔开',
  `media_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `media_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '素材ID',
  `plan_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '计划发送时间',
  `complete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务结束时间',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT=' 消息群发表';

-- ----------------------------
--  Table structure for `ky_tpzs_channel`
-- ----------------------------
DROP TABLE IF EXISTS `ky_tpzs_channel`;
CREATE TABLE `ky_tpzs_channel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `contact_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `contact_tel` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ckid` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创客的id',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='渠道';

-- ----------------------------
--  Table structure for `ky_tpzs_config`
-- ----------------------------
DROP TABLE IF EXISTS `ky_tpzs_config`;
CREATE TABLE `ky_tpzs_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0',
  `key` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `value` text COLLATE utf8mb4_unicode_ci,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='推品助手配置表';

-- ----------------------------
--  Table structure for `ky_tpzs_gather`
-- ----------------------------
DROP TABLE IF EXISTS `ky_tpzs_gather`;
CREATE TABLE `ky_tpzs_gather` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL,
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'bot id',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '群聊id',
  `officer` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '指挥官',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='群聊-推广位关联表';

-- ----------------------------
--  Table structure for `ky_tpzs_grouppos`
-- ----------------------------
DROP TABLE IF EXISTS `ky_tpzs_grouppos`;
CREATE TABLE `ky_tpzs_grouppos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '群聊id',
  `position_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'league_position  id',
  `channel_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'channel id',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='群聊-推广位关联表';


-- ----------------------------
--  Table structure for `ky_tpzs_position`
-- ----------------------------
DROP TABLE IF EXISTS `ky_tpzs_position`;
CREATE TABLE `ky_tpzs_position` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `union_id` int(10) unsigned NOT NULL DEFAULT '0',
  `position_id` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1手动2拉取',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `jd_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '站点类型 1.网站推广位2.APP推广位3.导购媒体推广位4.聊天工具推广位',
  `siteid` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '站点ID，如网站ID/appID/snsID',
  `default` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '默认',
  `union_type` tinyint(1) unsigned NOT NULL DEFAULT '3' COMMENT '1cps推广位；2cpc推广位；3私域推广位，上限5000个，不在联盟后台展示，无对应 PID；4联盟后台推广位，上限500个，会在推客联盟后台展示，自动生成对应PID，可用于内容平台推广',
  PRIMARY KEY (`id`),
  UNIQUE KEY `position_id` (`position_id`),
  KEY `league_id` (`union_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='推广位id';

-- ----------------------------
--  Table structure for `ky_tpzs_task`
-- ----------------------------
DROP TABLE IF EXISTS `ky_tpzs_task`;
CREATE TABLE `ky_tpzs_task` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '机器人id',
  `members` text COLLATE utf8mb4_unicode_ci COMMENT '接收者wxids，逗号隔开',
  `sku_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品id',
  `content` text COLLATE utf8mb4_unicode_ci COMMENT '文本内容',
  `img` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图片',
  `plan_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '计划发送时间',
  `complete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务结束时间',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'basic' COMMENT '商品来源：basic、ck、jd',
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=423 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='推品助手任务表';


-- ----------------------------
--  Table structure for `ky_tpzs_team`
-- ----------------------------
DROP TABLE IF EXISTS `ky_tpzs_team`;
CREATE TABLE `ky_tpzs_team` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '机器人id',
  `groups` text COLLATE utf8mb4_unicode_ci COMMENT '群wxids',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='机器人负责群聊';


-- ----------------------------
--  Table structure for `ky_tpzs_union`
-- ----------------------------
DROP TABLE IF EXISTS `ky_tpzs_union`;
CREATE TABLE `ky_tpzs_union` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `unionid` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '联盟id',
  `app_key` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '接口key',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0',
  `default` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否默认',
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'jd' COMMENT 'jd,tb',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unionid` (`unionid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='联盟账号信息';

SET FOREIGN_KEY_CHECKS = 1;
