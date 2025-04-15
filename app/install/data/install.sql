-- ----------------------------
-- Table structure for ky_admin
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__admin`;
CREATE TABLE `__PREFIX__admin`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_id` int(10) NOT NULL DEFAULT 1 COMMENT '所属部门',
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '账号',
  `password` char(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '密码',
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `mobile` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `realname` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '姓名',
  `ip` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '登录ip',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态',
  `last_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后登录时间',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '新增时间',
  `update_time` int(10) NOT NULL DEFAULT 0 COMMENT '最后修改时间',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所属商户ID',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_admin_group
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__admin_group`;
CREATE TABLE `__PREFIX__admin_group`  (
  `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '商户ID',
  `name` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '部门标识',
  `title` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `rules` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `sort` tinyint(3) NOT NULL DEFAULT 0,
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `tenant_group` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否客户角色',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `title`(`title`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

INSERT INTO `__PREFIX__admin_group` VALUES ('1', '1','super_admin', '超级管理员', '1', '', '0', '0', '1445158837', '拥有系统最高管理权限', '1626581117', 0),('2', '1','', '客户', '1', '169,162', '0', '0', '1445158837', '通过自主注册的商户', '1626581117', 1);

-- ----------------------------
-- Table structure for ky_admin_rule
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__admin_rule`;
CREATE TABLE `__PREFIX__admin_rule`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标识',
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题名称',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级菜单',
  `icon` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图标',
  `href` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '路径',
  `target` enum('_self','_blank') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '_self' COMMENT '打开方式',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否显示',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后更新时间',
  `sort` int(6) UNSIGNED NOT NULL DEFAULT 0 COMMENT '数字越小越靠前',
  `type` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1菜单 2权限',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ky_admin_rule
-- ----------------------------
INSERT INTO `__PREFIX__admin_rule` VALUES ('1', '', '系统管理', '0', '', '', '_self', '1', '0', '1626845872', '0', '1'), ('3', '', '员工管理', '194', '', '/admin/admin/index', '_self', '1', '0', '1676530002', '1', '1'), ('4', '', '菜单管理', '1', 'fa fa-align-justify', '/admin/adminrule/index', '_self', '1', '0', '1640017490', '3', '1'),('217', '', '系统公告', '1', 'fa fa-bullhorn', '/admin/notice/community', '_self', '1', '0', '1640017490', '3', '1'),('218', '', '操作日志', '1', 'fa fa-braille', '/admin/adminlog/index', '_self', '1', '0', '1640017490', '3', '1'), ('6', '', '基础功能', '0', '', '', '_self', '1', '1599463278', '1649926644', '18', '1'), ('1000', '', '消息记录', '158', '', '/admin/msglog/index', '_self', '1', '1604904482', '1655799164', '0', '1'), ('105', '', '消息转播', '158', '', '/admin/forward/index', '_self', '1', '1604904482', '1655799164', '1', '1'), ('106', '', '被动回复', '158', '', '/admin/reply/index', '_self', '1', '1604904505', '1640364369', '5', '1'), ('107', '', '角色管理', '194', '', '/admin/admingroup/index', '_self', '1', '1604904540', '1676530036', '1', '1'),  ('148', '', '配置管理', '1', 'fa fa-cogs', '/admin/setting/index', '_self', '1', '1624803694', '1640366685', '4', '1'), ('149', '', '应用管理', '0', '', '', '_self', '1', '1625712595', '1684985921', '15', '1'), ('150', '', '渠道管理', '7', '', '/admin/tpzschannel/index', '_self', '1', '1625712631', '1649233035', '5', '1'), ('151', '', '机器人列表', '162', '', '/admin/bot/index', '_self', '1', '1625725311', '1640018227', '5', '1'), ('152', '', '通讯录', '6', 'fa fa-address-book', '', '_self', '1', '1625737689', '1650419650', '20', '1'), ('153', '', '电商联盟', '7', '', '/admin/tpzsunion/index', '_self', '1', '1625737734', '1648523931', '1', '1'), ('157', '', '关键词回复', '158', '', '/admin/keyword/index', '_self', '1', '1626505434', '1650262233', '10', '1'), ('158', '', '快捷设置', '6', 'fa fa-cogs', '', '_self', '1', '1626505455', '1640365845', '40', '1'), ('159', '', '消息群发', '158', '', '/admin/task/index', '_self', '1', '1626505494', '1650459935', '1', '1'), ('160', '', '好友列表', '152', '', '/admin/botfriend/index', '_self', '1', '1626505753', '1682133649', '10', '1'), ('162', '', '机器人管理', '169', 'fa fa-android', '', '_self', '1', '1626507555', '1640844683', '50', '1'), ('166', '', '群组列表', '152', '', '/admin/botgroup/index', '_self', '1', '1626509815', '1646837078', '9', '1'), ('168', '', '素材库', '169', 'fa fa-book', '', '_self', '1', '1640786676', '1683701902', '1', '1'), ('169', '', '全局设置', '0', '', '', '_self', '1', '1640844673', '1650466761', '20', '1'), ('170', '', '基础设置', '7', '', '/admin/tpzsconfig/index', '_self', '1', '1646839737', '1648534905', '0', '1'), ('171', '', '采品群', '7', '', '/admin/tpzsgather/index', '_self', '1', '1646839758', '1648546385', '45', '1'), ('172', '', '机器人负责群', '7', '', '/admin/tpzsteam/index', '_self', '0', '1646839780', '1653879660', '40', '1'), ('173', '', '发送列表', '7', '', '/admin/tpzstask/index', '_self', '1', '1646839807', '1648439722', '50', '1'), ('176', '', '系统升级', '1', 'fa fa-cloud-upload', '/admin/upgrade/index', '_self', '1', '1657205545', '1657205566', '1', '1'), ('186', '', '朋友圈', '6', 'fa fa-chrome', '', '_self', '1', '1663308146', '1663308146', '5', '1'), ('187', '', '获取朋友圈', '186', '', '/admin/moments/pull', '_self', '1', '1663308205', '1663308336', '10', '1'), ('188', '', '发朋友圈', '186', '', '/admin/moments/index', '_self', '1', '1663308300', '1663308300', '8', '1'), ('192', '', '客户管理', '1', 'fa fa-users', '', '_self', '1', '1676518784', '1677032365', '10', '1'), ('193', '', '运营管理', '0', '', '', '_self', '1', '1676518921', '1676518921', '10', '1'), ('194', '', '组织管理', '193', 'fa fa-connectdevelop', '', '_self', '1', '1676529954', '1676529982', '1', '1'), ('195', '', '账号额度管理', '193', 'fa fa-cc', '', '_self', '1', '1677031565', '1677031565', '5', '1'), ('196', '', '账号申请', '195', '', '/admin/botapply/index', '_self', '1', '1677031582', '1677031600', '5', '1'), ('197', '', '客户列表', '192', '', '/admin/tenant/index', '_self', '1', '1677032354', '1677032354', '10', '1'), ('198', '', '账号额度申请', '192', '', '/admin/botapply/applylist', '_self', '1', '1677032418', '1677033819', '5', '1'), ('199', '', '服务器管理', '1', 'fa fa-server', '', '_self', '1', '1677144245', '1677144245', '5', '1'), ('200', '', '服务器列表', '199', '', '/admin/server/index', '_self', '1', '1677144271', '1677144271', '5', '1'), ('207', '', '分组管理', '152', '', '/admin/membertag/index', '_self', '1', '1682133616', '1682133616', '1', '1'), ('208', '', '素材列表', '168', '', '/admin/mediatext/index', '_self', '1', '1683701924', '1683702229', '5', '1'), ('209', '', '素材分组', '168', '', '/admin/mediagroup/index', '_self', '1', '1683702001', '1683702001', '1', '1'), ('210', '', '官方市场', '149', 'fa fa-apple', '', '_self', '1', '1684915556', '1684915556', '20', '1'), ('211', '', '应用商店', '210', '', '/admin/appstore/index', '_self', '1', '1684915593', '1684915593', '10', '1'), ('212', '', '应用升级', '210', '', '/admin/appstore/upgrade', '_self', '1', '1684915629', '1684915629', '1', '1'), ('213', '', '应用中心', '149', 'fa fa-cubes', '', '_self', '1', '1684936224', '1684985949', '50', '1'), ('214', '', '操作台', '149', 'fa fa-dashboard', '/admin/apps/index', '_self', '1', '1684936261', '1684984019', '100', '1'), ('215', '', '应用管理', '213', '', '/admin/apps/installedlist', '_self', '1', '1684936301', '1684983898', '1', '1');

-- ----------------------------
-- Table structure for ky_bot
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__bot`;
CREATE TABLE `__PREFIX__bot`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT '商户ID',
  `staff_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '员工ID',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '账号',
  `app_key` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '密码',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '微信用户昵称',
  `headimgurl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '新增时间',
  `update_time` int(10) NOT NULL DEFAULT 0 COMMENT '最后修改时间',
  `uuid` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '微信号或会话ID',
  `uin` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '当前登录微信用户的id',
  `is_current` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否当前操作',
  `login_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '登录时间',
  `protocol` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'vlw' COMMENT '使用协议： vlw，web',
  `url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '接口地址',
  `alive` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否登录',
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '微信号',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `app_key`(`app_key`) USING BTREE,
  INDEX `uin`(`uin`, `alive`) USING BTREE,
  INDEX `admin_id`(`admin_id`, `staff_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '个微机器人' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_bot_apply
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__bot_apply`;
CREATE TABLE `__PREFIX__bot_apply`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `wx_num` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `deadline` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '截至日期',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0 1 2',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `month` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '开通月数',
  `staff_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '绑定员工id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_bot_groupmember
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__bot_groupmember`;
CREATE TABLE `__PREFIX__bot_groupmember`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `bot_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '机器人id',
  `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '群id',
  `wxid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '成员wxid',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '成员微信昵称',
  `group_nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '群内昵称',
  `username` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '微信号',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '新增时间',
  `update_time` int(10) NOT NULL DEFAULT 0 COMMENT '最后修改时间',
  `headimgurl` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`wxid`, `group_id`) USING BTREE,
  INDEX `group_id`(`group_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '群成员' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_bot_member
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__bot_member`;
CREATE TABLE `__PREFIX__bot_member`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uin` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '机器人id',
  `alias` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '别名',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '微信用户昵称',
  `remark_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注名',
  `username` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'xp:  用户微信号\r\nweb:当前登录下的好友唯一标识（每次登录不一样）',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '新增时间',
  `update_time` int(10) NOT NULL DEFAULT 0 COMMENT '最后修改时间',
  `type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'friend' COMMENT 'friend、group、mp',
  `headimgurl` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `wxid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '微信id',
  `internal` tinyint(1) UNSIGNED NOT NULL DEFAULT 2 COMMENT '企业微信区分内部和外部，默认外部群',
  `last_chat_time` int(11) NOT NULL DEFAULT 0 COMMENT '最后聊天时间',
  `sex` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1男 2女',
  `province` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `city` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `dnd` tinyint(1) NOT NULL DEFAULT 0 COMMENT '消息免打扰',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `wxid_botid`(`uin`, `wxid`) USING BTREE,
  INDEX `uin`(`uin`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '通讯录' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_district
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__district`;
CREATE TABLE `__PREFIX__district`  (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '名称',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上级id',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '省市地区字典' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_emoji_code
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__emoji_code`;
CREATE TABLE `__PREFIX__emoji_code`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `unified` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `docomo` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `kddi` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `softbank` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `google` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `softb_unicode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_forward
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__forward`;
CREATE TABLE `__PREFIX__forward`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL,
  `bot_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'bot id',
  `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '群聊id',
  `officer` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '指挥官',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `wxids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '转发对象',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '群聊-推广位关联表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_group_rule
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__group_rule`;
CREATE TABLE `__PREFIX__group_rule`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `bot_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `wxids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `rule` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'rm' COMMENT 'rm移除群',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `value` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '设置值',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '群规则设置' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_hanzi_config
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__hanzi_config`;
CREATE TABLE `__PREFIX__hanzi_config`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `bot_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `key` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `wxids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '指定对象',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '推品助手配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_keyword
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__keyword`;
CREATE TABLE `__PREFIX__keyword`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL,
  `bot_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `keyword` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '关键词',
  `media_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '回复类型',
  `media_id` int(10) NOT NULL DEFAULT 0 COMMENT '触发应用',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1正常 0禁用',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `wxids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '作用对象，好友wxid或群wxid',
  `sort` int(6) UNSIGNED NOT NULL DEFAULT 0,
  `user_type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0所有 1好友 2群聊',
  `need_at` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '艾特提问者',
  `match_type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0完全匹配  1模糊匹配',
  `medias` text COMMENT '素材',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `unique`(`keyword`, `bot_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '被动回复' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_media_file_1
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_file_1`;
CREATE TABLE `__PREFIX__media_file_1`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT 0,
  `update_time` int(10) NOT NULL DEFAULT 0,
  `size` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '格式',
  `location` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'qiniu',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文件素材库' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_media_file_2
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_file_2`;
CREATE TABLE `__PREFIX__media_file_2`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT 0,
  `update_time` int(10) NOT NULL DEFAULT 0,
  `size` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '格式',
  `location` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'qiniu',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文件素材库' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_media_file_3
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_file_3`;
CREATE TABLE `__PREFIX__media_file_3`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT 0,
  `update_time` int(10) NOT NULL DEFAULT 0,
  `size` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '格式',
  `location` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'qiniu',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文件素材库' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_media_file_4
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_file_4`;
CREATE TABLE `__PREFIX__media_file_4`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT 0,
  `update_time` int(10) NOT NULL DEFAULT 0,
  `size` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '格式',
  `location` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'qiniu',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文件素材库' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_media_file_5
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_file_5`;
CREATE TABLE `__PREFIX__media_file_5`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT 0,
  `update_time` int(10) NOT NULL DEFAULT 0,
  `size` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '格式',
  `location` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'qiniu',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文件素材库' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_media_image_1
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_image_1`;
CREATE TABLE `__PREFIX__media_image_1`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT 0,
  `update_time` int(10) NOT NULL DEFAULT 0,
  `size` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` enum('bmp','jpg','jpeg','png','gif') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'jpg' COMMENT '图片格式',
  `location` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '位置Local，Qiniu',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '图片素材库' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_media_image_2
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_image_2`;
CREATE TABLE `__PREFIX__media_image_2`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT 0,
  `update_time` int(10) NOT NULL DEFAULT 0,
  `size` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` enum('bmp','jpg','jpeg','png','gif') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'jpg' COMMENT '图片格式',
  `location` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '位置Local，Qiniu',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '图片素材库' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_media_image_3
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_image_3`;
CREATE TABLE `__PREFIX__media_image_3`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT 0,
  `update_time` int(10) NOT NULL DEFAULT 0,
  `size` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` enum('bmp','jpg','jpeg','png','gif') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'jpg' COMMENT '图片格式',
  `location` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '位置Local，Qiniu',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '图片素材库' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_media_image_4
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_image_4`;
CREATE TABLE `__PREFIX__media_image_4`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT 0,
  `update_time` int(10) NOT NULL DEFAULT 0,
  `size` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` enum('bmp','jpg','jpeg','png','gif') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'jpg' COMMENT '图片格式',
  `location` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '位置Local，Qiniu',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '图片素材库' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_media_image_5
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_image_5`;
CREATE TABLE `__PREFIX__media_image_5`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT 0,
  `update_time` int(10) NOT NULL DEFAULT 0,
  `size` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` enum('bmp','jpg','jpeg','png','gif') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'jpg' COMMENT '图片格式',
  `location` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '位置Local，Qiniu',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '图片素材库' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_media_link_1
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_link_1`;
CREATE TABLE `__PREFIX__media_link_1`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片url',
  `image_url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `create_time` int(10) NOT NULL DEFAULT 0,
  `update_time` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE,
  INDEX `title`(`title`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '分享链接素材库' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_media_link_2
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_link_2`;
CREATE TABLE `__PREFIX__media_link_2`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片url',
  `image_url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `create_time` int(10) NOT NULL DEFAULT 0,
  `update_time` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE,
  INDEX `title`(`title`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '分享链接素材库' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_media_link_3
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_link_3`;
CREATE TABLE `__PREFIX__media_link_3`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片url',
  `image_url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `create_time` int(10) NOT NULL DEFAULT 0,
  `update_time` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE,
  INDEX `title`(`title`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '分享链接素材库' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_media_link_4
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_link_4`;
CREATE TABLE `__PREFIX__media_link_4`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片url',
  `image_url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `create_time` int(10) NOT NULL DEFAULT 0,
  `update_time` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE,
  INDEX `title`(`title`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '分享链接素材库' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_media_link_5
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_link_5`;
CREATE TABLE `__PREFIX__media_link_5`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片url',
  `image_url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `create_time` int(10) NOT NULL DEFAULT 0,
  `update_time` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE,
  INDEX `title`(`title`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '分享链接素材库' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_media_text_1
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_text_1`;
CREATE TABLE `__PREFIX__media_text_1`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文本内容',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`admin_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文本素材' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_media_text_2
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_text_2`;
CREATE TABLE `__PREFIX__media_text_2`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文本内容',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`admin_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文本素材' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_media_text_3
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_text_3`;
CREATE TABLE `__PREFIX__media_text_3`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文本内容',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`admin_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文本素材' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_media_text_4
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_text_4`;
CREATE TABLE `__PREFIX__media_text_4`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文本内容',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`admin_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文本素材' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_media_text_5
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_text_5`;
CREATE TABLE `__PREFIX__media_text_5`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文本内容',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`admin_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文本素材' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_media_video_1
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_video_1`;
CREATE TABLE `__PREFIX__media_video_1`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT 0,
  `update_time` int(10) NOT NULL DEFAULT 0,
  `size` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '格式',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '视频素材库' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_media_video_2
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_video_2`;
CREATE TABLE `__PREFIX__media_video_2`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT 0,
  `update_time` int(10) NOT NULL DEFAULT 0,
  `size` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '格式',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '视频素材库' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_media_video_3
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_video_3`;
CREATE TABLE `__PREFIX__media_video_3`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT 0,
  `update_time` int(10) NOT NULL DEFAULT 0,
  `size` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '格式',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '视频素材库' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_media_video_4
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_video_4`;
CREATE TABLE `__PREFIX__media_video_4`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT 0,
  `update_time` int(10) NOT NULL DEFAULT 0,
  `size` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '格式',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '视频素材库' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_media_video_5
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_video_5`;
CREATE TABLE `__PREFIX__media_video_5`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员id',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文本内容',
  `url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片url',
  `create_time` int(10) NOT NULL DEFAULT 0,
  `update_time` int(10) NOT NULL DEFAULT 0,
  `size` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小（用于判断上传的图片是否小于微信素材库的限制2M）',
  `ext` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '格式',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `admin_id`(`admin_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '视频素材库' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_moments
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__moments`;
CREATE TABLE `__PREFIX__moments`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL,
  `bot_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `media_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '回复类型',
  `media_id` int(10) NOT NULL DEFAULT 0 COMMENT '触发应用',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1正常 0禁用',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '配文',
  `publish_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '被动回复' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_reply
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__reply`;
CREATE TABLE `__PREFIX__reply`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL,
  `bot_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `event` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'beadded' COMMENT '事件/消息类型',
  `media_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '回复类型',
  `media_id` int(10) NOT NULL DEFAULT 0 COMMENT '触发应用',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1正常 0禁用',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `wxids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '作用对象，好友wxid或群wxid',
  `sort` int(6) UNSIGNED NOT NULL DEFAULT 0,
  `need_at` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否需要at',
  `handle_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'msg' COMMENT '响应类型：msg回复消息  rm移出群  del删除好友',
  `msg_type` smallint(6) UNSIGNED NOT NULL DEFAULT 1 COMMENT '消息类型',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `unique`(`event`, `bot_id`, `status`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '被动回复' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for ky_server
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__server`;
CREATE TABLE `__PREFIX__server`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0.0.0.0' COMMENT '接口地址',
  `app_key` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'appkey',
  `num` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '支持微信号数量',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '服务器' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_setting
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__setting`;
CREATE TABLE `__PREFIX__setting`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标识',
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '名称',
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '配置值',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '站点配置' ROW_FORMAT = Dynamic;

INSERT INTO `__PREFIX__setting` VALUES ('1', 'site', '站点信息', '{\"app_title\":\"WxBot\"}', '1590290640', '1649899288'), ('2', 'upload', '附件设置', '{\"driver\":\"local\",\"qiniu_ak\":\"\",\"qiniu_sk\":\"\",\"qiniu_bucket\":\"\",\"qiniu_domain\":\"\",\"image_size\":\"3148000\",\"image_ext\":\"jpg,gif,png,jpeg\",\"file_size\":\"53000000\",\"file_ext\":\"jpg,gif,png,jpeg,zip,rar,tar,gz,7z,doc,docx,txt,xml,mp3,mp4,xls,xlsx,pdf\",\"voice_size\":\"2048000\",\"voice_ext\":\"mp3,wma,wav,amr\",\"video_size\":\"50240000\",\"video_ext\":\"mp4,flv,mov\"}', '1590292316', '1646835370');

-- ----------------------------
-- Table structure for ky_task
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__task`;
CREATE TABLE `__PREFIX__task`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `bot_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '机器人id',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '任务名称',
  `wxids` longblob NULL COMMENT '接收者wxids，逗号隔开',
  `plan_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '计划发送时间',
  `complete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '任务结束时间',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `medias` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '回复内容',
  `circle` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1单次 2每天发 ',
  `plan_hour` char(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '00:00' COMMENT '具体的发送时段',
  `atall` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否艾特所有人',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = ' 消息群发表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_tj_group
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__tj_group`;
CREATE TABLE `__PREFIX__tj_group`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `day` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0000-00-00' COMMENT 'Y-m-d格式日期',
  `add_num` int(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT '访问量',
  `decr_num` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '老访客访问量',
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `bot_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `group_id_day`(`group_id`, `day`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '店铺日统计' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_whiteid
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__whiteid`;
CREATE TABLE `__PREFIX__whiteid`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `bot_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `group_wxid` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '群',
  `wxids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '白名单' ROW_FORMAT = Dynamic;

-- ----------------------------
-- 2.1.1
-- ----------------------------
ALTER TABLE `__PREFIX__moments` ADD COLUMN `plan_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT ' 计划发圈时间' AFTER `publish_time`;
ALTER TABLE `__PREFIX__moments` CHANGE COLUMN `media_id` `media_id` varchar(200) NOT NULL DEFAULT 0 COMMENT '触发应用';

-- ----------------------------
-- 2.1.2
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__moments_follow`;
CREATE TABLE `__PREFIX__moments_follow` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '机器人id',
  `wxids` text COLLATE utf8mb4_unicode_ci COMMENT '接收者wxids，逗号隔开',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `last_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后同步时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `admin_id` (`admin_id`,`bot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='跟圈设置';
-- ----------------------------
-- 2.1.3
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__member_tag`;
CREATE TABLE `__PREFIX__member_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'bot ID',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '标签',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `__PREFIX__bot_member` ADD COLUMN `tags` varchar(200) NOT NULL DEFAULT '' COMMENT '分组标签' AFTER `update_time`;
ALTER TABLE `__PREFIX__task` ADD COLUMN `member_tags` varchar(200) NOT NULL DEFAULT '' COMMENT '分组标签' AFTER `update_time`;
ALTER TABLE `__PREFIX__forward` ADD COLUMN `member_tags` varchar(200) NOT NULL DEFAULT '' COMMENT '分组标签' AFTER `update_time`;
-- ----------------------------
-- 2.1.4 手动更新请务必手动替换__PREFIX__为你的真实表前缀
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__media_group`;
CREATE TABLE `__PREFIX__media_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `title` (`title`,`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='素材分组';

ALTER TABLE `__PREFIX__media_file_1` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' AFTER `location`;
ALTER TABLE `__PREFIX__media_file_2` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' AFTER `location`;
ALTER TABLE `__PREFIX__media_file_3` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' AFTER `location`;
ALTER TABLE `__PREFIX__media_file_4` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' AFTER `location`;
ALTER TABLE `__PREFIX__media_file_5` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' AFTER `location`;

ALTER TABLE `__PREFIX__media_image_1` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' AFTER `location`;
ALTER TABLE `__PREFIX__media_image_2` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' AFTER `location`;
ALTER TABLE `__PREFIX__media_image_3` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' AFTER `location`;
ALTER TABLE `__PREFIX__media_image_4` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' AFTER `location`;
ALTER TABLE `__PREFIX__media_image_5` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' AFTER `location`;

ALTER TABLE `__PREFIX__media_link_1` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;
ALTER TABLE `__PREFIX__media_link_2` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;
ALTER TABLE `__PREFIX__media_link_3` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;
ALTER TABLE `__PREFIX__media_link_4` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;
ALTER TABLE `__PREFIX__media_link_5` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;

ALTER TABLE `__PREFIX__media_text_1` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;
ALTER TABLE `__PREFIX__media_text_2` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;
ALTER TABLE `__PREFIX__media_text_3` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;
ALTER TABLE `__PREFIX__media_text_4` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;
ALTER TABLE `__PREFIX__media_text_5` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;

ALTER TABLE `__PREFIX__media_video_1` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;
ALTER TABLE `__PREFIX__media_video_2` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;
ALTER TABLE `__PREFIX__media_video_3` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;
ALTER TABLE `__PREFIX__media_video_4` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;
ALTER TABLE `__PREFIX__media_video_5` ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分组id' ;
-- ----------------------------
-- 2.2.0 手动更新请务必手动替换__PREFIX__为你的真实表前缀
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__addon`;
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
-- ----------------------------
-- 2.2.3 手动更新请务必手动替换__PREFIX__为你的真实表前缀
-- ----------------------------
ALTER TABLE `__PREFIX__admin_group` ADD COLUMN `addons` text NULL COMMENT '角色应用' AFTER `tenant_group`;
-- ----------------------------
-- 2.2.4 手动更新请务必手动替换__PREFIX__为你的真实表前缀
-- ----------------------------
ALTER TABLE `__PREFIX__addon` ADD COLUMN `sort_reply` int(10) UNSIGNED NOT NULL DEFAULT 1 AFTER `cates`;
-- ----------------------------
-- 2.2.6 手动更新请务必手动替换__PREFIX__为你的真实表前缀
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__admin_log`;
CREATE TABLE `__PREFIX__admin_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '操作类型',
  `desc` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '操作描述',
  `admin_id` int(10) NOT NULL DEFAULT '0',
  `admin_username` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '管理员账号',
  `ip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '操作IP',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `year` int(10) unsigned NOT NULL DEFAULT '2023',
  PRIMARY KEY (`id`,`year`) USING BTREE,
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

PARTITION BY RANGE (`year`)
(PARTITION p2022 VALUES LESS THAN (2023) ENGINE = InnoDB,
 PARTITION p2023 VALUES LESS THAN (2024) ENGINE = InnoDB,
 PARTITION p2024 VALUES LESS THAN (2025) ENGINE = InnoDB,
 PARTITION p2025 VALUES LESS THAN (2026) ENGINE = InnoDB,
 PARTITION p2026 VALUES LESS THAN (2027) ENGINE = InnoDB,
 PARTITION p2027 VALUES LESS THAN (2028) ENGINE = InnoDB,
 PARTITION p2028 VALUES LESS THAN (2029) ENGINE = InnoDB,
 PARTITION p2029 VALUES LESS THAN (2030) ENGINE = InnoDB
 );

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
 PARTITION p2024 VALUES LESS THAN (2025) ENGINE = InnoDB,
 PARTITION p2025 VALUES LESS THAN (2026) ENGINE = InnoDB,
 PARTITION p2026 VALUES LESS THAN (2027) ENGINE = InnoDB,
 PARTITION p2027 VALUES LESS THAN (2028) ENGINE = InnoDB,
 PARTITION p2028 VALUES LESS THAN (2029) ENGINE = InnoDB,
 PARTITION p2029 VALUES LESS THAN (2030) ENGINE = InnoDB);

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

-- ----------------------------
-- 2.3.4
-- ----------------------------
CREATE TABLE `__PREFIX__msg_gather_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL,
  `bot_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'bot id',
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '规则名称',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='消息规则分组';
ALTER TABLE `__PREFIX__msg_log` ADD COLUMN `gather_id` int(10) COMMENT '规则ID' DEFAULT '0';
ALTER TABLE `__PREFIX__msg_gather` ADD COLUMN `title` varchar(100) NOT NULL DEFAULT '' COMMENT '规则名称' AFTER `bot_id`,ADD COLUMN `expire_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '过期时间' AFTER `to_media`,ADD COLUMN `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '规则分组' AFTER `expire_time`,ADD COLUMN `keyword` varchar(200) NOT NULL DEFAULT '' COMMENT '监控关键词' AFTER `group_id`;

-- ----------------------------
-- 2.3.9
-- ----------------------------

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