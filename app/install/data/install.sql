-- ----------------------------
-- Table structure for setting
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__setting`;
CREATE TABLE `__PREFIX__setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '标识',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `value` text COMMENT '配置值',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='站点配置';

INSERT INTO `__PREFIX__setting` VALUES ('1', 'site', '站点信息', '{\"version\":\"2.0.4\",\"company_title\":\"微精灵\",\"jd_appkey\":\"\",\"jd_appsecret\":\"\",\"jtt_appid\":\"\",\"jtt_appkey\":\"\"}', '1590290640', '1649899288'), ('2', 'upload', '附件设置', '{\"driver\":\"local\",\"qiniu_ak\":\"\",\"qiniu_sk\":\"\",\"qiniu_bucket\":\"\",\"qiniu_domain\":\"\",\"image_size\":\"3148000\",\"image_ext\":\"jpg,gif,png,jpeg\",\"file_size\":\"53000000\",\"file_ext\":\"jpg,gif,png,jpeg,zip,rar,tar,gz,7z,doc,docx,txt,xml,mp3,mp4,xls,xlsx,pdf\",\"voice_size\":\"2048000\",\"voice_ext\":\"mp3,wma,wav,amr\",\"video_size\":\"50240000\",\"video_ext\":\"mp4,flv,mov\"}', '1590292316', '1646835370');

-- ----------------------------
-- Table structure for ky_admin
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__admin`;
CREATE TABLE `__PREFIX__admin` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for ky_admin_group
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__admin_group`;
CREATE TABLE `__PREFIX__admin_group` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ky_admin_group
-- ----------------------------
INSERT INTO `__PREFIX__admin_group` VALUES ('1', 'super_admin', '超级管理员', '1', '7,160,149,151,150,152,166,153,155,69,79,156,157,158,159,1,3,105,106,107,108,109,4,6,162,148', '0', '0', '1445158837', '拥有系统最高管理权限', '1626581117');

-- ----------------------------
-- Table structure for ky_admin_rule
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__admin_rule`;
CREATE TABLE `__PREFIX__admin_rule` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ky_admin_rule
-- ----------------------------
INSERT INTO `__PREFIX__admin_rule` VALUES ('1', '', '系统管理', '0', '', '', '_self', '1', '0', '1626845872', '0', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('3', '', ' 管理员管理', '1', 'fa fa-users', '/admin/admin/index', '_self', '1', '0', '1605337009', '1', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('4', '', '菜单管理', '1', 'fa fa-align-justify', '/admin/adminrule/index', '_self', '1', '0', '1640017490', '3', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('6', '', '基础功能', '0', '', '', '_self', '1', '1599463278', '1649926644', '18', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('7', '', '推品助手', '149', 'fa fa-life-saver', '', '_self', '1', '1599463674', '1648437994', '10', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('69', '', '好友管理', '6', 'fa fa-users', '', '_self', '0', '1603766642', '1650419615', '20', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('79', '', '触发设置', '108', '', '/admin/yhqreply/index', '_self', '1', '1603767014', '1655878678', '5', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('105', '', '消息转播', '158', '', '/admin/forward/index', '_self', '1', '1604904482', '1655799164', '1', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('106', '', '被动回复', '158', '', '/admin/reply/index', '_self', '1', '1604904505', '1640364369', '5', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('107', '', '角色管理', '1', 'fa fa-align-center', '/admin/admingroup/index', '_self', '1', '1604904540', '1605337113', '2', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('108', '', '优惠券助手', '149', 'fa fa-codepen', '', '_self', '1', '1604904704', '1655878454', '1', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('109', '', '优惠券管理', '108', '', '/admin/yhqcoupon/index', '_self', '1', '1604904735', '1655878586', '1', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('148', '', '配置管理', '1', 'fa fa-cogs', '/admin/setting/index', '_self', '1', '1624803694', '1640366685', '4', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('149', '', '应用中心', '0', '', '', '_self', '1', '1625712595', '1648437554', '10', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('150', '', '渠道管理', '7', '', '/admin/tpzschannel/index', '_self', '1', '1625712631', '1649233035', '5', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('151', '', '机器人列表', '162', '', '/admin/bot/index', '_self', '1', '1625725311', '1640018227', '5', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('152', '', '通讯录', '6', 'fa fa-address-book', '', '_self', '1', '1625737689', '1650419650', '20', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('153', '', '电商联盟', '7', '', '/admin/tpzsunion/index', '_self', '1', '1625737734', '1648523931', '1', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('155', '', '设置', '108', '', '/admin/yhqconfig/index', '_self', '1', '1626505323', '1655878696', '10', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('156', '', '群管理', '6', 'fa fa-object-group', '', '_self', '1', '1626505381', '1655732578', '10', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('157', '', '关键词回复', '158', '', '/admin/keyword/index', '_self', '1', '1626505434', '1650262233', '10', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('158', '', '快捷设置', '6', 'fa fa-cogs', '', '_self', '1', '1626505455', '1640365845', '40', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('159', '', '消息群发', '158', '', '/admin/task/index', '_self', '1', '1626505494', '1650459935', '1', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('160', '', '好友列表', '152', '', '/admin/botfriend/index', '_self', '1', '1626505753', '1646837048', '1', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('162', '', '机器人管理', '169', 'fa fa-android', '', '_self', '1', '1626507555', '1640844683', '50', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('166', '', '群组列表', '152', '', '/admin/botgroup/index', '_self', '1', '1626509815', '1646837078', '9', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('167', '', '群规则', '156', '', '/admin/rule/index', '_self', '1', '1626593222', '1650952528', '20', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('168', '', '素材库', '169', 'fa fa-book', '/admin/mediatext/index', '_self', '1', '1640786676', '1649837988', '1', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('169', '', '全局设置', '0', '', '', '_self', '1', '1640844673', '1650466761', '20', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('170', '', '基础设置', '7', '', '/admin/tpzsconfig/index', '_self', '1', '1646839737', '1648534905', '0', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('171', '', '采品群', '7', '', '/admin/tpzsgather/index', '_self', '1', '1646839758', '1648546385', '45', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('172', '', '机器人负责群', '7', '', '/admin/tpzsteam/index', '_self', '0', '1646839780', '1653879660', '40', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('173', '', '发送列表', '7', '', '/admin/tpzstask/index', '_self', '1', '1646839807', '1648439722', '50', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('174', '', '汉字助手', '149', 'fa fa-anchor', '', '_self', '1', '1653556679', '1653556679', '1', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('175', '', '设置', '174', '', '/admin/hanziconfig/index', '_self', '1', '1653556715', '1653556715', '1', '1');
INSERT INTO `__PREFIX__admin_rule` VALUES ('176', '', '系统升级', '1', 'fa fa-cloud-upload', '/admin/upgrade/index', '_self', '1', '1657205545', '1657205566', '1', '1');

-- ----------------------------
-- Table structure for ky_bot
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__bot`;
CREATE TABLE `__PREFIX__bot` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='个微机器人';

-- ----------------------------
-- Table structure for ky_bot_groupmember
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__bot_groupmember`;
CREATE TABLE `__PREFIX__bot_groupmember` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='群成员';

-- ----------------------------
-- Table structure for ky_bot_member
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__bot_member`;
CREATE TABLE `__PREFIX__bot_member` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='通讯录';

-- ----------------------------
-- Table structure for ky_forward
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__forward`;
CREATE TABLE `__PREFIX__forward` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL,
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'bot id',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '群聊id',
  `officer` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '指挥官',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `wxids` text COLLATE utf8mb4_unicode_ci COMMENT '转发对象',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='群聊-推广位关联表';

-- ----------------------------
-- Table structure for ky_group_rule
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__group_rule`;
CREATE TABLE `__PREFIX__group_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0',
  `wxids` text COLLATE utf8mb4_unicode_ci,
  `rule` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'rm' COMMENT 'rm移除群',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `value` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '设置值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='群规则设置';

-- ----------------------------
-- Table structure for ky_hanzi_config
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__hanzi_config`;
CREATE TABLE `__PREFIX__hanzi_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0',
  `key` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `value` text COLLATE utf8mb4_unicode_ci,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `wxids` text COLLATE utf8mb4_unicode_ci COMMENT '指定对象',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='推品助手配置表';

-- ----------------------------
-- Table structure for ky_keyword
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__keyword`;
CREATE TABLE `__PREFIX__keyword` (
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
  `sort` int(6) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `unique` (`keyword`,`bot_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT COMMENT='被动回复';

-- ----------------------------
-- Table structure for ky_media_file_1
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_file_1`;
CREATE TABLE `__PREFIX__media_file_1` (
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
-- Table structure for ky_media_file_2
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_file_2`;
CREATE TABLE `__PREFIX__media_file_2` (
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
-- Table structure for ky_media_file_3
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_file_3`;
CREATE TABLE `__PREFIX__media_file_3` (
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
-- Table structure for ky_media_file_4
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_file_4`;
CREATE TABLE `__PREFIX__media_file_4` (
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
-- Table structure for ky_media_file_5
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_file_5`;
CREATE TABLE `__PREFIX__media_file_5` (
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
-- Table structure for ky_media_image_1
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_image_1`;
CREATE TABLE `__PREFIX__media_image_1` (
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
-- Table structure for ky_media_image_2
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_image_2`;
CREATE TABLE `__PREFIX__media_image_2` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='图片素材库';

-- ----------------------------
-- Table structure for ky_media_image_3
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_image_3`;
CREATE TABLE `__PREFIX__media_image_3` (
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
-- Table structure for ky_media_image_4
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_image_4`;
CREATE TABLE `__PREFIX__media_image_4` (
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
-- Table structure for ky_media_image_5
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_image_5`;
CREATE TABLE `__PREFIX__media_image_5` (
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
-- Table structure for ky_media_link_1
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_link_1`;
CREATE TABLE `__PREFIX__media_link_1` (
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
-- Table structure for ky_media_link_2
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_link_2`;
CREATE TABLE `__PREFIX__media_link_2` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='分享链接素材库';

-- ----------------------------
-- Table structure for ky_media_link_3
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_link_3`;
CREATE TABLE `__PREFIX__media_link_3` (
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
-- Table structure for ky_media_link_4
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_link_4`;
CREATE TABLE `__PREFIX__media_link_4` (
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
-- Table structure for ky_media_link_5
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_link_5`;
CREATE TABLE `__PREFIX__media_link_5` (
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
-- Table structure for ky_media_text_1
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_text_1`;
CREATE TABLE `__PREFIX__media_text_1` (
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
-- Table structure for ky_media_text_2
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_text_2`;
CREATE TABLE `__PREFIX__media_text_2` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '备注',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文本内容',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`admin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='文本素材';

-- ----------------------------
-- Table structure for ky_media_text_3
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_text_3`;
CREATE TABLE `__PREFIX__media_text_3` (
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
-- Table structure for ky_media_text_4
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_text_4`;
CREATE TABLE `__PREFIX__media_text_4` (
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
-- Table structure for ky_media_text_5
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_text_5`;
CREATE TABLE `__PREFIX__media_text_5` (
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
-- Table structure for ky_media_video_1
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_video_1`;
CREATE TABLE `__PREFIX__media_video_1` (
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
-- Table structure for ky_media_video_2
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_video_2`;
CREATE TABLE `__PREFIX__media_video_2` (
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
-- Table structure for ky_media_video_3
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_video_3`;
CREATE TABLE `__PREFIX__media_video_3` (
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
-- Table structure for ky_media_video_4
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_video_4`;
CREATE TABLE `__PREFIX__media_video_4` (
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
-- Table structure for ky_media_video_5
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_video_5`;
CREATE TABLE `__PREFIX__media_video_5` (
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
-- Table structure for ky_reply
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__reply`;
CREATE TABLE `__PREFIX__reply` (
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
  `sort` int(6) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `unique` (`event`,`bot_id`,`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT COMMENT='被动回复';

-- ----------------------------
-- Table structure for ky_task
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__task`;
CREATE TABLE `__PREFIX__task` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '机器人id',
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '任务名称',
  `wxids` longblob NULL COMMENT '接收者wxids，逗号隔开',
  `plan_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '计划发送时间',
  `complete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务结束时间',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `medias` text COLLATE utf8mb4_unicode_ci COMMENT '回复内容',
  `circle` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1单次 2每天发 ',
  `plan_hour` char(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '00:00' COMMENT '具体的发送时段',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT=' 消息群发表';

-- ----------------------------
-- Table structure for ky_tpzs_channel
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__tpzs_channel`;
CREATE TABLE `__PREFIX__tpzs_channel` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='渠道';

-- ----------------------------
-- Table structure for ky_tpzs_config
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__tpzs_config`;
CREATE TABLE `__PREFIX__tpzs_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0',
  `key` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `value` text COLLATE utf8mb4_unicode_ci,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='推品助手配置表';

-- ----------------------------
-- Table structure for ky_tpzs_gather
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__tpzs_gather`;
CREATE TABLE `__PREFIX__tpzs_gather` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL,
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'bot id',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '群聊id',
  `officer` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '指挥官',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `wxids` text COLLATE utf8mb4_unicode_ci COMMENT '转发对象',
  `universal` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要转链',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='群聊-推广位关联表';

-- ----------------------------
-- Table structure for ky_tpzs_grouppos
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__tpzs_grouppos`;
CREATE TABLE `__PREFIX__tpzs_grouppos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '群聊id',
  `position_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'league_position  id',
  `channel_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'channel id',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='群聊-推广位关联表';

-- ----------------------------
-- Table structure for ky_tpzs_position
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__tpzs_position`;
CREATE TABLE `__PREFIX__tpzs_position` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='推广位id';

-- ----------------------------
-- Table structure for ky_tpzs_task
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__tpzs_task`;
CREATE TABLE `__PREFIX__tpzs_task` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='推品助手任务表';

-- ----------------------------
-- Table structure for ky_tpzs_team
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__tpzs_team`;
CREATE TABLE `__PREFIX__tpzs_team` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '机器人id',
  `groups` text COLLATE utf8mb4_unicode_ci COMMENT '群wxids',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='机器人负责群聊';

-- ----------------------------
-- Table structure for ky_tpzs_union
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__tpzs_union`;
CREATE TABLE `__PREFIX__tpzs_union` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='联盟账号信息';

-- ----------------------------
-- Table structure for ky_whiteid
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__whiteid`;
CREATE TABLE `__PREFIX__whiteid` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0',
  `group_wxid` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '群',
  `wxids` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='白名单';

-- ----------------------------
-- Table structure for ky_yhq_code
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__yhq_code`;
CREATE TABLE `__PREFIX__yhq_code` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `coupon_id` int(10) unsigned NOT NULL DEFAULT '0',
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `code_url` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '链接',
  `wxid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '领取人',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `create_time` int(10) unsigned NOT NULL,
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `send_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='券码';

-- ----------------------------
-- Table structure for ky_yhq_config
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__yhq_config`;
CREATE TABLE `__PREFIX__yhq_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0',
  `key` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `value` text COLLATE utf8mb4_unicode_ci,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='推品助手配置表';

-- ----------------------------
-- Table structure for ky_yhq_coupon
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__yhq_coupon`;
CREATE TABLE `__PREFIX__yhq_coupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `create_time` int(10) unsigned NOT NULL,
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='优惠券';

-- ----------------------------
-- Table structure for ky_yhq_reply
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__yhq_reply`;
CREATE TABLE `__PREFIX__yhq_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL,
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0',
  `event` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'beadded' COMMENT '事件/消息类型',
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '回复类型',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1正常 0禁用',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `wxids` text COLLATE utf8mb4_unicode_ci COMMENT '作用对象，好友wxid或群wxid',
  `sort` int(6) unsigned NOT NULL DEFAULT '0',
  `coupon_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `unique` (`event`,`bot_id`,`status`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT COMMENT='被动回复';


-- ----------------------------
-- 1.5.1
-- ----------------------------
ALTER TABLE `__PREFIX__keyword` ADD COLUMN `user_type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0不用 1好友 2群聊' AFTER `sort`;
DROP TABLE IF EXISTS `__PREFIX__tj_group`;
CREATE TABLE `__PREFIX__tj_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(10) unsigned NOT NULL DEFAULT '0',
  `day` char(10) NOT NULL DEFAULT '0000-00-00' COMMENT 'Y-m-d格式日期',
  `add_num` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '访问量',
  `decr_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '老访客访问量',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_id_day` (`group_id`,`day`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='群员数量统计';

#---v1.6.0---#
ALTER TABLE `__PREFIX__bot_member` CHANGE COLUMN `wxid` `wxid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '微信id';
ALTER TABLE `__PREFIX__bot_groupmember` CHANGE COLUMN `wxid` `wxid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '成员wxid';

-- ----------------------------
--  Table structure for `emoji_code`
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__emoji_code`;
CREATE TABLE `__PREFIX__emoji_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` varchar(30) NOT NULL,
  `name` varchar(40) NOT NULL,
  `unified` varchar(20) NOT NULL,
  `docomo` varchar(20) NOT NULL,
  `kddi` varchar(20) NOT NULL,
  `softbank` varchar(20) NOT NULL,
  `google` varchar(20) NOT NULL,
  `softb_unicode` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
--  Records of `emoji_code`
-- ----------------------------
INSERT INTO `__PREFIX__emoji_code` VALUES ('1', 'emoji2600', 'black sun with rays', 'U+2600', 'U+E63E', 'U+E488', 'U+E04A', 'U+FE000', '\\ue04a'), ('2', 'emoji2601', 'cloud', 'U+2601', 'U+E63F', 'U+E48D', 'U+E049', 'U+FE001', '\\ue049'), ('3', 'emoji2614', 'umbrella with rain drops', 'U+2614', 'U+E640', 'U+E48C', 'U+E04B', 'U+FE002', '\\ue04b'), ('4', 'emoji26c4', 'snowman without snow', 'U+26C4', 'U+E641', 'U+E485', 'U+E048', 'U+FE003', '\\ue048'), ('5', 'emoji26a1', 'high voltage sign', 'U+26A1', 'U+E642', 'U+E487', 'U+E13D', 'U+FE004', '\\ue13d'), ('6', 'emoji1f300', 'cyclone', 'U+1F300', 'U+E643', 'U+E469', 'U+E443', 'U+FE005', '\\ue443'), ('7', 'emoji1f301', 'foggy', 'U+1F301', 'U+E644', 'U+E598', '-', 'U+FE006', '-'), ('8', 'emoji1f302', 'closed umbrella', 'U+1F302', 'U+E645', 'U+EAE8', 'U+E43C', 'U+FE007', '\\ue43c'), ('9', 'emoji1f303', 'night with stars', 'U+1F303', 'U+E6B3', 'U+EAF1', 'U+E44B', 'U+FE008', '\\ue44b'), ('10', 'emoji1f304', 'sunrise over mountains', 'U+1F304', 'U+E63E', 'U+EAF4', 'U+E04D', 'U+FE009', '\\ue04d'), ('11', 'emoji1f305', 'sunrise', 'U+1F305', 'U+E63E', 'U+EAF4', 'U+E449', 'U+FE00A', '\\ue449'), ('12', 'emoji1f306', 'cityscape at dusk', 'U+1F306', '-', 'U+E5DA', 'U+E146', 'U+FE00B', '\\ue146'), ('13', 'emoji1f307', 'sunset over buildings', 'U+1F307', 'U+E63E', 'U+E5DA', 'U+E44A', 'U+FE00C', '\\ue44a'), ('14', 'emoji1f308', 'rainbow', 'U+1F308', '-', 'U+EAF2', 'U+E44C', 'U+FE00D', '\\ue44c'), ('15', 'emoji2744', 'snowflake', 'U+2744', '-', 'U+E48A', '-', 'U+FE00E', '-'), ('16', 'emoji26c5', 'sun behind cloud', 'U+26C5', 'U+E63E U+E63F', 'U+E48E', 'U+E04A U+E049', 'U+FE00F', '\\ue049'), ('17', 'emoji1f309', 'bridge at night', 'U+1F309', 'U+E6B3', 'U+E4BF', 'U+E44B', 'U+FE010', '\\ue44b'), ('18', 'emoji1f30a', 'water wave', 'U+1F30A', 'U+E73F', 'U+EB7C', 'U+E43E', 'U+FE038', '\\ue43e'), ('19', 'emoji1f30b', 'volcano', 'U+1F30B', '-', 'U+EB53', '-', 'U+FE03A', '-'), ('20', 'emoji1f30c', 'milky way', 'U+1F30C', 'U+E6B3', 'U+EB5F', 'U+E44B', 'U+FE03B', '\\ue44b'), ('21', 'emoji1f30f', 'earth globe asia-australia', 'U+1F30F', '-', 'U+E5B3', '-', 'U+FE039', '-'), ('22', 'emoji1f311', 'new moon symbol', 'U+1F311', 'U+E69C', 'U+E5A8', '-', 'U+FE011', '-'), ('23', 'emoji1f314', 'waxing gibbous moon symbol', 'U+1F314', 'U+E69D', 'U+E5A9', 'U+E04C', 'U+FE012', '\\ue04c'), ('24', 'emoji1f313', 'first quarter moon symbol', 'U+1F313', 'U+E69E', 'U+E5AA', 'U+E04C', 'U+FE013', '\\ue04c'), ('25', 'emoji1f319', 'crescent moon', 'U+1F319', 'U+E69F', 'U+E486', 'U+E04C', 'U+FE014', '\\ue04c'), ('26', 'emoji1f315', 'full moon symbol', 'U+1F315', 'U+E6A0', '-', '-', 'U+FE015', '-'), ('27', 'emoji1f31b', 'first quarter moon with face', 'U+1F31B', 'U+E69E', 'U+E489', 'U+E04C', 'U+FE016', '\\ue04c'), ('28', 'emoji1f31f', 'glowing star', 'U+1F31F', '-', 'U+E48B', 'U+E335', 'U+FEB69', '\\ue335'), ('29', 'emoji1f320', 'shooting star', 'U+1F320', '-', 'U+E468', '-', 'U+FEB6A', '-'), ('30', 'emoji1f550', 'clock face one oclock', 'U+1F550', 'U+E6BA', 'U+E594', 'U+E024', 'U+FE01E', '\\ue024'), ('31', 'emoji1f551', 'clock face two oclock', 'U+1F551', 'U+E6BA', 'U+E594', 'U+E025', 'U+FE01F', '\\ue025'), ('32', 'emoji1f552', 'clock face three oclock', 'U+1F552', 'U+E6BA', 'U+E594', 'U+E026', 'U+FE020', '\\ue026'), ('33', 'emoji1f553', 'clock face four oclock', 'U+1F553', 'U+E6BA', 'U+E594', 'U+E027', 'U+FE021', '\\ue027'), ('34', 'emoji1f554', 'clock face five oclock', 'U+1F554', 'U+E6BA', 'U+E594', 'U+E028', 'U+FE022', '\\ue028'), ('35', 'emoji1f555', 'clock face six oclock', 'U+1F555', 'U+E6BA', 'U+E594', 'U+E029', 'U+FE023', '\\ue029'), ('36', 'emoji1f556', 'clock face seven oclock', 'U+1F556', 'U+E6BA', 'U+E594', 'U+E02A', 'U+FE024', '\\ue02a'), ('37', 'emoji1f557', 'clock face eight oclock', 'U+1F557', 'U+E6BA', 'U+E594', 'U+E02B', 'U+FE025', '\\ue02b'), ('38', 'emoji1f558', 'clock face nine oclock', 'U+1F558', 'U+E6BA', 'U+E594', 'U+E02C', 'U+FE026', '\\ue02c'), ('39', 'emoji1f559', 'clock face ten oclock', 'U+1F559', 'U+E6BA', 'U+E594', 'U+E02D', 'U+FE027', '\\ue02d'), ('40', 'emoji1f55a', 'clock face eleven oclock', 'U+1F55A', 'U+E6BA', 'U+E594', 'U+E02E', 'U+FE028', '\\ue02e'), ('41', 'emoji1f55b', 'clock face twelve oclock', 'U+1F55B', 'U+E6BA', 'U+E594', 'U+E02F', 'U+FE029', '\\ue02f'), ('42', 'emoji231a', 'watch', 'U+231A', 'U+E71F', 'U+E57A', '-', 'U+FE01D', '-'), ('43', 'emoji231b', 'hourglass', 'U+231B', 'U+E71C', 'U+E57B', '-', 'U+FE01C', '-'), ('44', 'emoji23f0', 'alarm clock', 'U+23F0', 'U+E6BA', 'U+E594', 'U+E02D', 'U+FE02A', '\\ue02d'), ('45', 'emoji23f3', 'hourglass with flowing sand', 'U+23F3', 'U+E71C', 'U+E47C', '-', 'U+FE01B', '-'), ('46', 'emoji2648', 'aries', 'U+2648', 'U+E646', 'U+E48F', 'U+E23F', 'U+FE02B', '\\ue23f'), ('47', 'emoji2649', 'taurus', 'U+2649', 'U+E647', 'U+E490', 'U+E240', 'U+FE02C', '\\ue240'), ('48', 'emoji264a', 'gemini', 'U+264A', 'U+E648', 'U+E491', 'U+E241', 'U+FE02D', '\\ue241'), ('49', 'emoji264b', 'cancer', 'U+264B', 'U+E649', 'U+E492', 'U+E242', 'U+FE02E', '\\ue242'), ('50', 'emoji264c', 'leo', 'U+264C', 'U+E64A', 'U+E493', 'U+E243', 'U+FE02F', '\\ue243'), ('51', 'emoji264d', 'virgo', 'U+264D', 'U+E64B', 'U+E494', 'U+E244', 'U+FE030', '\\ue244'), ('52', 'emoji264e', 'libra', 'U+264E', 'U+E64C', 'U+E495', 'U+E245', 'U+FE031', '\\ue245'), ('53', 'emoji264f', 'scorpius', 'U+264F', 'U+E64D', 'U+E496', 'U+E246', 'U+FE032', '\\ue246'), ('54', 'emoji2650', 'sagittarius', 'U+2650', 'U+E64E', 'U+E497', 'U+E247', 'U+FE033', '\\ue247'), ('55', 'emoji2651', 'capricorn', 'U+2651', 'U+E64F', 'U+E498', 'U+E248', 'U+FE034', '\\ue248'), ('56', 'emoji2652', 'aquarius', 'U+2652', 'U+E650', 'U+E499', 'U+E249', 'U+FE035', '\\ue249'), ('57', 'emoji2653', 'pisces', 'U+2653', 'U+E651', 'U+E49A', 'U+E24A', 'U+FE036', '\\ue24a'), ('58', 'emoji26ce', 'ophiuchus', 'U+26CE', '-', 'U+E49B', 'U+E24B', 'U+FE037', '\\ue24b'), ('59', 'emoji1f340', 'four leaf clover', 'U+1F340', 'U+E741', 'U+E513', 'U+E110', 'U+FE03C', '\\ue110'), ('60', 'emoji1f337', 'tulip', 'U+1F337', 'U+E743', 'U+E4E4', 'U+E304', 'U+FE03D', '\\ue304'), ('61', 'emoji1f331', 'seedling', 'U+1F331', 'U+E746', 'U+EB7D', 'U+E110', 'U+FE03E', '\\ue110'), ('62', 'emoji1f341', 'maple leaf', 'U+1F341', 'U+E747', 'U+E4CE', 'U+E118', 'U+FE03F', '\\ue118'), ('63', 'emoji1f338', 'cherry blossom', 'U+1F338', 'U+E748', 'U+E4CA', 'U+E030', 'U+FE040', '\\ue030'), ('64', 'emoji1f339', 'rose', 'U+1F339', '-', 'U+E5BA', 'U+E032', 'U+FE041', '\\ue032'), ('65', 'emoji1f342', 'fallen leaf', 'U+1F342', 'U+E747', 'U+E5CD', 'U+E119', 'U+FE042', '\\ue119'), ('66', 'emoji1f343', 'leaf fluttering in wind', 'U+1F343', '-', 'U+E5CD', 'U+E447', 'U+FE043', '\\ue447'), ('67', 'emoji1f33a', 'hibiscus', 'U+1F33A', '-', 'U+EA94', 'U+E303', 'U+FE045', '\\ue303'), ('68', 'emoji1f33b', 'sunflower', 'U+1F33B', '-', 'U+E4E3', 'U+E305', 'U+FE046', '\\ue305'), ('69', 'emoji1f334', 'palm tree', 'U+1F334', '-', 'U+E4E2', 'U+E307', 'U+FE047', '\\ue307'), ('70', 'emoji1f335', 'cactus', 'U+1F335', '-', 'U+EA96', 'U+E308', 'U+FE048', '\\ue308'), ('71', 'emoji1f33e', 'ear of rice', 'U+1F33E', '-', '-', 'U+E444', 'U+FE049', '\\ue444'), ('72', 'emoji1f33d', 'ear of maize', 'U+1F33D', '-', 'U+EB36', '-', 'U+FE04A', '-'), ('73', 'emoji1f344', 'mushroom', 'U+1F344', '-', 'U+EB37', '-', 'U+FE04B', '-'), ('74', 'emoji1f330', 'chestnut', 'U+1F330', '-', 'U+EB38', '-', 'U+FE04C', '-'), ('75', 'emoji1f33c', 'blossom', 'U+1F33C', '-', 'U+EB49', 'U+E305', 'U+FE04D', '\\ue305'), ('76', 'emoji1f33f', 'herb', 'U+1F33F', 'U+E741', 'U+EB82', 'U+E110', 'U+FE04E', '\\ue110'), ('77', 'emoji1f352', 'cherries', 'U+1F352', 'U+E742', 'U+E4D2', '-', 'U+FE04F', '-'), ('78', 'emoji1f34c', 'banana', 'U+1F34C', 'U+E744', 'U+EB35', '-', 'U+FE050', '-'), ('79', 'emoji1f34e', 'red apple', 'U+1F34E', 'U+E745', 'U+EAB9', 'U+E345', 'U+FE051', '\\ue345'), ('80', 'emoji1f34a', 'tangerine', 'U+1F34A', '-', 'U+EABA', 'U+E346', 'U+FE052', '\\ue346'), ('81', 'emoji1f353', 'strawberry', 'U+1F353', '-', 'U+E4D4', 'U+E347', 'U+FE053', '\\ue347'), ('82', 'emoji1f349', 'watermelon', 'U+1F349', '-', 'U+E4CD', 'U+E348', 'U+FE054', '\\ue348'), ('83', 'emoji1f345', 'tomato', 'U+1F345', '-', 'U+EABB', 'U+E349', 'U+FE055', '\\ue349'), ('84', 'emoji1f346', 'aubergine', 'U+1F346', '-', 'U+EABC', 'U+E34A', 'U+FE056', '\\ue34a'), ('85', 'emoji1f348', 'melon', 'U+1F348', '-', 'U+EB32', '-', 'U+FE057', '-'), ('86', 'emoji1f34d', 'pineapple', 'U+1F34D', '-', 'U+EB33', '-', 'U+FE058', '-'), ('87', 'emoji1f347', 'grapes', 'U+1F347', '-', 'U+EB34', '-', 'U+FE059', '-'), ('88', 'emoji1f351', 'peach', 'U+1F351', '-', 'U+EB39', '-', 'U+FE05A', '-'), ('89', 'emoji1f34f', 'green apple', 'U+1F34F', 'U+E745', 'U+EB5A', 'U+E345', 'U+FE05B', '\\ue345'), ('90', 'emoji1f440', 'eyes', 'U+1F440', 'U+E691', 'U+E5A4', 'U+E419', 'U+FE190', '\\ue419'), ('91', 'emoji1f442', 'ear', 'U+1F442', 'U+E692', 'U+E5A5', 'U+E41B', 'U+FE191', '\\ue41b'), ('92', 'emoji1f443', 'nose', 'U+1F443', '-', 'U+EAD0', 'U+E41A', 'U+FE192', '\\ue41a'), ('93', 'emoji1f444', 'mouth', 'U+1F444', 'U+E6F9', 'U+EAD1', 'U+E41C', 'U+FE193', '\\ue41c'), ('94', 'emoji1f445', 'tongue', 'U+1F445', 'U+E728', 'U+EB47', 'U+E409', 'U+FE194', '\\ue409'), ('95', 'emoji1f484', 'lipstick', 'U+1F484', 'U+E710', 'U+E509', 'U+E31C', 'U+FE195', '\\ue31c'), ('96', 'emoji1f485', 'nail polish', 'U+1F485', '-', 'U+EAA0', 'U+E31D', 'U+FE196', '\\ue31d'), ('97', 'emoji1f486', 'face massage', 'U+1F486', '-', 'U+E50B', 'U+E31E', 'U+FE197', '\\ue31e'), ('98', 'emoji1f487', 'haircut', 'U+1F487', 'U+E675', 'U+EAA1', 'U+E31F', 'U+FE198', '\\ue31f'), ('99', 'emoji1f488', 'barber pole', 'U+1F488', '-', 'U+EAA2', 'U+E320', 'U+FE199', '\\ue320'), ('100', 'emoji1f464', 'bust in silhouette', 'U+1F464', 'U+E6B1', '-', '-', 'U+FE19A', '-'), ('101', 'emoji1f466', 'boy', 'U+1F466', 'U+E6F0', 'U+E4FC', 'U+E001', 'U+FE19B', '\\ue001'), ('102', 'emoji1f467', 'girl', 'U+1F467', 'U+E6F0', 'U+E4FA', 'U+E002', 'U+FE19C', '\\ue002'), ('103', 'emoji1f468', 'man', 'U+1F468', 'U+E6F0', 'U+E4FC', 'U+E004', 'U+FE19D', '\\ue004'), ('104', 'emoji1f469', 'woman', 'U+1F469', 'U+E6F0', 'U+E4FA', 'U+E005', 'U+FE19E', '\\ue005'), ('105', 'emoji1f46a', 'family', 'U+1F46A', '-', 'U+E501', '-', 'U+FE19F', '-'), ('106', 'emoji1f46b', 'man and woman holding hands', 'U+1F46B', '-', '-', 'U+E428', 'U+FE1A0', '\\ue428'), ('107', 'emoji1f46e', 'police officer', 'U+1F46E', '-', 'U+E5DD', 'U+E152', 'U+FE1A1', '\\ue152'), ('108', 'emoji1f46f', 'woman with bunny ears', 'U+1F46F', '-', 'U+EADB', 'U+E429', 'U+FE1A2', '\\ue429'), ('109', 'emoji1f470', 'bride with veil', 'U+1F470', '-', 'U+EAE9', '-', 'U+FE1A3', '-'), ('110', 'emoji1f471', 'person with blond hair', 'U+1F471', '-', 'U+EB13', 'U+E515', 'U+FE1A4', '\\ue515'), ('111', 'emoji1f472', 'man with gua pi mao', 'U+1F472', '-', 'U+EB14', 'U+E516', 'U+FE1A5', '\\ue516'), ('112', 'emoji1f473', 'man with turban', 'U+1F473', '-', 'U+EB15', 'U+E517', 'U+FE1A6', '\\ue517'), ('113', 'emoji1f474', 'older man', 'U+1F474', '-', 'U+EB16', 'U+E518', 'U+FE1A7', '\\ue518'), ('114', 'emoji1f475', 'older woman', 'U+1F475', '-', 'U+EB17', 'U+E519', 'U+FE1A8', '\\ue519'), ('115', 'emoji1f476', 'baby', 'U+1F476', '-', 'U+EB18', 'U+E51A', 'U+FE1A9', '\\ue51a'), ('116', 'emoji1f477', 'construction worker', 'U+1F477', '-', 'U+EB19', 'U+E51B', 'U+FE1AA', '\\ue51b'), ('117', 'emoji1f478', 'princess', 'U+1F478', '-', 'U+EB1A', 'U+E51C', 'U+FE1AB', '\\ue51c'), ('118', 'emoji1f479', 'japanese ogre', 'U+1F479', '-', 'U+EB44', '-', 'U+FE1AC', '-'), ('119', 'emoji1f47a', 'japanese goblin', 'U+1F47A', '-', 'U+EB45', '-', 'U+FE1AD', '-'), ('120', 'emoji1f47b', 'ghost', 'U+1F47B', '-', 'U+E4CB', 'U+E11B', 'U+FE1AE', '\\ue11b'), ('121', 'emoji1f47c', 'baby angel', 'U+1F47C', '-', 'U+E5BF', 'U+E04E', 'U+FE1AF', '\\ue04e'), ('122', 'emoji1f47d', 'extraterrestrial alien', 'U+1F47D', '-', 'U+E50E', 'U+E10C', 'U+FE1B0', '\\ue10c'), ('123', 'emoji1f47e', 'alien monster', 'U+1F47E', '-', 'U+E4EC', 'U+E12B', 'U+FE1B1', '\\ue12b'), ('124', 'emoji1f47f', 'imp', 'U+1F47F', '-', 'U+E4EF', 'U+E11A', 'U+FE1B2', '\\ue11a'), ('125', 'emoji1f480', 'skull', 'U+1F480', '-', 'U+E4F8', 'U+E11C', 'U+FE1B3', '\\ue11c'), ('126', 'emoji1f481', 'information desk person', 'U+1F481', '-', '-', 'U+E253', 'U+FE1B4', '\\ue253'), ('127', 'emoji1f482', 'guardsman', 'U+1F482', '-', '-', 'U+E51E', 'U+FE1B5', '\\ue51e'), ('128', 'emoji1f483', 'dancer', 'U+1F483', '-', 'U+EB1C', 'U+E51F', 'U+FE1B6', '\\ue51f'), ('129', 'emoji1f40c', 'snail', 'U+1F40C', 'U+E74E', 'U+EB7E', '-', 'U+FE1B9', '-'), ('130', 'emoji1f40d', 'snake', 'U+1F40D', '-', 'U+EB22', 'U+E52D', 'U+FE1D3', '\\ue52d'), ('131', 'emoji1f40e', 'horse', 'U+1F40E', 'U+E754', 'U+E4D8', 'U+E134', 'U+FE7DC', '\\ue134'), ('132', 'emoji1f414', 'chicken', 'U+1F414', '-', 'U+EB23', 'U+E52E', 'U+FE1D4', '\\ue52e'), ('133', 'emoji1f417', 'boar', 'U+1F417', '-', 'U+EB24', 'U+E52F', 'U+FE1D5', '\\ue52f'), ('134', 'emoji1f42b', 'bactrian camel', 'U+1F42B', '-', 'U+EB25', 'U+E530', 'U+FE1D6', '\\ue530'), ('135', 'emoji1f418', 'elephant', 'U+1F418', '-', 'U+EB1F', 'U+E526', 'U+FE1CC', '\\ue526'), ('136', 'emoji1f428', 'koala', 'U+1F428', '-', 'U+EB20', 'U+E527', 'U+FE1CD', '\\ue527'), ('137', 'emoji1f412', 'monkey', 'U+1F412', '-', 'U+E4D9', 'U+E528', 'U+FE1CE', '\\ue528'), ('138', 'emoji1f411', 'sheep', 'U+1F411', '-', 'U+E48F', 'U+E529', 'U+FE1CF', '\\ue529'), ('139', 'emoji1f419', 'octopus', 'U+1F419', '-', 'U+E5C7', 'U+E10A', 'U+FE1C5', '\\ue10a'), ('140', 'emoji1f41a', 'spiral shell', 'U+1F41A', '-', 'U+EAEC', 'U+E441', 'U+FE1C6', '\\ue441'), ('141', 'emoji1f41b', 'bug', 'U+1F41B', '-', 'U+EB1E', 'U+E525', 'U+FE1CB', '\\ue525'), ('142', 'emoji1f41c', 'ant', 'U+1F41C', '-', 'U+E4DD', '-', 'U+FE1DA', '-'), ('143', 'emoji1f41d', 'honeybee', 'U+1F41D', '-', 'U+EB57', '-', 'U+FE1E1', '-'), ('144', 'emoji1f41e', 'lady beetle', 'U+1F41E', '-', 'U+EB58', '-', 'U+FE1E2', '-'), ('145', 'emoji1f420', 'tropical fish', 'U+1F420', 'U+E751', 'U+EB1D', 'U+E522', 'U+FE1C9', '\\ue522'), ('146', 'emoji1f421', 'blowfish', 'U+1F421', 'U+E751', 'U+E4D3', 'U+E019', 'U+FE1D9', '\\ue019'), ('147', 'emoji1f422', 'turtle', 'U+1F422', '-', 'U+E5D4', '-', 'U+FE1DC', '-'), ('148', 'emoji1f424', 'baby chick', 'U+1F424', 'U+E74F', 'U+E4E0', 'U+E523', 'U+FE1BA', '\\ue523'), ('149', 'emoji1f425', 'front-facing baby chick', 'U+1F425', 'U+E74F', 'U+EB76', 'U+E523', 'U+FE1BB', '\\ue523'), ('150', 'emoji1f426', 'bird', 'U+1F426', 'U+E74F', 'U+E4E0', 'U+E521', 'U+FE1C8', '\\ue521'), ('151', 'emoji1f423', 'hatching chick', 'U+1F423', 'U+E74F', 'U+E5DB', 'U+E523', 'U+FE1DD', '\\ue523'), ('152', 'emoji1f427', 'penguin', 'U+1F427', 'U+E750', 'U+E4DC', 'U+E055', 'U+FE1BC', '\\ue055'), ('153', 'emoji1f429', 'poodle', 'U+1F429', 'U+E6A1', 'U+E4DF', 'U+E052', 'U+FE1D8', '\\ue052'), ('154', 'emoji1f41f', 'fish', 'U+1F41F', 'U+E751', 'U+E49A', 'U+E019', 'U+FE1BD', '\\ue019'), ('155', 'emoji1f42c', 'dolphin', 'U+1F42C', '-', 'U+EB1B', 'U+E520', 'U+FE1C7', '\\ue520'), ('156', 'emoji1f42d', 'mouse face', 'U+1F42D', '-', 'U+E5C2', 'U+E053', 'U+FE1C2', '\\ue053'), ('157', 'emoji1f42f', 'tiger face', 'U+1F42F', '-', 'U+E5C0', 'U+E050', 'U+FE1C0', '\\ue050'), ('158', 'emoji1f431', 'cat face', 'U+1F431', 'U+E6A2', 'U+E4DB', 'U+E04F', 'U+FE1B8', '\\ue04f'), ('159', 'emoji1f433', 'spouting whale', 'U+1F433', '-', 'U+E470', 'U+E054', 'U+FE1C3', '\\ue054'), ('160', 'emoji1f434', 'horse face', 'U+1F434', 'U+E754', 'U+E4D8', 'U+E01A', 'U+FE1BE', '\\ue01a'), ('161', 'emoji1f435', 'monkey face', 'U+1F435', '-', 'U+E4D9', 'U+E109', 'U+FE1C4', '\\ue109'), ('162', 'emoji1f436', 'dog face', 'U+1F436', 'U+E6A1', 'U+E4E1', 'U+E052', 'U+FE1B7', '\\ue052'), ('163', 'emoji1f437', 'pig face', 'U+1F437', 'U+E755', 'U+E4DE', 'U+E10B', 'U+FE1BF', '\\ue10b'), ('164', 'emoji1f43b', 'bear face', 'U+1F43B', '-', 'U+E5C1', 'U+E051', 'U+FE1C1', '\\ue051'), ('165', 'emoji1f439', 'hamster face', 'U+1F439', '-', '-', 'U+E524', 'U+FE1CA', '\\ue524'), ('166', 'emoji1f43a', 'wolf face', 'U+1F43A', 'U+E6A1', 'U+E4E1', 'U+E52A', 'U+FE1D0', '\\ue52a'), ('167', 'emoji1f42e', 'cow face', 'U+1F42E', '-', 'U+EB21', 'U+E52B', 'U+FE1D1', '\\ue52b'), ('168', 'emoji1f430', 'rabbit face', 'U+1F430', '-', 'U+E4D7', 'U+E52C', 'U+FE1D2', '\\ue52c'), ('169', 'emoji1f438', 'frog face', 'U+1F438', '-', 'U+E4DA', 'U+E531', 'U+FE1D7', '\\ue531'), ('170', 'emoji1f43e', 'paw prints', 'U+1F43E', 'U+E698', 'U+E4EE', 'U+E536', 'U+FE1DB', '\\ue536'), ('171', 'emoji1f432', 'dragon face', 'U+1F432', '-', 'U+EB3F', '-', 'U+FE1DE', '-'), ('172', 'emoji1f43c', 'panda face', 'U+1F43C', '-', 'U+EB46', '-', 'U+FE1DF', '-'), ('173', 'emoji1f43d', 'pig nose', 'U+1F43D', 'U+E755', 'U+EB48', 'U+E10B', 'U+FE1E0', '\\ue10b'), ('174', 'emoji1f620', 'angry face', 'U+1F620', 'U+E6F1', 'U+E472', 'U+E059', 'U+FE320', '\\ue059'), ('175', 'emoji1f629', 'weary face', 'U+1F629', 'U+E6F3', 'U+EB67', 'U+E403', 'U+FE321', '\\ue403'), ('176', 'emoji1f632', 'astonished face', 'U+1F632', 'U+E6F4', 'U+EACA', 'U+E410', 'U+FE322', '\\ue410'), ('177', 'emoji1f61e', 'disappointed face', 'U+1F61E', 'U+E6F2', 'U+EAC0', 'U+E058', 'U+FE323', '\\ue058'), ('178', 'emoji1f635', 'dizzy face', 'U+1F635', 'U+E6F4', 'U+E5AE', 'U+E406', 'U+FE324', '\\ue406'), ('179', 'emoji1f630', 'face with open mouth and cold sweat', 'U+1F630', 'U+E723', 'U+EACB', 'U+E40F', 'U+FE325', '\\ue40f'), ('180', 'emoji1f612', 'unamused face', 'U+1F612', 'U+E725', 'U+EAC9', 'U+E40E', 'U+FE326', '\\ue40e'), ('181', 'emoji1f60d', 'smiling face with heart-shaped eyes', 'U+1F60D', 'U+E726', 'U+E5C4', 'U+E106', 'U+FE327', '\\ue106'), ('182', 'emoji1f624', 'face with look of triumph', 'U+1F624', 'U+E753', 'U+EAC1', 'U+E404', 'U+FE328', '\\ue404'), ('183', 'emoji1f61c', 'face with stuck-out tongue and winking e', 'U+1F61C', 'U+E728', 'U+E4E7', 'U+E105', 'U+FE329', '\\ue105'), ('184', 'emoji1f61d', 'face with stuck-out tongue and tightly-c', 'U+1F61D', 'U+E728', 'U+E4E7', 'U+E409', 'U+FE32A', '\\ue409'), ('185', 'emoji1f60b', 'face savouring delicious food', 'U+1F60B', 'U+E752', 'U+EACD', 'U+E056', 'U+FE32B', '\\ue056'), ('186', 'emoji1f618', 'face throwing a kiss', 'U+1F618', 'U+E726', 'U+EACF', 'U+E418', 'U+FE32C', '\\ue418'), ('187', 'emoji1f61a', 'kissing face with closed eyes', 'U+1F61A', 'U+E726', 'U+EACE', 'U+E417', 'U+FE32D', '\\ue417'), ('188', 'emoji1f637', 'face with medical mask', 'U+1F637', '-', 'U+EAC7', 'U+E40C', 'U+FE32E', '\\ue40c'), ('189', 'emoji1f633', 'flushed face', 'U+1F633', 'U+E72A', 'U+EAC8', 'U+E40D', 'U+FE32F', '\\ue40d'), ('190', 'emoji1f603', 'smiling face with open mouth', 'U+1F603', 'U+E6F0', 'U+E471', 'U+E057', 'U+FE330', '\\ue057'), ('191', 'emoji1f605', 'smiling face with open mouth and cold sw', 'U+1F605', 'U+E722', 'U+E471 U+E5B1', 'U+E415 U+E331', 'U+FE331', '\\ue415 ue331'), ('192', 'emoji1f606', 'smiling face with open mouth and tightly', 'U+1F606', 'U+E72A', 'U+EAC5', 'U+E40A', 'U+FE332', '\\ue40a'), ('193', 'emoji1f601', 'grinning face with smiling eyes', 'U+1F601', 'U+E753', 'U+EB80', 'U+E404', 'U+FE333', '\\ue404'), ('194', 'emoji1f602', 'face with tears of joy', 'U+1F602', 'U+E72A', 'U+EB64', 'U+E412', 'U+FE334', '\\ue412'), ('195', 'emoji1f60a', 'smiling face with smiling eyes', 'U+1F60A', 'U+E6F0', 'U+EACD', 'U+E056', 'U+FE335', '\\ue056'), ('196', 'emoji263a', 'white smiling face', 'U+263A', 'U+E6F0', 'U+E4FB', 'U+E414', 'U+FE336', '\\ue414'), ('197', 'emoji1f604', 'smiling face with open mouth and smiling', 'U+1F604', 'U+E6F0', 'U+E471', 'U+E415', 'U+FE338', '\\ue415'), ('198', 'emoji1f622', 'crying face', 'U+1F622', 'U+E72E', 'U+EB69', 'U+E413', 'U+FE339', '\\ue413'), ('199', 'emoji1f62d', 'loudly crying face', 'U+1F62D', 'U+E72D', 'U+E473', 'U+E411', 'U+FE33A', '\\ue411'), ('200', 'emoji1f628', 'fearful face', 'U+1F628', 'U+E757', 'U+EAC6', 'U+E40B', 'U+FE33B', '\\ue40b'), ('201', 'emoji1f623', 'persevering face', 'U+1F623', 'U+E72B', 'U+EAC2', 'U+E406', 'U+FE33C', '\\ue406'), ('202', 'emoji1f621', 'pouting face', 'U+1F621', 'U+E724', 'U+EB5D', 'U+E416', 'U+FE33D', '\\ue416'), ('203', 'emoji1f60c', 'relieved face', 'U+1F60C', 'U+E721', 'U+EAC5', 'U+E40A', 'U+FE33E', '\\ue40a'), ('204', 'emoji1f616', 'confounded face', 'U+1F616', 'U+E6F3', 'U+EAC3', 'U+E407', 'U+FE33F', '\\ue407'), ('205', 'emoji1f614', 'pensive face', 'U+1F614', 'U+E720', 'U+EAC0', 'U+E403', 'U+FE340', '\\ue403'), ('206', 'emoji1f631', 'face screaming in fear', 'U+1F631', 'U+E757', 'U+E5C5', 'U+E107', 'U+FE341', '\\ue107'), ('207', 'emoji1f62a', 'sleepy face', 'U+1F62A', 'U+E701', 'U+EAC4', 'U+E408', 'U+FE342', '\\ue408'), ('208', 'emoji1f60f', 'smirking face', 'U+1F60F', 'U+E72C', 'U+EABF', 'U+E402', 'U+FE343', '\\ue402'), ('209', 'emoji1f613', 'face with cold sweat', 'U+1F613', 'U+E723', 'U+E5C6', 'U+E108', 'U+FE344', '\\ue108'), ('210', 'emoji1f625', 'disappointed but relieved face', 'U+1F625', 'U+E723', 'U+E5C6', 'U+E401', 'U+FE345', '\\ue401'), ('211', 'emoji1f62b', 'tired face', 'U+1F62B', 'U+E72B', 'U+E474', 'U+E406', 'U+FE346', '\\ue406'), ('212', 'emoji1f609', 'winking face', 'U+1F609', 'U+E729', 'U+E5C3', 'U+E405', 'U+FE347', '\\ue405'), ('213', 'emoji1f63a', 'smiling cat face with open mouth', 'U+1F63A', 'U+E6F0', 'U+EB61', 'U+E057', 'U+FE348', '\\ue057'), ('214', 'emoji1f638', 'grinning cat face with smiling eyes', 'U+1F638', 'U+E753', 'U+EB7F', 'U+E404', 'U+FE349', '\\ue404'), ('215', 'emoji1f639', 'cat face with tears of joy', 'U+1F639', 'U+E72A', 'U+EB63', 'U+E412', 'U+FE34A', '\\ue412'), ('216', 'emoji1f63d', 'kissing cat face with closed eyes', 'U+1F63D', 'U+E726', 'U+EB60', 'U+E418', 'U+FE34B', '\\ue418'), ('217', 'emoji1f63b', 'smiling cat face with heart-shaped eyes', 'U+1F63B', 'U+E726', 'U+EB65', 'U+E106', 'U+FE34C', '\\ue106'), ('218', 'emoji1f63f', 'crying cat face', 'U+1F63F', 'U+E72E', 'U+EB68', 'U+E413', 'U+FE34D', '\\ue413'), ('219', 'emoji1f63e', 'pouting cat face', 'U+1F63E', 'U+E724', 'U+EB5E', 'U+E416', 'U+FE34E', '\\ue416'), ('220', 'emoji1f63c', 'cat face with wry smile', 'U+1F63C', 'U+E753', 'U+EB6A', 'U+E404', 'U+FE34F', '\\ue404'), ('221', 'emoji1f640', 'weary cat face', 'U+1F640', 'U+E6F3', 'U+EB66', 'U+E403', 'U+FE350', '\\ue403'), ('222', 'emoji1f645', 'face with no good gesture', 'U+1F645', 'U+E72F', 'U+EAD7', 'U+E423', 'U+FE351', '\\ue423'), ('223', 'emoji1f646', 'face with ok gesture', 'U+1F646', 'U+E70B', 'U+EAD8', 'U+E424', 'U+FE352', '\\ue424'), ('224', 'emoji1f647', 'person bowing deeply', 'U+1F647', '-', 'U+EAD9', 'U+E426', 'U+FE353', '\\ue426'), ('225', 'emoji1f648', 'see-no-evil monkey', 'U+1F648', '-', 'U+EB50', '-', 'U+FE354', '-'), ('226', 'emoji1f64a', 'speak-no-evil monkey', 'U+1F64A', '-', 'U+EB51', '-', 'U+FE355', '-'), ('227', 'emoji1f649', 'hear-no-evil monkey', 'U+1F649', '-', 'U+EB52', '-', 'U+FE356', '-'), ('228', 'emoji1f64b', 'happy person raising one hand', 'U+1F64B', '-', 'U+EB85', 'U+E012', 'U+FE357', '\\ue012'), ('229', 'emoji1f64c', 'person raising both hands in celebration', 'U+1F64C', '-', 'U+EB86', 'U+E427', 'U+FE358', '\\ue427'), ('230', 'emoji1f64d', 'person frowning', 'U+1F64D', 'U+E6F3', 'U+EB87', 'U+E403', 'U+FE359', '\\ue403'), ('231', 'emoji1f64e', 'person with pouting face', 'U+1F64E', 'U+E6F1', 'U+EB88', 'U+E416', 'U+FE35A', '\\ue416'), ('232', 'emoji1f64f', 'person with folded hands', 'U+1F64F', '-', 'U+EAD2', 'U+E41D', 'U+FE35B', '\\ue41d'), ('233', 'emoji1f3e0', 'house building', 'U+1F3E0', 'U+E663', 'U+E4AB', 'U+E036', 'U+FE4B0', '\\ue036'), ('234', 'emoji1f3e1', 'house with garden', 'U+1F3E1', 'U+E663', 'U+EB09', 'U+E036', 'U+FE4B1', '\\ue036'), ('235', 'emoji1f3e2', 'office building', 'U+1F3E2', 'U+E664', 'U+E4AD', 'U+E038', 'U+FE4B2', '\\ue038'), ('236', 'emoji1f3e3', 'japanese post office', 'U+1F3E3', 'U+E665', 'U+E5DE', 'U+E153', 'U+FE4B3', '\\ue153'), ('237', 'emoji1f3e5', 'hospital', 'U+1F3E5', 'U+E666', 'U+E5DF', 'U+E155', 'U+FE4B4', '\\ue155'), ('238', 'emoji1f3e6', 'bank', 'U+1F3E6', 'U+E667', 'U+E4AA', 'U+E14D', 'U+FE4B5', '\\ue14d'), ('239', 'emoji1f3e7', 'automated teller machine', 'U+1F3E7', 'U+E668', 'U+E4A3', 'U+E154', 'U+FE4B6', '\\ue154'), ('240', 'emoji1f3e8', 'hotel', 'U+1F3E8', 'U+E669', 'U+EA81', 'U+E158', 'U+FE4B7', '\\ue158'), ('241', 'emoji1f3e9', 'love hotel', 'U+1F3E9', 'U+E669 U+E6EF', 'U+EAF3', 'U+E501', 'U+FE4B8', '\\ue501'), ('242', 'emoji1f3ea', 'convenience store', 'U+1F3EA', 'U+E66A', 'U+E4A4', 'U+E156', 'U+FE4B9', '\\ue156'), ('243', 'emoji1f3eb', 'school', 'U+1F3EB', 'U+E73E', 'U+EA80', 'U+E157', 'U+FE4BA', '\\ue157'), ('244', 'emoji26ea', 'church', 'U+26EA', '-', 'U+E5BB', 'U+E037', 'U+FE4BB', '\\ue037'), ('245', 'emoji26f2', 'fountain', 'U+26F2', '-', 'U+E5CF', 'U+E121', 'U+FE4BC', '\\ue121'), ('246', 'emoji1f3ec', 'department store', 'U+1F3EC', '-', 'U+EAF6', 'U+E504', 'U+FE4BD', '\\ue504'), ('247', 'emoji1f3ef', 'japanese castle', 'U+1F3EF', '-', 'U+EAF7', 'U+E505', 'U+FE4BE', '\\ue505'), ('248', 'emoji1f3f0', 'european castle', 'U+1F3F0', '-', 'U+EAF8', 'U+E506', 'U+FE4BF', '\\ue506'), ('249', 'emoji1f3ed', 'factory', 'U+1F3ED', '-', 'U+EAF9', 'U+E508', 'U+FE4C0', '\\ue508'), ('250', 'emoji2693', 'anchor', 'U+2693', 'U+E661', 'U+E4A9', 'U+E202', 'U+FE4C1', '\\ue202'), ('251', 'emoji1f3ee', 'izakaya lantern', 'U+1F3EE', 'U+E74B', 'U+E4BD', 'U+E30B', 'U+FE4C2', '\\ue30b'), ('252', 'emoji1f5fb', 'mount fuji', 'U+1F5FB', 'U+E740', 'U+E5BD', 'U+E03B', 'U+FE4C3', '\\ue03b'), ('253', 'emoji1f5fc', 'tokyo tower', 'U+1F5FC', '-', 'U+E4C0', 'U+E509', 'U+FE4C4', '\\ue509'), ('254', 'emoji1f5fd', 'statue of liberty', 'U+1F5FD', '-', '-', 'U+E51D', 'U+FE4C6', '\\ue51d'), ('255', 'emoji1f5fe', 'silhouette of japan', 'U+1F5FE', '-', 'U+E572', '-', 'U+FE4C7', '-'), ('256', 'emoji1f5ff', 'moyai', 'U+1F5FF', '-', 'U+EB6C', '-', 'U+FE4C8', '-'), ('257', 'emoji1f45e', 'mans shoe', 'U+1F45E', 'U+E699', 'U+E5B7', 'U+E007', 'U+FE4CC', '\\ue007'), ('258', 'emoji1f45f', 'athletic shoe', 'U+1F45F', 'U+E699', 'U+EB2B', 'U+E007', 'U+FE4CD', '\\ue007'), ('259', 'emoji1f460', 'high-heeled shoe', 'U+1F460', 'U+E674', 'U+E51A', 'U+E13E', 'U+FE4D6', '\\ue13e'), ('260', 'emoji1f461', 'womans sandal', 'U+1F461', 'U+E674', 'U+E51A', 'U+E31A', 'U+FE4D7', '\\ue31a'), ('261', 'emoji1f462', 'womans boots', 'U+1F462', '-', 'U+EA9F', 'U+E31B', 'U+FE4D8', '\\ue31b'), ('262', 'emoji1f463', 'footprints', 'U+1F463', 'U+E698', 'U+EB2A', 'U+E536', 'U+FE553', '\\ue536'), ('263', 'emoji1f453', 'eyeglasses', 'U+1F453', 'U+E69A', 'U+E4FE', '-', 'U+FE4CE', '-'), ('264', 'emoji1f455', 't-shirt', 'U+1F455', 'U+E70E', 'U+E5B6', 'U+E006', 'U+FE4CF', '\\ue006'), ('265', 'emoji1f456', 'jeans', 'U+1F456', 'U+E711', 'U+EB77', '-', 'U+FE4D0', '-'), ('266', 'emoji1f451', 'crown', 'U+1F451', 'U+E71A', 'U+E5C9', 'U+E10E', 'U+FE4D1', '\\ue10e'), ('267', 'emoji1f454', 'necktie', 'U+1F454', '-', 'U+EA93', 'U+E302', 'U+FE4D3', '\\ue302'), ('268', 'emoji1f452', 'womans hat', 'U+1F452', '-', 'U+EA9E', 'U+E318', 'U+FE4D4', '\\ue318'), ('269', 'emoji1f457', 'dress', 'U+1F457', '-', 'U+EB6B', 'U+E319', 'U+FE4D5', '\\ue319'), ('270', 'emoji1f458', 'kimono', 'U+1F458', '-', 'U+EAA3', 'U+E321', 'U+FE4D9', '\\ue321'), ('271', 'emoji1f459', 'bikini', 'U+1F459', '-', 'U+EAA4', 'U+E322', 'U+FE4DA', '\\ue322'), ('272', 'emoji1f45a', 'womans clothes', 'U+1F45A', 'U+E70E', 'U+E50D', 'U+E006', 'U+FE4DB', '\\ue006'), ('273', 'emoji1f45b', 'purse', 'U+1F45B', 'U+E70F', 'U+E504', '-', 'U+FE4DC', '-'), ('274', 'emoji1f45c', 'handbag', 'U+1F45C', 'U+E682', 'U+E49C', 'U+E323', 'U+FE4F0', '\\ue323'), ('275', 'emoji1f45d', 'pouch', 'U+1F45D', 'U+E6AD', '-', '-', 'U+FE4F1', '-'), ('276', 'emoji1f4b0', 'money bag', 'U+1F4B0', 'U+E715', 'U+E4C7', 'U+E12F', 'U+FE4DD', '\\ue12f'), ('277', 'emoji1f4b1', 'currency exchange', 'U+1F4B1', '-', '-', 'U+E149', 'U+FE4DE', '\\ue149'), ('278', 'emoji1f4b9', 'chart with upwards trend and yen sign', 'U+1F4B9', '-', 'U+E5DC', 'U+E14A', 'U+FE4DF', '\\ue14a'), ('279', 'emoji1f4b2', 'heavy dollar sign', 'U+1F4B2', 'U+E715', 'U+E579', 'U+E12F', 'U+FE4E0', '\\ue12f'), ('280', 'emoji1f4b3', 'credit card', 'U+1F4B3', '-', 'U+E57C', '-', 'U+FE4E1', '-'), ('281', 'emoji1f4b4', 'banknote with yen sign', 'U+1F4B4', 'U+E6D6', 'U+E57D', '-', 'U+FE4E2', '-'), ('282', 'emoji1f4b5', 'banknote with dollar sign', 'U+1F4B5', 'U+E715', 'U+E585', 'U+E12F', 'U+FE4E3', '\\ue12f'), ('283', 'emoji1f4b8', 'money with wings', 'U+1F4B8', '-', 'U+EB5B', '-', 'U+FE4E4', '-'), ('284', 'emoji1f1e81f1f3', 'regional indicator symbol letters cn', 'U+1F1E8 U+1F1F3', '-', 'U+EB11', 'U+E513', 'U+FE4ED', '\\ue513'), ('285', 'emoji1f1e91f1ea', 'regional indicator symbol letters de', 'U+1F1E9 U+1F1EA', '-', 'U+EB0E', 'U+E50E', 'U+FE4E8', '\\ue50e'), ('286', 'emoji1f1ea1f1f8', 'regional indicator symbol letters es', 'U+1F1EA U+1F1F8', '-', 'U+E5D5', 'U+E511', 'U+FE4EB', '\\ue511'), ('287', 'emoji1f1eb1f1f7', 'regional indicator symbol letters fr', 'U+1F1EB U+1F1F7', '-', 'U+EAFA', 'U+E50D', 'U+FE4E7', '\\ue50d'), ('288', 'emoji1f1ec1f1e7', 'regional indicator symbol letters gb', 'U+1F1EC U+1F1E7', '-', 'U+EB10', 'U+E510', 'U+FE4EA', '\\ue510'), ('289', 'emoji1f1ee1f1f9', 'regional indicator symbol letters it', 'U+1F1EE U+1F1F9', '-', 'U+EB0F', 'U+E50F', 'U+FE4E9', '\\ue50f'), ('290', 'emoji1f1ef1f1f5', 'regional indicator symbol letters jp', 'U+1F1EF U+1F1F5', '-', 'U+E4CC', 'U+E50B', 'U+FE4E5', '\\ue50b'), ('291', 'emoji1f1f01f1f7', 'regional indicator symbol letters kr', 'U+1F1F0 U+1F1F7', '-', 'U+EB12', 'U+E514', 'U+FE4EE', '\\ue514'), ('292', 'emoji1f1f71f1fa', 'regional indicator symbol letters ru', 'U+1F1F7 U+1F1FA', '-', 'U+E5D6', 'U+E512', 'U+FE4EC', '\\ue512'), ('293', 'emoji1f1fa1f1f8', 'regional indicator symbol letters us', 'U+1F1FA U+1F1F8', '-', 'U+E573', 'U+E50C', 'U+FE4E6', '\\ue50c'), ('294', 'emoji1f525', 'fire', 'U+1F525', '-', 'U+E47B', 'U+E11D', 'U+FE4F6', '\\ue11d'), ('295', 'emoji1f526', 'electric torch', 'U+1F526', 'U+E6FB', 'U+E583', '-', 'U+FE4FB', '-'), ('296', 'emoji1f527', 'wrench', 'U+1F527', 'U+E718', 'U+E587', '-', 'U+FE4C9', '-'), ('297', 'emoji1f528', 'hammer', 'U+1F528', '-', 'U+E5CB', 'U+E116', 'U+FE4CA', '\\ue116'), ('298', 'emoji1f529', 'nut and bolt', 'U+1F529', '-', 'U+E581', '-', 'U+FE4CB', '-'), ('299', 'emoji1f52a', 'hocho', 'U+1F52A', '-', 'U+E57F', '-', 'U+FE4FA', '-'), ('300', 'emoji1f52b', 'pistol', 'U+1F52B', '-', 'U+E50A', 'U+E113', 'U+FE4F5', '\\ue113'), ('301', 'emoji1f52e', 'crystal ball', 'U+1F52E', '-', 'U+EA8F', 'U+E23E', 'U+FE4F7', '\\ue23e'), ('302', 'emoji1f52f', 'six pointed star with middle dot', 'U+1F52F', '-', 'U+EA8F', 'U+E23E', 'U+FE4F8', '\\ue23e'), ('303', 'emoji1f530', 'japanese symbol for beginner', 'U+1F530', '-', 'U+E480', 'U+E209', 'U+FE044', '\\ue209'), ('304', 'emoji1f531', 'trident emblem', 'U+1F531', 'U+E71A', 'U+E5C9', 'U+E031', 'U+FE4D2', '\\ue031'), ('305', 'emoji1f489', 'syringe', 'U+1F489', '-', 'U+E510', 'U+E13B', 'U+FE509', '\\ue13b'), ('306', 'emoji1f48a', 'pill', 'U+1F48A', '-', 'U+EA9A', 'U+E30F', 'U+FE50A', '\\ue30f'), ('307', 'emoji1f170', 'negative squared latin capital letter a', 'U+1F170', '-', 'U+EB26', 'U+E532', 'U+FE50B', '\\ue532'), ('308', 'emoji1f171', 'negative squared latin capital letter b', 'U+1F171', '-', 'U+EB27', 'U+E533', 'U+FE50C', '\\ue533'), ('309', 'emoji1f18e', 'negative squared ab', 'U+1F18E', '-', 'U+EB29', 'U+E534', 'U+FE50D', '\\ue534'), ('310', 'emoji1f17e', 'negative squared latin capital letter o', 'U+1F17E', '-', 'U+EB28', 'U+E535', 'U+FE50E', '\\ue535'), ('311', 'emoji1f380', 'ribbon', 'U+1F380', 'U+E684', 'U+E59F', 'U+E314', 'U+FE50F', '\\ue314'), ('312', 'emoji1f381', 'wrapped present', 'U+1F381', 'U+E685', 'U+E4CF', 'U+E112', 'U+FE510', '\\ue112'), ('313', 'emoji1f382', 'birthday cake', 'U+1F382', 'U+E686', 'U+E5A0', 'U+E34B', 'U+FE511', '\\ue34b'), ('314', 'emoji1f384', 'christmas tree', 'U+1F384', 'U+E6A4', 'U+E4C9', 'U+E033', 'U+FE512', '\\ue033'), ('315', 'emoji1f385', 'father christmas', 'U+1F385', '-', 'U+EAF0', 'U+E448', 'U+FE513', '\\ue448'), ('316', 'emoji1f38c', 'crossed flags', 'U+1F38C', '-', 'U+E5D9', 'U+E143', 'U+FE514', '\\ue143'), ('317', 'emoji1f386', 'fireworks', 'U+1F386', '-', 'U+E5CC', 'U+E117', 'U+FE515', '\\ue117'), ('318', 'emoji1f388', 'balloon', 'U+1F388', '-', 'U+EA9B', 'U+E310', 'U+FE516', '\\ue310'), ('319', 'emoji1f389', 'party popper', 'U+1F389', '-', 'U+EA9C', 'U+E312', 'U+FE517', '\\ue312'), ('320', 'emoji1f38d', 'pine decoration', 'U+1F38D', '-', 'U+EAE3', 'U+E436', 'U+FE518', '\\ue436'), ('321', 'emoji1f38e', 'japanese dolls', 'U+1F38E', '-', 'U+EAE4', 'U+E438', 'U+FE519', '\\ue438'), ('322', 'emoji1f393', 'graduation cap', 'U+1F393', '-', 'U+EAE5', 'U+E439', 'U+FE51A', '\\ue439'), ('323', 'emoji1f392', 'school satchel', 'U+1F392', '-', 'U+EAE6', 'U+E43A', 'U+FE51B', '\\ue43a'), ('324', 'emoji1f38f', 'carp streamer', 'U+1F38F', '-', 'U+EAE7', 'U+E43B', 'U+FE51C', '\\ue43b'), ('325', 'emoji1f387', 'firework sparkler', 'U+1F387', '-', 'U+EAEB', 'U+E440', 'U+FE51D', '\\ue440'), ('326', 'emoji1f390', 'wind chime', 'U+1F390', '-', 'U+EAED', 'U+E442', 'U+FE51E', '\\ue442'), ('327', 'emoji1f383', 'jack-o-lantern', 'U+1F383', '-', 'U+EAEE', 'U+E445', 'U+FE51F', '\\ue445'), ('328', 'emoji1f38a', 'confetti ball', 'U+1F38A', '-', 'U+E46F', '-', 'U+FE520', '-'), ('329', 'emoji1f38b', 'tanabata tree', 'U+1F38B', '-', 'U+EB3D', '-', 'U+FE521', '-'), ('330', 'emoji1f391', 'moon viewing ceremony', 'U+1F391', '-', 'U+EAEF', 'U+E446', 'U+FE017', '\\ue446'), ('331', 'emoji1f4df', 'pager', 'U+1F4DF', 'U+E65A', 'U+E59B', '-', 'U+FE522', '-'), ('332', 'emoji260e', 'black telephone', 'U+260E', 'U+E687', 'U+E596', 'U+E009', 'U+FE523', '\\ue009'), ('333', 'emoji1f4de', 'telephone receiver', 'U+1F4DE', 'U+E687', 'U+E51E', 'U+E009', 'U+FE524', '\\ue009'), ('334', 'emoji1f4f1', 'mobile phone', 'U+1F4F1', 'U+E688', 'U+E588', 'U+E00A', 'U+FE525', '\\ue00a'), ('335', 'emoji1f4f2', 'mobile phone with rightwards arrow at le', 'U+1F4F2', 'U+E6CE', 'U+EB08', 'U+E104', 'U+FE526', '\\ue104'), ('336', 'emoji1f4dd', 'memo', 'U+1F4DD', 'U+E689', 'U+EA92', 'U+E301', 'U+FE527', '\\ue301'), ('337', 'emoji1f4e0', 'fax machine', 'U+1F4E0', 'U+E6D0', 'U+E520', 'U+E00B', 'U+FE528', '\\ue00b'), ('338', 'emoji2709', 'envelope', 'U+2709', 'U+E6D3', 'U+E521', 'U+E103', 'U+FE529', '\\ue103'), ('339', 'emoji1f4e8', 'incoming envelope', 'U+1F4E8', 'U+E6CF', 'U+E591', 'U+E103', 'U+FE52A', '\\ue103'), ('340', 'emoji1f4e9', 'envelope with downwards arrow above', 'U+1F4E9', 'U+E6CF', 'U+EB62', 'U+E103', 'U+FE52B', '\\ue103'), ('341', 'emoji1f4ea', 'closed mailbox with lowered flag', 'U+1F4EA', 'U+E665', 'U+E51B', 'U+E101', 'U+FE52C', '\\ue101'), ('342', 'emoji1f4eb', 'closed mailbox with raised flag', 'U+1F4EB', 'U+E665', 'U+EB0A', 'U+E101', 'U+FE52D', '\\ue101'), ('343', 'emoji1f4ee', 'postbox', 'U+1F4EE', 'U+E665', 'U+E51B', 'U+E102', 'U+FE52E', '\\ue102'), ('344', 'emoji1f4f0', 'newspaper', 'U+1F4F0', '-', 'U+E58B', '-', 'U+FE822', '-'), ('345', 'emoji1f4e2', 'public address loudspeaker', 'U+1F4E2', '-', 'U+E511', 'U+E142', 'U+FE52F', '\\ue142'), ('346', 'emoji1f4e3', 'cheering megaphone', 'U+1F4E3', '-', 'U+E511', 'U+E317', 'U+FE530', '\\ue317'), ('347', 'emoji1f4e1', 'satellite antenna', 'U+1F4E1', '-', 'U+E4A8', 'U+E14B', 'U+FE531', '\\ue14b'), ('348', 'emoji1f4e4', 'outbox tray', 'U+1F4E4', '-', 'U+E592', '-', 'U+FE533', '-'), ('349', 'emoji1f4e5', 'inbox tray', 'U+1F4E5', '-', 'U+E593', '-', 'U+FE534', '-'), ('350', 'emoji1f4e6', 'package', 'U+1F4E6', 'U+E685', 'U+E51F', 'U+E112', 'U+FE535', '\\ue112'), ('351', 'emoji1f4e7', 'e-mail symbol', 'U+1F4E7', 'U+E6D3', 'U+EB71', 'U+E103', 'U+FEB92', '\\ue103'), ('352', 'emoji1f520', 'input symbol for latin capital letters', 'U+1F520', '-', 'U+EAFD', '-', 'U+FEB7C', '-'), ('353', 'emoji1f521', 'input symbol for latin small letters', 'U+1F521', '-', 'U+EAFE', '-', 'U+FEB7D', '-'), ('354', 'emoji1f522', 'input symbol for numbers', 'U+1F522', '-', 'U+EAFF', '-', 'U+FEB7E', '-'), ('355', 'emoji1f523', 'input symbol for symbols', 'U+1F523', '-', 'U+EB00', '-', 'U+FEB7F', '-'), ('356', 'emoji1f524', 'input symbol for latin letters', 'U+1F524', '-', 'U+EB55', '-', 'U+FEB80', '-'), ('357', 'emoji2712', 'black nib', 'U+2712', 'U+E6AE', 'U+EB03', '-', 'U+FE536', '-'), ('358', 'emoji1f4ba', 'seat', 'U+1F4BA', 'U+E6B2', '-', 'U+E11F', 'U+FE537', '\\ue11f'), ('359', 'emoji1f4bb', 'personal computer', 'U+1F4BB', 'U+E716', 'U+E5B8', 'U+E00C', 'U+FE538', '\\ue00c'), ('360', 'emoji270f', 'pencil', 'U+270F', 'U+E719', 'U+E4A1', 'U+E301', 'U+FE539', '\\ue301'), ('361', 'emoji1f4ce', 'paperclip', 'U+1F4CE', 'U+E730', 'U+E4A0', '-', 'U+FE53A', '-'), ('362', 'emoji1f4bc', 'briefcase', 'U+1F4BC', 'U+E682', 'U+E5CE', 'U+E11E', 'U+FE53B', '\\ue11e'), ('363', 'emoji1f4bd', 'minidisc', 'U+1F4BD', '-', 'U+E582', 'U+E316', 'U+FE53C', '\\ue316'), ('364', 'emoji1f4be', 'floppy disk', 'U+1F4BE', '-', 'U+E562', 'U+E316', 'U+FE53D', '\\ue316'), ('365', 'emoji1f4bf', 'optical disc', 'U+1F4BF', 'U+E68C', 'U+E50C', 'U+E126', 'U+FE81D', '\\ue126'), ('366', 'emoji1f4c0', 'dvd', 'U+1F4C0', 'U+E68C', 'U+E50C', 'U+E127', 'U+FE81E', '\\ue127'), ('367', 'emoji2702', 'black scissors', 'U+2702', 'U+E675', 'U+E516', 'U+E313', 'U+FE53E', '\\ue313'), ('368', 'emoji1f4cd', 'round pushpin', 'U+1F4CD', '-', 'U+E560', '-', 'U+FE53F', '-'), ('369', 'emoji1f4c3', 'page with curl', 'U+1F4C3', 'U+E689', 'U+E561', 'U+E301', 'U+FE540', '\\ue301'), ('370', 'emoji1f4c4', 'page facing up', 'U+1F4C4', 'U+E689', 'U+E569', 'U+E301', 'U+FE541', '\\ue301'), ('371', 'emoji1f4c5', 'calendar', 'U+1F4C5', '-', 'U+E563', '-', 'U+FE542', '-'), ('372', 'emoji1f4c1', 'file folder', 'U+1F4C1', '-', 'U+E58F', '-', 'U+FE543', '-'), ('373', 'emoji1f4c2', 'open file folder', 'U+1F4C2', '-', 'U+E590', '-', 'U+FE544', '-'), ('374', 'emoji1f4d3', 'notebook', 'U+1F4D3', 'U+E683', 'U+E56B', 'U+E148', 'U+FE545', '\\ue148'), ('375', 'emoji1f4d6', 'open book', 'U+1F4D6', 'U+E683', 'U+E49F', 'U+E148', 'U+FE546', '\\ue148'), ('376', 'emoji1f4d4', 'notebook with decorative cover', 'U+1F4D4', 'U+E683', 'U+E49D', 'U+E148', 'U+FE547', '\\ue148'), ('377', 'emoji1f4d5', 'closed book', 'U+1F4D5', 'U+E683', 'U+E568', 'U+E148', 'U+FE502', '\\ue148'), ('378', 'emoji1f4d7', 'green book', 'U+1F4D7', 'U+E683', 'U+E565', 'U+E148', 'U+FE4FF', '\\ue148'), ('379', 'emoji1f4d8', 'blue book', 'U+1F4D8', 'U+E683', 'U+E566', 'U+E148', 'U+FE500', '\\ue148'), ('380', 'emoji1f4d9', 'orange book', 'U+1F4D9', 'U+E683', 'U+E567', 'U+E148', 'U+FE501', '\\ue148'), ('381', 'emoji1f4da', 'books', 'U+1F4DA', 'U+E683', 'U+E56F', 'U+E148', 'U+FE503', '\\ue148'), ('382', 'emoji1f4db', 'name badge', 'U+1F4DB', '-', 'U+E51D', '-', 'U+FE504', '-'), ('383', 'emoji1f4dc', 'scroll', 'U+1F4DC', 'U+E70A', 'U+E55F', '-', 'U+FE4FD', '-'), ('384', 'emoji1f4cb', 'clipboard', 'U+1F4CB', 'U+E689', 'U+E564', 'U+E301', 'U+FE548', '\\ue301'), ('385', 'emoji1f4c6', 'tear-off calendar', 'U+1F4C6', '-', 'U+E56A', '-', 'U+FE549', '-'), ('386', 'emoji1f4ca', 'bar chart', 'U+1F4CA', '-', 'U+E574', 'U+E14A', 'U+FE54A', '\\ue14a'), ('387', 'emoji1f4c8', 'chart with upwards trend', 'U+1F4C8', '-', 'U+E575', 'U+E14A', 'U+FE54B', '\\ue14a'), ('388', 'emoji1f4c9', 'chart with downwards trend', 'U+1F4C9', '-', 'U+E576', '-', 'U+FE54C', '-'), ('389', 'emoji1f4c7', 'card index', 'U+1F4C7', 'U+E683', 'U+E56C', 'U+E148', 'U+FE54D', '\\ue148'), ('390', 'emoji1f4cc', 'pushpin', 'U+1F4CC', '-', 'U+E56D', '-', 'U+FE54E', '-'), ('391', 'emoji1f4d2', 'ledger', 'U+1F4D2', 'U+E683', 'U+E56E', 'U+E148', 'U+FE54F', '\\ue148'), ('392', 'emoji1f4cf', 'straight ruler', 'U+1F4CF', '-', 'U+E570', '-', 'U+FE550', '-'), ('393', 'emoji1f4d0', 'triangular ruler', 'U+1F4D0', '-', 'U+E4A2', '-', 'U+FE551', '-'), ('394', 'emoji1f4d1', 'bookmark tabs', 'U+1F4D1', 'U+E689', 'U+EB0B', 'U+E301', 'U+FE552', '\\ue301'), ('395', 'emoji1f3bd', 'running shirt with sash', 'U+1F3BD', 'U+E652', '-', '-', 'U+FE7D0', '-'), ('396', 'emoji26be', 'baseball', 'U+26BE', 'U+E653', 'U+E4BA', 'U+E016', 'U+FE7D1', '\\ue016'), ('397', 'emoji26f3', 'flag in hole', 'U+26F3', 'U+E654', 'U+E599', 'U+E014', 'U+FE7D2', '\\ue014'), ('398', 'emoji1f3be', 'tennis racquet and ball', 'U+1F3BE', 'U+E655', 'U+E4B7', 'U+E015', 'U+FE7D3', '\\ue015'), ('399', 'emoji26bd', 'soccer ball', 'U+26BD', 'U+E656', 'U+E4B6', 'U+E018', 'U+FE7D4', '\\ue018'), ('400', 'emoji1f3bf', 'ski and ski boot', 'U+1F3BF', 'U+E657', 'U+EAAC', 'U+E013', 'U+FE7D5', '\\ue013'), ('401', 'emoji1f3c0', 'basketball and hoop', 'U+1F3C0', 'U+E658', 'U+E59A', 'U+E42A', 'U+FE7D6', '\\ue42a'), ('402', 'emoji1f3c1', 'chequered flag', 'U+1F3C1', 'U+E659', 'U+E4B9', 'U+E132', 'U+FE7D7', '\\ue132'), ('403', 'emoji1f3c2', 'snowboarder', 'U+1F3C2', 'U+E712', 'U+E4B8', '-', 'U+FE7D8', '-'), ('404', 'emoji1f3c3', 'runner', 'U+1F3C3', 'U+E733', 'U+E46B', 'U+E115', 'U+FE7D9', '\\ue115'), ('405', 'emoji1f3c4', 'surfer', 'U+1F3C4', 'U+E712', 'U+EB41', 'U+E017', 'U+FE7DA', '\\ue017'), ('406', 'emoji1f3c6', 'trophy', 'U+1F3C6', '-', 'U+E5D3', 'U+E131', 'U+FE7DB', '\\ue131'), ('407', 'emoji1f3c8', 'american football', 'U+1F3C8', '-', 'U+E4BB', 'U+E42B', 'U+FE7DD', '\\ue42b'), ('408', 'emoji1f3ca', 'swimmer', 'U+1F3CA', '-', 'U+EADE', 'U+E42D', 'U+FE7DE', '\\ue42d'), ('409', 'emoji1f683', 'railway car', 'U+1F683', 'U+E65B', 'U+E4B5', 'U+E01E', 'U+FE7DF', '\\ue01e'), ('410', 'emoji1f687', 'metro', 'U+1F687', 'U+E65C', 'U+E5BC', 'U+E434', 'U+FE7E0', '\\ue434'), ('411', 'emoji24c2', 'circled latin capital letter m', 'U+24C2', 'U+E65C', 'U+E5BC', 'U+E434', 'U+FE7E1', '\\ue434'), ('412', 'emoji1f684', 'high-speed train', 'U+1F684', 'U+E65D', 'U+E4B0', 'U+E435', 'U+FE7E2', '\\ue435'), ('413', 'emoji1f685', 'high-speed train with bullet nose', 'U+1F685', 'U+E65D', 'U+E4B0', 'U+E01F', 'U+FE7E3', '\\ue01f'), ('414', 'emoji1f697', 'automobile', 'U+1F697', 'U+E65E', 'U+E4B1', 'U+E01B', 'U+FE7E4', '\\ue01b'), ('415', 'emoji1f699', 'recreational vehicle', 'U+1F699', 'U+E65F', 'U+E4B1', 'U+E42E', 'U+FE7E5', '\\ue42e'), ('416', 'emoji1f68c', 'bus', 'U+1F68C', 'U+E660', 'U+E4AF', 'U+E159', 'U+FE7E6', '\\ue159'), ('417', 'emoji1f68f', 'bus stop', 'U+1F68F', '-', 'U+E4A7', 'U+E150', 'U+FE7E7', '\\ue150'), ('418', 'emoji1f6a2', 'ship', 'U+1F6A2', 'U+E661', 'U+EA82', 'U+E202', 'U+FE7E8', '\\ue202'), ('419', 'emoji2708', 'airplane', 'U+2708', 'U+E662', 'U+E4B3', 'U+E01D', 'U+FE7E9', '\\ue01d'), ('420', 'emoji26f5', 'sailboat', 'U+26F5', 'U+E6A3', 'U+E4B4', 'U+E01C', 'U+FE7EA', '\\ue01c'), ('421', 'emoji1f689', 'station', 'U+1F689', '-', 'U+EB6D', 'U+E039', 'U+FE7EC', '\\ue039'), ('422', 'emoji1f680', 'rocket', 'U+1F680', '-', 'U+E5C8', 'U+E10D', 'U+FE7ED', '\\ue10d'), ('423', 'emoji1f6a4', 'speedboat', 'U+1F6A4', 'U+E6A3', 'U+E4B4', 'U+E135', 'U+FE7EE', '\\ue135'), ('424', 'emoji1f695', 'taxi', 'U+1F695', 'U+E65E', 'U+E4B1', 'U+E15A', 'U+FE7EF', '\\ue15a'), ('425', 'emoji1f69a', 'delivery truck', 'U+1F69A', '-', 'U+E4B2', 'U+E42F', 'U+FE7F1', '\\ue42f'), ('426', 'emoji1f692', 'fire engine', 'U+1F692', '-', 'U+EADF', 'U+E430', 'U+FE7F2', '\\ue430'), ('427', 'emoji1f691', 'ambulance', 'U+1F691', '-', 'U+EAE0', 'U+E431', 'U+FE7F3', '\\ue431'), ('428', 'emoji1f693', 'police car', 'U+1F693', '-', 'U+EAE1', 'U+E432', 'U+FE7F4', '\\ue432'), ('429', 'emoji26fd', 'fuel pump', 'U+26FD', 'U+E66B', 'U+E571', 'U+E03A', 'U+FE7F5', '\\ue03a'), ('430', 'emoji1f17f', 'negative squared latin capital letter p', 'U+1F17F', 'U+E66C', 'U+E4A6', 'U+E14F', 'U+FE7F6', '\\ue14f'), ('431', 'emoji1f6a5', 'horizontal traffic light', 'U+1F6A5', 'U+E66D', 'U+E46A', 'U+E14E', 'U+FE7F7', '\\ue14e'), ('432', 'emoji1f6a7', 'construction sign', 'U+1F6A7', '-', 'U+E5D7', 'U+E137', 'U+FE7F8', '\\ue137'), ('433', 'emoji1f6a8', 'police cars revolving light', 'U+1F6A8', '-', 'U+EB73', 'U+E432', 'U+FE7F9', '\\ue432'), ('434', 'emoji2668', 'hot springs', 'U+2668', 'U+E6F7', 'U+E4BC', 'U+E123', 'U+FE7FA', '\\ue123'), ('435', 'emoji26fa', 'tent', 'U+26FA', '-', 'U+E5D0', 'U+E122', 'U+FE7FB', '\\ue122'), ('436', 'emoji1f3a0', 'carousel horse', 'U+1F3A0', 'U+E679', '-', '-', 'U+FE7FC', '-'), ('437', 'emoji1f3a1', 'ferris wheel', 'U+1F3A1', '-', 'U+E46D', 'U+E124', 'U+FE7FD', '\\ue124'), ('438', 'emoji1f3a2', 'roller coaster', 'U+1F3A2', '-', 'U+EAE2', 'U+E433', 'U+FE7FE', '\\ue433'), ('439', 'emoji1f3a3', 'fishing pole and fish', 'U+1F3A3', 'U+E751', 'U+EB42', 'U+E019', 'U+FE7FF', '\\ue019'), ('440', 'emoji1f3a4', 'microphone', 'U+1F3A4', 'U+E676', 'U+E503', 'U+E03C', 'U+FE800', '\\ue03c'), ('441', 'emoji1f3a5', 'movie camera', 'U+1F3A5', 'U+E677', 'U+E517', 'U+E03D', 'U+FE801', '\\ue03d'), ('442', 'emoji1f3a6', 'cinema', 'U+1F3A6', 'U+E677', 'U+E517', 'U+E507', 'U+FE802', '\\ue507'), ('443', 'emoji1f3a7', 'headphone', 'U+1F3A7', 'U+E67A', 'U+E508', 'U+E30A', 'U+FE803', '\\ue30a'), ('444', 'emoji1f3a8', 'artist palette', 'U+1F3A8', 'U+E67B', 'U+E59C', 'U+E502', 'U+FE804', '\\ue502'), ('445', 'emoji1f3a9', 'top hat', 'U+1F3A9', 'U+E67C', 'U+EAF5', 'U+E503', 'U+FE805', '\\ue503'), ('446', 'emoji1f3aa', 'circus tent', 'U+1F3AA', 'U+E67D', 'U+E59E', '-', 'U+FE806', '-'), ('447', 'emoji1f3ab', 'ticket', 'U+1F3AB', 'U+E67E', 'U+E49E', 'U+E125', 'U+FE807', '\\ue125'), ('448', 'emoji1f3ac', 'clapper board', 'U+1F3AC', 'U+E6AC', 'U+E4BE', 'U+E324', 'U+FE808', '\\ue324'), ('449', 'emoji1f3ad', 'performing arts', 'U+1F3AD', '-', 'U+E59D', 'U+E503', 'U+FE809', '\\ue503'), ('450', 'emoji1f3ae', 'video game', 'U+1F3AE', 'U+E68B', 'U+E4C6', '-', 'U+FE80A', '-'), ('451', 'emoji1f004', 'mahjong tile red dragon', 'U+1F004', '-', 'U+E5D1', 'U+E12D', 'U+FE80B', '\\ue12d'), ('452', 'emoji1f3af', 'direct hit', 'U+1F3AF', '-', 'U+E4C5', 'U+E130', 'U+FE80C', '\\ue130'), ('453', 'emoji1f3b0', 'slot machine', 'U+1F3B0', '-', 'U+E46E', 'U+E133', 'U+FE80D', '\\ue133'), ('454', 'emoji1f3b1', 'billiards', 'U+1F3B1', '-', 'U+EADD', 'U+E42C', 'U+FE80E', '\\ue42c'), ('455', 'emoji1f3b2', 'game die', 'U+1F3B2', '-', 'U+E4C8', '-', 'U+FE80F', '-'), ('456', 'emoji1f3b3', 'bowling', 'U+1F3B3', '-', 'U+EB43', '-', 'U+FE810', '-'), ('457', 'emoji1f3b4', 'flower playing cards', 'U+1F3B4', '-', 'U+EB6E', '-', 'U+FE811', '-'), ('458', 'emoji1f0cf', 'playing card black joker', 'U+1F0CF', '-', 'U+EB6F', '-', 'U+FE812', '-'), ('459', 'emoji1f3b5', 'musical note', 'U+1F3B5', 'U+E6F6', 'U+E5BE', 'U+E03E', 'U+FE813', '\\ue03e'), ('460', 'emoji1f3b6', 'multiple musical notes', 'U+1F3B6', 'U+E6FF', 'U+E505', 'U+E326', 'U+FE814', '\\ue326'), ('461', 'emoji1f3b7', 'saxophone', 'U+1F3B7', '-', '-', 'U+E040', 'U+FE815', '\\ue040'), ('462', 'emoji1f3b8', 'guitar', 'U+1F3B8', '-', 'U+E506', 'U+E041', 'U+FE816', '\\ue041'), ('463', 'emoji1f3b9', 'musical keyboard', 'U+1F3B9', '-', 'U+EB40', '-', 'U+FE817', '-'), ('464', 'emoji1f3ba', 'trumpet', 'U+1F3BA', '-', 'U+EADC', 'U+E042', 'U+FE818', '\\ue042'), ('465', 'emoji1f3bb', 'violin', 'U+1F3BB', '-', 'U+E507', '-', 'U+FE819', '-'), ('466', 'emoji1f3bc', 'musical score', 'U+1F3BC', 'U+E6FF', 'U+EACC', 'U+E326', 'U+FE81A', '\\ue326'), ('467', 'emoji303d', 'part alternation mark', 'U+303D', '-', '-', 'U+E12C', 'U+FE81B', '\\ue12c'), ('468', 'emoji1f4f7', 'camera', 'U+1F4F7', 'U+E681', 'U+E515', 'U+E008', 'U+FE4EF', '\\ue008'), ('469', 'emoji1f4f9', 'video camera', 'U+1F4F9', 'U+E677', 'U+E57E', 'U+E03D', 'U+FE4F9', '\\ue03d'), ('470', 'emoji1f4fa', 'television', 'U+1F4FA', 'U+E68A', 'U+E502', 'U+E12A', 'U+FE81C', '\\ue12a'), ('471', 'emoji1f4fb', 'radio', 'U+1F4FB', '-', 'U+E5B9', 'U+E128', 'U+FE81F', '\\ue128'), ('472', 'emoji1f4fc', 'videocassette', 'U+1F4FC', '-', 'U+E580', 'U+E129', 'U+FE820', '\\ue129'), ('473', 'emoji1f48b', 'kiss mark', 'U+1F48B', 'U+E6F9', 'U+E4EB', 'U+E003', 'U+FE823', '\\ue003'), ('474', 'emoji1f48c', 'love letter', 'U+1F48C', 'U+E717', 'U+EB78', 'U+E103 U+E328', 'U+FE824', '\\ue103 ue328'), ('475', 'emoji1f48d', 'ring', 'U+1F48D', 'U+E71B', 'U+E514', 'U+E034', 'U+FE825', '\\ue034'), ('476', 'emoji1f48e', 'gem stone', 'U+1F48E', 'U+E71B', 'U+E514', 'U+E035', 'U+FE826', '\\ue035'), ('477', 'emoji1f48f', 'kiss', 'U+1F48F', 'U+E6F9', 'U+E5CA', 'U+E111', 'U+FE827', '\\ue111'), ('478', 'emoji1f490', 'bouquet', 'U+1F490', '-', 'U+EA95', 'U+E306', 'U+FE828', '\\ue306'), ('479', 'emoji1f491', 'couple with heart', 'U+1F491', 'U+E6ED', 'U+EADA', 'U+E425', 'U+FE829', '\\ue425'), ('480', 'emoji1f492', 'wedding', 'U+1F492', '-', 'U+E5BB', 'U+E43D', 'U+FE82A', '\\ue43d'), ('481', 'emoji1f51e', 'no one under eighteen symbol', 'U+1F51E', '-', 'U+EA83', 'U+E207', 'U+FEB25', '\\ue207'), ('482', 'emojia9', 'copyright sign', 'U+00A9', 'U+E731', 'U+E558', 'U+E24E', 'U+FEB29', '\\ue24e'), ('483', 'emojiae', 'registered sign', 'U+00AE', 'U+E736', 'U+E559', 'U+E24F', 'U+FEB2D', '\\ue24f'), ('484', 'emoji2122', 'trade mark sign', 'U+2122', 'U+E732', 'U+E54E', 'U+E537', 'U+FEB2A', '\\ue537'), ('485', 'emoji2139', 'information source', 'U+2139', '-', 'U+E533', '-', 'U+FEB47', '-'), ('486', 'emoji2320e3', 'hash key', 'U+0023 U+20E3', 'U+E6E0', 'U+EB84', 'U+E210', 'U+FE82C', '\\ue210'), ('487', 'emoji3120e3', 'keycap 1', 'U+0031 U+20E3', 'U+E6E2', 'U+E522', 'U+E21C', 'U+FE82E', '\\ue21c'), ('488', 'emoji3220e3', 'keycap 2', 'U+0032 U+20E3', 'U+E6E3', 'U+E523', 'U+E21D', 'U+FE82F', '\\ue21d'), ('489', 'emoji3320e3', 'keycap 3', 'U+0033 U+20E3', 'U+E6E4', 'U+E524', 'U+E21E', 'U+FE830', '\\ue21e'), ('490', 'emoji3420e3', 'keycap 4', 'U+0034 U+20E3', 'U+E6E5', 'U+E525', 'U+E21F', 'U+FE831', '\\ue21f'), ('491', 'emoji3520e3', 'keycap 5', 'U+0035 U+20E3', 'U+E6E6', 'U+E526', 'U+E220', 'U+FE832', '\\ue220'), ('492', 'emoji3620e3', 'keycap 6', 'U+0036 U+20E3', 'U+E6E7', 'U+E527', 'U+E221', 'U+FE833', '\\ue221'), ('493', 'emoji3720e3', 'keycap 7', 'U+0037 U+20E3', 'U+E6E8', 'U+E528', 'U+E222', 'U+FE834', '\\ue222'), ('494', 'emoji3820e3', 'keycap 8', 'U+0038 U+20E3', 'U+E6E9', 'U+E529', 'U+E223', 'U+FE835', '\\ue223'), ('495', 'emoji3920e3', 'keycap 9', 'U+0039 U+20E3', 'U+E6EA', 'U+E52A', 'U+E224', 'U+FE836', '\\ue224'), ('496', 'emoji3020e3', 'keycap 0', 'U+0030 U+20E3', 'U+E6EB', 'U+E5AC', 'U+E225', 'U+FE837', '\\ue225'), ('497', 'emoji1f51f', 'keycap ten', 'U+1F51F', '-', 'U+E52B', '-', 'U+FE83B', '-'), ('498', 'emoji1f4f6', 'antenna with bars', 'U+1F4F6', '-', 'U+EA84', 'U+E20B', 'U+FE838', '\\ue20b'), ('499', 'emoji1f4f3', 'vibration mode', 'U+1F4F3', '-', 'U+EA90', 'U+E250', 'U+FE839', '\\ue250'), ('500', 'emoji1f4f4', 'mobile phone off', 'U+1F4F4', '-', 'U+EA91', 'U+E251', 'U+FE83A', '\\ue251'), ('501', 'emoji1f354', 'hamburger', 'U+1F354', 'U+E673', 'U+E4D6', 'U+E120', 'U+FE960', '\\ue120'), ('502', 'emoji1f359', 'rice ball', 'U+1F359', 'U+E749', 'U+E4D5', 'U+E342', 'U+FE961', '\\ue342'), ('503', 'emoji1f370', 'shortcake', 'U+1F370', 'U+E74A', 'U+E4D0', 'U+E046', 'U+FE962', '\\ue046'), ('504', 'emoji1f35c', 'steaming bowl', 'U+1F35C', 'U+E74C', 'U+E5B4', 'U+E340', 'U+FE963', '\\ue340'), ('505', 'emoji1f35e', 'bread', 'U+1F35E', 'U+E74D', 'U+EAAF', 'U+E339', 'U+FE964', '\\ue339'), ('506', 'emoji1f373', 'cooking', 'U+1F373', '-', 'U+E4D1', 'U+E147', 'U+FE965', '\\ue147'), ('507', 'emoji1f366', 'soft ice cream', 'U+1F366', '-', 'U+EAB0', 'U+E33A', 'U+FE966', '\\ue33a'), ('508', 'emoji1f35f', 'french fries', 'U+1F35F', '-', 'U+EAB1', 'U+E33B', 'U+FE967', '\\ue33b'), ('509', 'emoji1f361', 'dango', 'U+1F361', '-', 'U+EAB2', 'U+E33C', 'U+FE968', '\\ue33c'), ('510', 'emoji1f358', 'rice cracker', 'U+1F358', '-', 'U+EAB3', 'U+E33D', 'U+FE969', '\\ue33d'), ('511', 'emoji1f35a', 'cooked rice', 'U+1F35A', 'U+E74C', 'U+EAB4', 'U+E33E', 'U+FE96A', '\\ue33e'), ('512', 'emoji1f35d', 'spaghetti', 'U+1F35D', '-', 'U+EAB5', 'U+E33F', 'U+FE96B', '\\ue33f'), ('513', 'emoji1f35b', 'curry and rice', 'U+1F35B', '-', 'U+EAB6', 'U+E341', 'U+FE96C', '\\ue341'), ('514', 'emoji1f362', 'oden', 'U+1F362', '-', 'U+EAB7', 'U+E343', 'U+FE96D', '\\ue343'), ('515', 'emoji1f363', 'sushi', 'U+1F363', '-', 'U+EAB8', 'U+E344', 'U+FE96E', '\\ue344'), ('516', 'emoji1f371', 'bento box', 'U+1F371', '-', 'U+EABD', 'U+E34C', 'U+FE96F', '\\ue34c'), ('517', 'emoji1f372', 'pot of food', 'U+1F372', '-', 'U+EABE', 'U+E34D', 'U+FE970', '\\ue34d'), ('518', 'emoji1f367', 'shaved ice', 'U+1F367', '-', 'U+EAEA', 'U+E43F', 'U+FE971', '\\ue43f'), ('519', 'emoji1f356', 'meat on bone', 'U+1F356', '-', 'U+E4C4', '-', 'U+FE972', '-'), ('520', 'emoji1f365', 'fish cake with swirl design', 'U+1F365', 'U+E643', 'U+E4ED', '-', 'U+FE973', '-'), ('521', 'emoji1f360', 'roasted sweet potato', 'U+1F360', '-', 'U+EB3A', '-', 'U+FE974', '-'), ('522', 'emoji1f355', 'slice of pizza', 'U+1F355', '-', 'U+EB3B', '-', 'U+FE975', '-'), ('523', 'emoji1f357', 'poultry leg', 'U+1F357', '-', 'U+EB3C', '-', 'U+FE976', '-'), ('524', 'emoji1f368', 'ice cream', 'U+1F368', '-', 'U+EB4A', '-', 'U+FE977', '-'), ('525', 'emoji1f369', 'doughnut', 'U+1F369', '-', 'U+EB4B', '-', 'U+FE978', '-'), ('526', 'emoji1f36a', 'cookie', 'U+1F36A', '-', 'U+EB4C', '-', 'U+FE979', '-'), ('527', 'emoji1f36b', 'chocolate bar', 'U+1F36B', '-', 'U+EB4D', '-', 'U+FE97A', '-'), ('528', 'emoji1f36c', 'candy', 'U+1F36C', '-', 'U+EB4E', '-', 'U+FE97B', '-'), ('529', 'emoji1f36d', 'lollipop', 'U+1F36D', '-', 'U+EB4F', '-', 'U+FE97C', '-'), ('530', 'emoji1f36e', 'custard', 'U+1F36E', '-', 'U+EB56', '-', 'U+FE97D', '-'), ('531', 'emoji1f36f', 'honey pot', 'U+1F36F', '-', 'U+EB59', '-', 'U+FE97E', '-'), ('532', 'emoji1f364', 'fried shrimp', 'U+1F364', '-', 'U+EB70', '-', 'U+FE97F', '-'), ('533', 'emoji1f374', 'fork and knife', 'U+1F374', 'U+E66F', 'U+E4AC', 'U+E043', 'U+FE980', '\\ue043'), ('534', 'emoji2615', 'hot beverage', 'U+2615', 'U+E670', 'U+E597', 'U+E045', 'U+FE981', '\\ue045'), ('535', 'emoji1f378', 'cocktail glass', 'U+1F378', 'U+E671', 'U+E4C2', 'U+E044', 'U+FE982', '\\ue044'), ('536', 'emoji1f37a', 'beer mug', 'U+1F37A', 'U+E672', 'U+E4C3', 'U+E047', 'U+FE983', '\\ue047'), ('537', 'emoji1f375', 'teacup without handle', 'U+1F375', 'U+E71E', 'U+EAAE', 'U+E338', 'U+FE984', '\\ue338'), ('538', 'emoji1f376', 'sake bottle and cup', 'U+1F376', 'U+E74B', 'U+EA97', 'U+E30B', 'U+FE985', '\\ue30b'), ('539', 'emoji1f377', 'wine glass', 'U+1F377', 'U+E756', 'U+E4C1', 'U+E044', 'U+FE986', '\\ue044'), ('540', 'emoji1f37b', 'clinking beer mugs', 'U+1F37B', 'U+E672', 'U+EA98', 'U+E30C', 'U+FE987', '\\ue30c'), ('541', 'emoji1f379', 'tropical drink', 'U+1F379', 'U+E671', 'U+EB3E', 'U+E044', 'U+FE988', '\\ue044'), ('542', 'emoji2197', 'north east arrow', 'U+2197', 'U+E678', 'U+E555', 'U+E236', 'U+FEAF0', '\\ue236'), ('543', 'emoji2198', 'south east arrow', 'U+2198', 'U+E696', 'U+E54D', 'U+E238', 'U+FEAF1', '\\ue238'), ('544', 'emoji2196', 'north west arrow', 'U+2196', 'U+E697', 'U+E54C', 'U+E237', 'U+FEAF2', '\\ue237'), ('545', 'emoji2199', 'south west arrow', 'U+2199', 'U+E6A5', 'U+E556', 'U+E239', 'U+FEAF3', '\\ue239'), ('546', 'emoji2934', 'arrow pointing rightwards then curving u', 'U+2934', 'U+E6F5', 'U+EB2D', 'U+E236', 'U+FEAF4', '\\ue236'), ('547', 'emoji2935', 'arrow pointing rightwards then curving d', 'U+2935', 'U+E700', 'U+EB2E', 'U+E238', 'U+FEAF5', '\\ue238'), ('548', 'emoji2194', 'left right arrow', 'U+2194', 'U+E73C', 'U+EB7A', '-', 'U+FEAF6', '-'), ('549', 'emoji2195', 'up down arrow', 'U+2195', 'U+E73D', 'U+EB7B', '-', 'U+FEAF7', '-'), ('550', 'emoji2b06', 'upwards black arrow', 'U+2B06', '-', 'U+E53F', 'U+E232', 'U+FEAF8', '\\ue232'), ('551', 'emoji2b07', 'downwards black arrow', 'U+2B07', '-', 'U+E540', 'U+E233', 'U+FEAF9', '\\ue233'), ('552', 'emoji27a1', 'black rightwards arrow', 'U+27A1', '-', 'U+E552', 'U+E234', 'U+FEAFA', '\\ue234'), ('553', 'emoji2b05', 'leftwards black arrow', 'U+2B05', '-', 'U+E553', 'U+E235', 'U+FEAFB', '\\ue235'), ('554', 'emoji25b6', 'black right-pointing triangle', 'U+25B6', '-', 'U+E52E', 'U+E23A', 'U+FEAFC', '\\ue23a'), ('555', 'emoji25c0', 'black left-pointing triangle', 'U+25C0', '-', 'U+E52D', 'U+E23B', 'U+FEAFD', '\\ue23b'), ('556', 'emoji23e9', 'black right-pointing double triangle', 'U+23E9', '-', 'U+E530', 'U+E23C', 'U+FEAFE', '\\ue23c'), ('557', 'emoji23ea', 'black left-pointing double triangle', 'U+23EA', '-', 'U+E52F', 'U+E23D', 'U+FEAFF', '\\ue23d'), ('558', 'emoji23eb', 'black up-pointing double triangle', 'U+23EB', '-', 'U+E545', '-', 'U+FEB03', '-'), ('559', 'emoji23ec', 'black down-pointing double triangle', 'U+23EC', '-', 'U+E544', '-', 'U+FEB02', '-'), ('560', 'emoji1f53a', 'up-pointing red triangle', 'U+1F53A', '-', 'U+E55A', '-', 'U+FEB78', '-'), ('561', 'emoji1f53b', 'down-pointing red triangle', 'U+1F53B', '-', 'U+E55B', '-', 'U+FEB79', '-'), ('562', 'emoji1f53c', 'up-pointing small red triangle', 'U+1F53C', '-', 'U+E543', '-', 'U+FEB01', '-'), ('563', 'emoji1f53d', 'down-pointing small red triangle', 'U+1F53D', '-', 'U+E542', '-', 'U+FEB00', '-'), ('564', 'emoji2b55', 'heavy large circle', 'U+2B55', 'U+E6A0', 'U+EAAD', 'U+E332', 'U+FEB44', '\\ue332'), ('565', 'emoji274c', 'cross mark', 'U+274C', '-', 'U+E550', 'U+E333', 'U+FEB45', '\\ue333'), ('566', 'emoji274e', 'negative squared cross mark', 'U+274E', '-', 'U+E551', 'U+E333', 'U+FEB46', '\\ue333'), ('567', 'emoji2757', 'heavy exclamation mark symbol', 'U+2757', 'U+E702', 'U+E482', 'U+E021', 'U+FEB04', '\\ue021'), ('568', 'emoji2049', 'exclamation question mark', 'U+2049', 'U+E703', 'U+EB2F', '-', 'U+FEB05', '-'), ('569', 'emoji203c', 'double exclamation mark', 'U+203C', 'U+E704', 'U+EB30', '-', 'U+FEB06', '-'), ('570', 'emoji2753', 'black question mark ornament', 'U+2753', '-', 'U+E483', 'U+E020', 'U+FEB09', '\\ue020'), ('571', 'emoji2754', 'white question mark ornament', 'U+2754', '-', 'U+E483', 'U+E336', 'U+FEB0A', '\\ue336'), ('572', 'emoji2755', 'white exclamation mark ornament', 'U+2755', 'U+E702', 'U+E482', 'U+E337', 'U+FEB0B', '\\ue337'), ('573', 'emoji3030', 'wavy dash', 'U+3030', 'U+E709', '-', '-', 'U+FEB07', '-'), ('574', 'emoji27b0', 'curly loop', 'U+27B0', 'U+E70A', 'U+EB31', '-', 'U+FEB08', '-'), ('575', 'emoji27bf', 'double curly loop', 'U+27BF', 'U+E6DF', '-', 'U+E211', 'U+FE82B', '\\ue211'), ('576', 'emoji2764', 'heavy black heart', 'U+2764', 'U+E6EC', 'U+E595', 'U+E022', 'U+FEB0C', '\\ue022'), ('577', 'emoji1f493', 'beating heart', 'U+1F493', 'U+E6ED', 'U+EB75', 'U+E327', 'U+FEB0D', '\\ue327'), ('578', 'emoji1f494', 'broken heart', 'U+1F494', 'U+E6EE', 'U+E477', 'U+E023', 'U+FEB0E', '\\ue023'), ('579', 'emoji1f495', 'two hearts', 'U+1F495', 'U+E6EF', 'U+E478', 'U+E327', 'U+FEB0F', '\\ue327'), ('580', 'emoji1f496', 'sparkling heart', 'U+1F496', 'U+E6EC', 'U+EAA6', 'U+E327', 'U+FEB10', '\\ue327'), ('581', 'emoji1f497', 'growing heart', 'U+1F497', 'U+E6ED', 'U+EB75', 'U+E328', 'U+FEB11', '\\ue328'), ('582', 'emoji1f498', 'heart with arrow', 'U+1F498', 'U+E6EC', 'U+E4EA', 'U+E329', 'U+FEB12', '\\ue329'), ('583', 'emoji1f499', 'blue heart', 'U+1F499', 'U+E6EC', 'U+EAA7', 'U+E32A', 'U+FEB13', '\\ue32a'), ('584', 'emoji1f49a', 'green heart', 'U+1F49A', 'U+E6EC', 'U+EAA8', 'U+E32B', 'U+FEB14', '\\ue32b'), ('585', 'emoji1f49b', 'yellow heart', 'U+1F49B', 'U+E6EC', 'U+EAA9', 'U+E32C', 'U+FEB15', '\\ue32c'), ('586', 'emoji1f49c', 'purple heart', 'U+1F49C', 'U+E6EC', 'U+EAAA', 'U+E32D', 'U+FEB16', '\\ue32d'), ('587', 'emoji1f49d', 'heart with ribbon', 'U+1F49D', 'U+E6EC', 'U+EB54', 'U+E437', 'U+FEB17', '\\ue437'), ('588', 'emoji1f49e', 'revolving hearts', 'U+1F49E', 'U+E6ED', 'U+E5AF', 'U+E327', 'U+FEB18', '\\ue327'), ('589', 'emoji1f49f', 'heart decoration', 'U+1F49F', 'U+E6F8', 'U+E595', 'U+E204', 'U+FEB19', '\\ue204'), ('590', 'emoji2665', 'black heart suit', 'U+2665', 'U+E68D', 'U+EAA5', 'U+E20C', 'U+FEB1A', '\\ue20c'), ('591', 'emoji2660', 'black spade suit', 'U+2660', 'U+E68E', 'U+E5A1', 'U+E20E', 'U+FEB1B', '\\ue20e'), ('592', 'emoji2666', 'black diamond suit', 'U+2666', 'U+E68F', 'U+E5A2', 'U+E20D', 'U+FEB1C', '\\ue20d'), ('593', 'emoji2663', 'black club suit', 'U+2663', 'U+E690', 'U+E5A3', 'U+E20F', 'U+FEB1D', '\\ue20f'), ('594', 'emoji1f6ac', 'smoking symbol', 'U+1F6AC', 'U+E67F', 'U+E47D', 'U+E30E', 'U+FEB1E', '\\ue30e'), ('595', 'emoji1f6ad', 'no smoking symbol', 'U+1F6AD', 'U+E680', 'U+E47E', 'U+E208', 'U+FEB1F', '\\ue208'), ('596', 'emoji267f', 'wheelchair symbol', 'U+267F', 'U+E69B', 'U+E47F', 'U+E20A', 'U+FEB20', '\\ue20a'), ('597', 'emoji1f6a9', 'triangular flag on post', 'U+1F6A9', 'U+E6DE', 'U+EB2C', '-', 'U+FEB22', '-'), ('598', 'emoji26a0', 'warning sign', 'U+26A0', 'U+E737', 'U+E481', 'U+E252', 'U+FEB23', '\\ue252'), ('599', 'emoji26d4', 'no entry', 'U+26D4', 'U+E72F', 'U+E484', 'U+E137', 'U+FEB26', '\\ue137'), ('600', 'emoji267b', 'black universal recycling symbol', 'U+267B', 'U+E735', 'U+EB79', '-', 'U+FEB2C', '-'), ('601', 'emoji1f6b2', 'bicycle', 'U+1F6B2', 'U+E71D', 'U+E4AE', 'U+E136', 'U+FE7EB', '\\ue136'), ('602', 'emoji1f6b6', 'pedestrian', 'U+1F6B6', 'U+E733', 'U+EB72', 'U+E201', 'U+FE7F0', '\\ue201'), ('603', 'emoji1f6b9', 'mens symbol', 'U+1F6B9', '-', '-', 'U+E138', 'U+FEB33', '\\ue138'), ('604', 'emoji1f6ba', 'womens symbol', 'U+1F6BA', '-', '-', 'U+E139', 'U+FEB34', '\\ue139'), ('605', 'emoji1f6c0', 'bath', 'U+1F6C0', 'U+E6F7', 'U+E5D8', 'U+E13F', 'U+FE505', '\\ue13f'), ('606', 'emoji1f6bb', 'restroom', 'U+1F6BB', 'U+E66E', 'U+E4A5', 'U+E151', 'U+FE506', '\\ue151'), ('607', 'emoji1f6bd', 'toilet', 'U+1F6BD', 'U+E66E', 'U+E4A5', 'U+E140', 'U+FE507', '\\ue140'), ('608', 'emoji1f6be', 'water closet', 'U+1F6BE', 'U+E66E', 'U+E4A5', 'U+E309', 'U+FE508', '\\ue309'), ('609', 'emoji1f6bc', 'baby symbol', 'U+1F6BC', '-', 'U+EB18', 'U+E13A', 'U+FEB35', '\\ue13a'), ('610', 'emoji1f6aa', 'door', 'U+1F6AA', 'U+E714', '-', '-', 'U+FE4F3', '-'), ('611', 'emoji1f6ab', 'no entry sign', 'U+1F6AB', 'U+E738', 'U+E541', '-', 'U+FEB48', '-'), ('612', 'emoji2714', 'heavy check mark', 'U+2714', '-', 'U+E557', '-', 'U+FEB49', '-'), ('613', 'emoji1f191', 'squared cl', 'U+1F191', 'U+E6DB', 'U+E5AB', '-', 'U+FEB84', '-'), ('614', 'emoji1f192', 'squared cool', 'U+1F192', '-', 'U+EA85', 'U+E214', 'U+FEB38', '\\ue214'), ('615', 'emoji1f193', 'squared free', 'U+1F193', 'U+E6D7', 'U+E578', '-', 'U+FEB21', '-'), ('616', 'emoji1f194', 'squared id', 'U+1F194', 'U+E6D8', 'U+EA88', 'U+E229', 'U+FEB81', '\\ue229'), ('617', 'emoji1f195', 'squared new', 'U+1F195', 'U+E6DD', 'U+E5B5', 'U+E212', 'U+FEB36', '\\ue212'), ('618', 'emoji1f196', 'squared ng', 'U+1F196', 'U+E72F', '-', '-', 'U+FEB28', '-'), ('619', 'emoji1f197', 'squared ok', 'U+1F197', 'U+E70B', 'U+E5AD', 'U+E24D', 'U+FEB27', '\\ue24d'), ('620', 'emoji1f198', 'squared sos', 'U+1F198', '-', 'U+E4E8', '-', 'U+FEB4F', '-'), ('621', 'emoji1f199', 'squared up with exclamation mark', 'U+1F199', '-', 'U+E50F', 'U+E213', 'U+FEB37', '\\ue213'), ('622', 'emoji1f19a', 'squared vs', 'U+1F19A', '-', 'U+E5D2', 'U+E12E', 'U+FEB32', '\\ue12e'), ('623', 'emoji1f201', 'squared katakana koko', 'U+1F201', '-', '-', 'U+E203', 'U+FEB24', '\\ue203'), ('624', 'emoji1f202', 'squared katakana sa', 'U+1F202', '-', 'U+EA87', 'U+E228', 'U+FEB3F', '\\ue228'), ('625', 'emoji1f232', 'squared cjk unified ideograph-7981', 'U+1F232', 'U+E738', '-', '-', 'U+FEB2E', '-'), ('626', 'emoji1f233', 'squared cjk unified ideograph-7a7a', 'U+1F233', 'U+E739', 'U+EA8A', 'U+E22B', 'U+FEB2F', '\\ue22b'), ('627', 'emoji1f234', 'squared cjk unified ideograph-5408', 'U+1F234', 'U+E73A', '-', '-', 'U+FEB30', '-'), ('628', 'emoji1f235', 'squared cjk unified ideograph-6e80', 'U+1F235', 'U+E73B', 'U+EA89', 'U+E22A', 'U+FEB31', '\\ue22a'), ('629', 'emoji1f236', 'squared cjk unified ideograph-6709', 'U+1F236', '-', '-', 'U+E215', 'U+FEB39', '\\ue215'), ('630', 'emoji1f21a', 'squared cjk unified ideograph-7121', 'U+1F21A', '-', '-', 'U+E216', 'U+FEB3A', '\\ue216'), ('631', 'emoji1f237', 'squared cjk unified ideograph-6708', 'U+1F237', '-', '-', 'U+E217', 'U+FEB3B', '\\ue217'), ('632', 'emoji1f238', 'squared cjk unified ideograph-7533', 'U+1F238', '-', '-', 'U+E218', 'U+FEB3C', '\\ue218'), ('633', 'emoji1f239', 'squared cjk unified ideograph-5272', 'U+1F239', '-', 'U+EA86', 'U+E227', 'U+FEB3E', '\\ue227'), ('634', 'emoji1f22f', 'squared cjk unified ideograph-6307', 'U+1F22F', '-', 'U+EA8B', 'U+E22C', 'U+FEB40', '\\ue22c'), ('635', 'emoji1f23a', 'squared cjk unified ideograph-55b6', 'U+1F23A', '-', 'U+EA8C', 'U+E22D', 'U+FEB41', '\\ue22d'), ('636', 'emoji3299', 'circled ideograph secret', 'U+3299', 'U+E734', 'U+E4F1', 'U+E315', 'U+FEB2B', '\\ue315'), ('637', 'emoji3297', 'circled ideograph congratulation', 'U+3297', '-', 'U+EA99', 'U+E30D', 'U+FEB43', '\\ue30d'), ('638', 'emoji1f250', 'circled ideograph advantage', 'U+1F250', '-', 'U+E4F7', 'U+E226', 'U+FEB3D', '\\ue226'), ('639', 'emoji1f251', 'circled ideograph accept', 'U+1F251', '-', 'U+EB01', '-', 'U+FEB50', '-'), ('640', 'emoji2795', 'heavy plus sign', 'U+2795', '-', 'U+E53C', '-', 'U+FEB51', '-'), ('641', 'emoji2796', 'heavy minus sign', 'U+2796', '-', 'U+E53D', '-', 'U+FEB52', '-'), ('642', 'emoji2716', 'heavy multiplication x', 'U+2716', '-', 'U+E54F', 'U+E333', 'U+FEB53', '\\ue333'), ('643', 'emoji2797', 'heavy division sign', 'U+2797', '-', 'U+E554', '-', 'U+FEB54', '-'), ('644', 'emoji1f4a0', 'diamond shape with a dot inside', 'U+1F4A0', 'U+E6F8', '-', '-', 'U+FEB55', '-'), ('645', 'emoji1f4a1', 'electric light bulb', 'U+1F4A1', 'U+E6FB', 'U+E476', 'U+E10F', 'U+FEB56', '\\ue10f'), ('646', 'emoji1f4a2', 'anger symbol', 'U+1F4A2', 'U+E6FC', 'U+E4E5', 'U+E334', 'U+FEB57', '\\ue334'), ('647', 'emoji1f4a3', 'bomb', 'U+1F4A3', 'U+E6FE', 'U+E47A', 'U+E311', 'U+FEB58', '\\ue311'), ('648', 'emoji1f4a4', 'sleeping symbol', 'U+1F4A4', 'U+E701', 'U+E475', 'U+E13C', 'U+FEB59', '\\ue13c'), ('649', 'emoji1f4a5', 'collision symbol', 'U+1F4A5', 'U+E705', 'U+E5B0', '-', 'U+FEB5A', '-'), ('650', 'emoji1f4a6', 'splashing sweat symbol', 'U+1F4A6', 'U+E706', 'U+E5B1', 'U+E331', 'U+FEB5B', '\\ue331'), ('651', 'emoji1f4a7', 'droplet', 'U+1F4A7', 'U+E707', 'U+E4E6', 'U+E331', 'U+FEB5C', '\\ue331'), ('652', 'emoji1f4a8', 'dash symbol', 'U+1F4A8', 'U+E708', 'U+E4F4', 'U+E330', 'U+FEB5D', '\\ue330'), ('653', 'emoji1f4a9', 'pile of poo', 'U+1F4A9', '-', 'U+E4F5', 'U+E05A', 'U+FE4F4', '\\ue05a'), ('654', 'emoji1f4aa', 'flexed biceps', 'U+1F4AA', '-', 'U+E4E9', 'U+E14C', 'U+FEB5E', '\\ue14c'), ('655', 'emoji1f4ab', 'dizzy symbol', 'U+1F4AB', '-', 'U+EB5C', 'U+E407', 'U+FEB5F', '\\ue407');
INSERT INTO `__PREFIX__emoji_code` VALUES ('656', 'emoji1f4ac', 'speech balloon', 'U+1F4AC', '-', 'U+E4FD', '-', 'U+FE532', '-'), ('657', 'emoji2728', 'sparkles', 'U+2728', 'U+E6FA', 'U+EAAB', 'U+E32E', 'U+FEB60', '\\ue32e'), ('658', 'emoji2734', 'eight pointed black star', 'U+2734', 'U+E6F8', 'U+E479', 'U+E205', 'U+FEB61', '\\ue205'), ('659', 'emoji2733', 'eight spoked asterisk', 'U+2733', 'U+E6F8', 'U+E53E', 'U+E206', 'U+FEB62', '\\ue206'), ('660', 'emoji26aa', 'medium white circle', 'U+26AA', 'U+E69C', 'U+E53A', 'U+E219', 'U+FEB65', '\\ue219'), ('661', 'emoji26ab', 'medium black circle', 'U+26AB', 'U+E69C', 'U+E53B', 'U+E219', 'U+FEB66', '\\ue219'), ('662', 'emoji1f534', 'large red circle', 'U+1F534', 'U+E69C', 'U+E54A', 'U+E219', 'U+FEB63', '\\ue219'), ('663', 'emoji1f535', 'large blue circle', 'U+1F535', 'U+E69C', 'U+E54B', 'U+E21A', 'U+FEB64', '\\ue21a'), ('664', 'emoji1f532', 'black square button', 'U+1F532', 'U+E69C', 'U+E54B', 'U+E21A', 'U+FEB64', '\\ue21a'), ('665', 'emoji1f533', 'white square button', 'U+1F533', 'U+E69C', 'U+E54B', 'U+E21B', 'U+FEB67', '\\ue21b'), ('666', 'emoji2b50', 'white medium star', 'U+2B50', '-', 'U+E48B', 'U+E32F', 'U+FEB68', '\\ue32f'), ('667', 'emoji2b1c', 'white large square', 'U+2B1C', '-', 'U+E548', 'U+E21B', 'U+FEB6B', '\\ue21b'), ('668', 'emoji2b1b', 'black large square', 'U+2B1B', '-', 'U+E549', 'U+E21A', 'U+FEB6C', '\\ue21a'), ('669', 'emoji25ab', 'white small square', 'U+25AB', '-', 'U+E531', 'U+E21B', 'U+FEB6D', '\\ue21b'), ('670', 'emoji25aa', 'black small square', 'U+25AA', '-', 'U+E532', 'U+E21A', 'U+FEB6E', '\\ue21a'), ('671', 'emoji25fd', 'white medium small square', 'U+25FD', '-', 'U+E534', 'U+E21B', 'U+FEB6F', '\\ue21b'), ('672', 'emoji25fe', 'black medium small square', 'U+25FE', '-', 'U+E535', 'U+E21A', 'U+FEB70', '\\ue21a'), ('673', 'emoji25fb', 'white medium square', 'U+25FB', '-', 'U+E538', 'U+E21B', 'U+FEB71', '\\ue21b'), ('674', 'emoji25fc', 'black medium square', 'U+25FC', '-', 'U+E539', 'U+E21A', 'U+FEB72', '\\ue21a'), ('675', 'emoji1f536', 'large orange diamond', 'U+1F536', '-', 'U+E546', 'U+E21B', 'U+FEB73', '\\ue21b'), ('676', 'emoji1f537', 'large blue diamond', 'U+1F537', '-', 'U+E547', 'U+E21B', 'U+FEB74', '\\ue21b'), ('677', 'emoji1f538', 'small orange diamond', 'U+1F538', '-', 'U+E536', 'U+E21B', 'U+FEB75', '\\ue21b'), ('678', 'emoji1f539', 'small blue diamond', 'U+1F539', '-', 'U+E537', 'U+E21B', 'U+FEB76', '\\ue21b'), ('679', 'emoji2747', 'sparkle', 'U+2747', 'U+E6FA', 'U+E46C', 'U+E32E', 'U+FEB77', '\\ue32e'), ('680', 'emoji1f4ae', 'white flower', 'U+1F4AE', '-', 'U+E4F0', '-', 'U+FEB7A', '-'), ('681', 'emoji1f4af', 'hundred points symbol', 'U+1F4AF', '-', 'U+E4F2', '-', 'U+FEB7B', '-'), ('682', 'emoji21a9', 'leftwards arrow with hook', 'U+21A9', 'U+E6DA', 'U+E55D', '-', 'U+FEB83', '-'), ('683', 'emoji21aa', 'rightwards arrow with hook', 'U+21AA', '-', 'U+E55C', '-', 'U+FEB88', '-'), ('684', 'emoji1f503', 'clockwise downwards and upwards open cir', 'U+1F503', 'U+E735', 'U+EB0D', '-', 'U+FEB91', '-'), ('685', 'emoji1f50a', 'speaker with three sound waves', 'U+1F50A', '-', 'U+E511', 'U+E141', 'U+FE821', '\\ue141'), ('686', 'emoji1f50b', 'battery', 'U+1F50B', '-', 'U+E584', '-', 'U+FE4FC', '-'), ('687', 'emoji1f50c', 'electric plug', 'U+1F50C', '-', 'U+E589', '-', 'U+FE4FE', '-'), ('688', 'emoji1f50d', 'left-pointing magnifying glass', 'U+1F50D', 'U+E6DC', 'U+E518', 'U+E114', 'U+FEB85', '\\ue114'), ('689', 'emoji1f50e', 'right-pointing magnifying glass', 'U+1F50E', 'U+E6DC', 'U+EB05', 'U+E114', 'U+FEB8D', '\\ue114'), ('690', 'emoji1f512', 'lock', 'U+1F512', 'U+E6D9', 'U+E51C', 'U+E144', 'U+FEB86', '\\ue144'), ('691', 'emoji1f513', 'open lock', 'U+1F513', 'U+E6D9', 'U+E51C', 'U+E145', 'U+FEB87', '\\ue145'), ('692', 'emoji1f50f', 'lock with ink pen', 'U+1F50F', 'U+E6D9', 'U+EB0C', 'U+E144', 'U+FEB90', '\\ue144'), ('693', 'emoji1f510', 'closed lock with key', 'U+1F510', 'U+E6D9', 'U+EAFC', 'U+E144', 'U+FEB8A', '\\ue144'), ('694', 'emoji1f511', 'key', 'U+1F511', 'U+E6D9', 'U+E519', 'U+E03F', 'U+FEB82', '\\ue03f'), ('695', 'emoji1f514', 'bell', 'U+1F514', 'U+E713', 'U+E512', 'U+E325', 'U+FE4F2', '\\ue325'), ('696', 'emoji2611', 'ballot box with check', 'U+2611', '-', 'U+EB02', '-', 'U+FEB8B', '-'), ('697', 'emoji1f518', 'radio button', 'U+1F518', '-', 'U+EB04', '-', 'U+FEB8C', '-'), ('698', 'emoji1f516', 'bookmark', 'U+1F516', '-', 'U+EB07', '-', 'U+FEB8F', '-'), ('699', 'emoji1f517', 'link symbol', 'U+1F517', '-', 'U+E58A', '-', 'U+FEB4B', '-'), ('700', 'emoji1f519', 'back with leftwards arrow above', 'U+1F519', '-', 'U+EB06', 'U+E235', 'U+FEB8E', '\\ue235'), ('701', 'emoji1f51a', 'end with leftwards arrow above', 'U+1F51A', 'U+E6B9', '-', '-', 'U+FE01A', '-'), ('702', 'emoji1f51b', 'on with exclamation mark with left right', 'U+1F51B', 'U+E6B8', '-', '-', 'U+FE019', '-'), ('703', 'emoji1f51c', 'soon with rightwards arrow above', 'U+1F51C', 'U+E6B7', '-', '-', 'U+FE018', '-'), ('704', 'emoji1f51d', 'top with upwards arrow above', 'U+1F51D', '-', '-', 'U+E24C', 'U+FEB42', '\\ue24c'), ('705', 'emoji2705', 'white heavy check mark', 'U+2705', '-', 'U+E55E', '-', 'U+FEB4A', '-'), ('706', 'emoji270a', 'raised fist', 'U+270A', 'U+E693', 'U+EB83', 'U+E010', 'U+FEB93', '\\ue010'), ('707', 'emoji270b', 'raised hand', 'U+270B', 'U+E695', 'U+E5A7', 'U+E012', 'U+FEB95', '\\ue012'), ('708', 'emoji270c', 'victory hand', 'U+270C', 'U+E694', 'U+E5A6', 'U+E011', 'U+FEB94', '\\ue011'), ('709', 'emoji1f44a', 'fisted hand sign', 'U+1F44A', 'U+E6FD', 'U+E4F3', 'U+E00D', 'U+FEB96', '\\ue00d'), ('710', 'emoji1f44d', 'thumbs up sign', 'U+1F44D', 'U+E727', 'U+E4F9', 'U+E00E', 'U+FEB97', '\\ue00e'), ('711', 'emoji261d', 'white up pointing index', 'U+261D', '-', 'U+E4F6', 'U+E00F', 'U+FEB98', '\\ue00f'), ('712', 'emoji1f446', 'white up pointing backhand index', 'U+1F446', '-', 'U+EA8D', 'U+E22E', 'U+FEB99', '\\ue22e'), ('713', 'emoji1f447', 'white down pointing backhand index', 'U+1F447', '-', 'U+EA8E', 'U+E22F', 'U+FEB9A', '\\ue22f'), ('714', 'emoji1f448', 'white left pointing backhand index', 'U+1F448', '-', 'U+E4FF', 'U+E230', 'U+FEB9B', '\\ue230'), ('715', 'emoji1f449', 'white right pointing backhand index', 'U+1F449', '-', 'U+E500', 'U+E231', 'U+FEB9C', '\\ue231'), ('716', 'emoji1f44b', 'waving hand sign', 'U+1F44B', 'U+E695', 'U+EAD6', 'U+E41E', 'U+FEB9D', '\\ue41e'), ('717', 'emoji1f44f', 'clapping hands sign', 'U+1F44F', '-', 'U+EAD3', 'U+E41F', 'U+FEB9E', '\\ue41f'), ('718', 'emoji1f44c', 'ok hand sign', 'U+1F44C', 'U+E70B', 'U+EAD4', 'U+E420', 'U+FEB9F', '\\ue420'), ('719', 'emoji1f44e', 'thumbs down sign', 'U+1F44E', 'U+E700', 'U+EAD5', 'U+E421', 'U+FEBA0', '\\ue421'), ('720', 'emoji1f450', 'open hands sign', 'U+1F450', 'U+E695', 'U+EAD6', 'U+E422', 'U+FEBA1', '\\ue422');

-- ----------------------------
-- 2.0.2
-- ----------------------------
ALTER TABLE `__PREFIX__reply` ADD COLUMN `need_at` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否需要at' AFTER `sort`;
ALTER TABLE `__PREFIX__reply` ADD COLUMN `handle_type` varchar(20) NOT NULL DEFAULT 'msg' COMMENT '响应类型：msg回复消息  rm移出群  del删除好友' AFTER `need_at`;
ALTER TABLE `__PREFIX__reply` ADD COLUMN `msg_type` smallint(6) NOT NULL DEFAULT 1 COMMENT '消息类型' AFTER `handle_type`;
ALTER TABLE `__PREFIX__task` ADD COLUMN `atall` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否艾特所有人' AFTER `plan_hour`;

DROP TABLE IF EXISTS `__PREFIX__ai_config`;
CREATE TABLE `__PREFIX__ai_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0',
  `key` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `value` text COLLATE utf8mb4_unicode_ci,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `bot_id` (`bot_id`,`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='openai配置表';

-- ----------------------------
-- 2.0.4
-- ----------------------------
ALTER TABLE `__PREFIX__keyword` ADD COLUMN `need_at` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '艾特提问者' AFTER `user_type`;

-- ----------------------------
-- Table structure for ky_zdjr_block
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__zdjr_block`;
CREATE TABLE `__PREFIX__zdjr_block`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `bot_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '机器人id',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否被锁，1被锁 0释放',
  `create_time` int(10) UNSIGNED NOT NULL,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `bot_id`(`bot_id`) USING BTREE,
  INDEX `admin_id`(`admin_id`, `bot_id`, `status`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '请求频繁机器人名单' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_zdjr_clue
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__zdjr_clue`;
CREATE TABLE `__PREFIX__zdjr_clue`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `bot_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '执行加人操作的机器人',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '名称',
  `content` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'QQ微信号手机号wxid',
  `type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'qq|wxnum|mobile|wxid',
  `step` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '加人进度,0 未申请 1 已申请 2已通过',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `create_time` int(10) UNSIGNED NOT NULL,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `wxid` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `project_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `admin_id`(`admin_id`, `content`, `type`) USING BTREE,
  INDEX `todo`(`admin_id`, `step`, `status`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '线索表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_zdjr_config
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__zdjr_config`;
CREATE TABLE `__PREFIX__zdjr_config`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `bot_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `key` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `admin_key`(`admin_id`, `key`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_zdjr_log
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__zdjr_log`;
CREATE TABLE `__PREFIX__zdjr_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `bot_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '执行加人操作的机器人',
  `rule_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `clue_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '线索ID',
  `res` tinyint(1) NOT NULL DEFAULT 0 COMMENT '搜索结果',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `create_time` int(10) UNSIGNED NOT NULL,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '线索表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_zdjr_project
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__zdjr_project`;
CREATE TABLE `__PREFIX__zdjr_project`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '名称',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用',
  `create_time` int(10) UNSIGNED NOT NULL,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '项目表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_zdjr_project_bot
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__zdjr_project_bot`;
CREATE TABLE `__PREFIX__zdjr_project_bot`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `bot_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `project_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用',
  `create_time` int(10) UNSIGNED NOT NULL,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '项目-机器人关联表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_zdjr_rule
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__zdjr_rule`;
CREATE TABLE `__PREFIX__zdjr_rule`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `bots` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '参与任务的机器人',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '名称',
  `rules` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '规则内容',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否启用',
  `create_time` int(10) UNSIGNED NOT NULL,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `project_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '策略表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- 2.0.5
-- ----------------------------
ALTER TABLE `__PREFIX__bot_member`
ADD COLUMN `last_chat_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后聊天时间' AFTER `internal`,
ADD COLUMN `sex` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1男 2女' AFTER `last_chat_time`,
ADD COLUMN `province` varchar(50) NOT NULL DEFAULT '' AFTER `sex`,
ADD COLUMN `city` varchar(50) NOT NULL DEFAULT '' AFTER `province`;
ALTER TABLE `__PREFIX__bot`
ADD COLUMN `username` varchar(20) NOT NULL DEFAULT '';
ALTER TABLE `__PREFIX__bot_groupmember`
ADD COLUMN `headimgurl` varchar(200) NOT NULL DEFAULT '';
-- ----------------------------
-- 2.1.0
-- ----------------------------
ALTER TABLE `__PREFIX__admin`  ADD COLUMN `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所属商户ID' AFTER `update_time`;
ALTER TABLE `__PREFIX__bot` MODIFY COLUMN `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT '商户ID' AFTER `id`, ADD COLUMN `staff_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '员工ID' AFTER `admin_id`,DROP INDEX `admin_id`,ADD INDEX `admin_id`(`admin_id`, `staff_id`) USING BTREE;
ALTER TABLE `__PREFIX__admin_group` ADD COLUMN `tenant_group` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 AFTER `update_time`;
ALTER TABLE `__PREFIX__admin_group` ADD COLUMN `admin_id` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 AFTER `update_time`;
