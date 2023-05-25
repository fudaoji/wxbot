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
  `rules` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `sort` tinyint(3) NOT NULL DEFAULT 0,
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `tenant_group` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否客户角色',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `title`(`title`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

INSERT INTO `__PREFIX__admin_group` VALUES ('1', '1','super_admin', '超级管理员', '1', '', '0', '0', '1445158837', '拥有系统最高管理权限', '1626581117', 0);


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
INSERT INTO `__PREFIX__admin_rule` VALUES (1, '', '系统管理', 0, '', '', '_self', 1, 0, 1626845872, 0, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (3, '', '员工管理', 194, '', '/admin/admin/index', '_self', 1, 0, 1676530002, 1, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (4, '', '菜单管理', 1, 'fa fa-align-justify', '/admin/adminrule/index', '_self', 1, 0, 1640017490, 3, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (6, '', '基础功能', 0, '', '', '_self', 1, 1599463278, 1649926644, 18, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (7, '', '推品助手', 149, 'fa fa-life-saver', '', '_self', 1, 1599463674, 1648437994, 10, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (69, '', '自动加人', 149, 'fa fa-address-card-o', '', '_self', 1, 1603766642, 1662473996, 15, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (79, '', '触发设置', 108, '', '/admin/yhqreply/index', '_self', 1, 1603767014, 1655878678, 5, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (105, '', '消息转播', 158, '', '/admin/forward/index', '_self', 1, 1604904482, 1655799164, 1, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (106, '', '被动回复', 158, '', '/admin/reply/index', '_self', 1, 1604904505, 1640364369, 5, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (107, '', '角色管理', 194, '', '/admin/admingroup/index', '_self', 1, 1604904540, 1676530036, 1, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (108, '', '优惠券助手', 149, 'fa fa-codepen', '', '_self', 1, 1604904704, 1655878454, 1, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (109, '', '优惠券管理', 108, '', '/admin/yhqcoupon/index', '_self', 1, 1604904735, 1655878586, 1, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (148, '', '配置管理', 1, 'fa fa-cogs', '/admin/setting/index', '_self', 1, 1624803694, 1640366685, 4, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (149, '', '应用中心', 0, '', '', '_self', 1, 1625712595, 1676518947, 15, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (150, '', '渠道管理', 7, '', '/admin/tpzschannel/index', '_self', 1, 1625712631, 1649233035, 5, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (151, '', '机器人列表', 162, '', '/admin/bot/index', '_self', 1, 1625725311, 1640018227, 5, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (152, '', '通讯录', 6, 'fa fa-address-book', '', '_self', 1, 1625737689, 1650419650, 20, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (153, '', '电商联盟', 7, '', '/admin/tpzsunion/index', '_self', 1, 1625737734, 1648523931, 1, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (155, '', '设置', 108, '', '/admin/yhqconfig/index', '_self', 1, 1626505323, 1655878696, 10, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (156, '', '群管理', 6, 'fa fa-object-group', '', '_self', 1, 1626505381, 1655732578, 10, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (157, '', '关键词回复', 158, '', '/admin/keyword/index', '_self', 1, 1626505434, 1650262233, 10, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (158, '', '快捷设置', 6, 'fa fa-cogs', '', '_self', 1, 1626505455, 1640365845, 40, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (159, '', '消息群发', 158, '', '/admin/task/index', '_self', 1, 1626505494, 1650459935, 1, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (160, '', '好友列表', 152, '', '/admin/botfriend/index', '_self', 1, 1626505753, 1646837048, 1, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (162, '', '机器人管理', 169, 'fa fa-android', '', '_self', 1, 1626507555, 1640844683, 50, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (166, '', '群组列表', 152, '', '/admin/botgroup/index', '_self', 1, 1626509815, 1646837078, 9, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (167, '', '群规则', 156, '', '/admin/rule/index', '_self', 1, 1626593222, 1650952528, 20, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (168, '', '素材库', 169, 'fa fa-book', '/admin/mediatext/index', '_self', 1, 1640786676, 1649837988, 1, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (169, '', '全局设置', 0, '', '', '_self', 1, 1640844673, 1650466761, 20, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (170, '', '基础设置', 7, '', '/admin/tpzsconfig/index', '_self', 1, 1646839737, 1648534905, 0, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (171, '', '采品群', 7, '', '/admin/tpzsgather/index', '_self', 1, 1646839758, 1648546385, 45, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (172, '', '机器人负责群', 7, '', '/admin/tpzsteam/index', '_self', 0, 1646839780, 1653879660, 40, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (173, '', '发送列表', 7, '', '/admin/tpzstask/index', '_self', 1, 1646839807, 1648439722, 50, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (174, '', '汉字助手', 149, 'fa fa-anchor', '', '_self', 1, 1653556679, 1653556679, 1, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (175, '', '设置', 174, '', '/admin/hanziconfig/index', '_self', 1, 1653556715, 1653556715, 1, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (176, '', '系统升级', 1, 'fa fa-cloud-upload', '/admin/upgrade/index', '_self', 1, 1657205545, 1657205566, 1, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (177, '', '智能AI', 149, 'fa fa-android', '', '_self', 1, 1659361639, 1659361639, 10, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (178, '', '设置', 177, '', '/admin/aiconfig/index', '_self', 1, 1659361684, 1659361684, 1, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (179, '', '线索管理', 69, '', '/admin/zdjrclue/index', '_self', 1, 1662474030, 1662474030, 10, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (180, '', '加人任务', 69, '', '/admin/zdjrrule/index', '_self', 1, 1662474109, 1662474109, 5, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (181, '', '频繁机器人', 69, '', '/admin/zdjrblock/index', '_self', 1, 1662559068, 1662559068, 5, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (182, '', '项目管理', 69, '', '/admin/zdjrproject/index', '_self', 1, 1662628174, 1662628174, 5, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (183, '', '机器人分组', 69, '', '/admin/zdjrprojectbot/index', '_self', 1, 1662859812, 1662859812, 1, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (184, '', '配置', 69, '', '/admin/zdjrconfig/index', '_self', 1, 1662882165, 1662882165, 0, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (185, '', '操作日志', 69, '', '/admin/zdjrlog/index', '_self', 1, 1662909223, 1665318730, 8, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (186, '', '朋友圈', 6, 'fa fa-chrome', '', '_self', 1, 1663308146, 1663308146, 5, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (187, '', '获取朋友圈', 186, '', '/admin/moments/pull', '_self', 1, 1663308205, 1663308336, 10, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (188, '', '发朋友圈', 186, '', '/admin/moments/index', '_self', 1, 1663308300, 1663308300, 8, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (192, '', '客户管理', 1, 'fa fa-users', '', '_self', 1, 1676518784, 1677032365, 10, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (193, '', '运营管理', 0, '', '', '_self', 1, 1676518921, 1676518921, 10, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (194, '', '组织管理', 193, 'fa fa-connectdevelop', '', '_self', 1, 1676529954, 1676529982, 1, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (195, '', '账号额度管理', 193, 'fa fa-cc', '', '_self', 1, 1677031565, 1677031565, 5, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (196, '', '账号申请', 195, '', '/admin/botapply/index', '_self', 1, 1677031582, 1677031600, 5, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (197, '', '客户列表', 192, '', '/admin/tenant/index', '_self', 1, 1677032354, 1677032354, 10, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (198, '', '账号额度申请', 192, '', '/admin/botapply/applylist', '_self', 1, 1677032418, 1677033819, 5, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (199, '', '服务器管理', 1, 'fa fa-server', '', '_self', 1, 1677144245, 1677144245, 5, 1);
INSERT INTO `__PREFIX__admin_rule` VALUES (200, '', '服务器列表', 199, '', '/admin/server/index', '_self', 1, 1677144271, 1677144271, 5, 1);

-- ----------------------------
-- Table structure for ky_ai_config
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__ai_config`;
CREATE TABLE `__PREFIX__ai_config`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `bot_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `key` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `bot_id`(`bot_id`, `key`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 58 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'openai配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_bot
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__bot`;
CREATE TABLE `__PREFIX__bot`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT '商户ID',
  `staff_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '员工ID',
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '账号',
  `app_key` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '密码',
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
) ENGINE = InnoDB AUTO_INCREMENT = 36 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '个微机器人' ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 2231 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '群成员' ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 7900 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '通讯录' ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 721 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '群聊-推广位关联表' ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '群规则设置' ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '推品助手配置表' ROW_FORMAT = Dynamic;

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
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `unique`(`keyword`, `bot_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 139 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '被动回复' ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文件素材库' ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文件素材库' ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文件素材库' ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文件素材库' ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '图片素材库' ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '图片素材库' ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '分享链接素材库' ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文本素材' ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 27 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文本素材' ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文本素材' ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文本素材' ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文本素材' ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '视频素材库' ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '被动回复' ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 120 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '被动回复' ROW_FORMAT = Compact;

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
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `ip`(`url`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '服务器' ROW_FORMAT = Dynamic;

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

INSERT INTO `__PREFIX__setting` VALUES ('1', 'site', '站点信息', '{\"company_title\":\"WxBot\"}', '1590290640', '1649899288'), ('2', 'upload', '附件设置', '{\"driver\":\"local\",\"qiniu_ak\":\"\",\"qiniu_sk\":\"\",\"qiniu_bucket\":\"\",\"qiniu_domain\":\"\",\"image_size\":\"3148000\",\"image_ext\":\"jpg,gif,png,jpeg\",\"file_size\":\"53000000\",\"file_ext\":\"jpg,gif,png,jpeg,zip,rar,tar,gz,7z,doc,docx,txt,xml,mp3,mp4,xls,xlsx,pdf\",\"voice_size\":\"2048000\",\"voice_ext\":\"mp3,wma,wav,amr\",\"video_size\":\"50240000\",\"video_ext\":\"mp4,flv,mov\"}', '1590292316', '1646835370');

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
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = ' 消息群发表' ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 232 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '店铺日统计' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_tpzs_channel
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__tpzs_channel`;
CREATE TABLE `__PREFIX__tpzs_channel`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `contact_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `contact_tel` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ckid` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创客的id',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '渠道' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_tpzs_config
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__tpzs_config`;
CREATE TABLE `__PREFIX__tpzs_config`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `bot_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `key` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '推品助手配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_tpzs_gather
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__tpzs_gather`;
CREATE TABLE `__PREFIX__tpzs_gather`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL,
  `bot_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'bot id',
  `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '群聊id',
  `officer` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '指挥官',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `wxids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '转发对象',
  `universal` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否需要转链',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '群聊-推广位关联表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_tpzs_grouppos
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__tpzs_grouppos`;
CREATE TABLE `__PREFIX__tpzs_grouppos`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `bot_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '群聊id',
  `position_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'league_position  id',
  `channel_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'channel id',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '群聊-推广位关联表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_tpzs_position
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__tpzs_position`;
CREATE TABLE `__PREFIX__tpzs_position`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `union_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `position_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `type` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1手动2拉取',
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `jd_type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '站点类型 1.网站推广位2.APP推广位3.导购媒体推广位4.聊天工具推广位',
  `siteid` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '站点ID，如网站ID/appID/snsID',
  `default` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '默认',
  `union_type` tinyint(1) UNSIGNED NOT NULL DEFAULT 3 COMMENT '1cps推广位；2cpc推广位；3私域推广位，上限5000个，不在联盟后台展示，无对应 PID；4联盟后台推广位，上限500个，会在推客联盟后台展示，自动生成对应PID，可用于内容平台推广',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `position_id`(`position_id`) USING BTREE,
  INDEX `league_id`(`union_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '推广位id' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_tpzs_task
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__tpzs_task`;
CREATE TABLE `__PREFIX__tpzs_task`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `bot_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '机器人id',
  `members` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '接收者wxids，逗号隔开',
  `sku_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品id',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '文本内容',
  `img` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图片',
  `plan_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '计划发送时间',
  `complete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '任务结束时间',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'basic' COMMENT '商品来源：basic、ck、jd',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 457 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '推品助手任务表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_tpzs_team
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__tpzs_team`;
CREATE TABLE `__PREFIX__tpzs_team`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `bot_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '机器人id',
  `groups` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '群wxids',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '机器人负责群聊' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_tpzs_union
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__tpzs_union`;
CREATE TABLE `__PREFIX__tpzs_union`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `unionid` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '联盟id',
  `app_key` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '接口key',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `admin_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `default` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否默认',
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'jd' COMMENT 'jd,tb',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `unionid`(`unionid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '联盟账号信息' ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '白名单' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_yhq_code
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__yhq_code`;
CREATE TABLE `__PREFIX__yhq_code`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `coupon_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `code_url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '链接',
  `wxid` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '领取人',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `create_time` int(10) UNSIGNED NOT NULL,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `send_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5002 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '券码' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_yhq_config
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__yhq_config`;
CREATE TABLE `__PREFIX__yhq_config`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `bot_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `key` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `bot_id`(`bot_id`, `key`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '推品助手配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_yhq_coupon
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__yhq_coupon`;
CREATE TABLE `__PREFIX__yhq_coupon`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `bot_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `create_time` int(10) UNSIGNED NOT NULL,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '优惠券' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ky_yhq_reply
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__yhq_reply`;
CREATE TABLE `__PREFIX__yhq_reply`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL,
  `bot_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `event` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'beadded' COMMENT '事件/消息类型',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '回复类型',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1正常 0禁用',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `wxids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '作用对象，好友wxid或群wxid',
  `sort` int(6) UNSIGNED NOT NULL DEFAULT 0,
  `coupon_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `unique`(`event`, `bot_id`, `status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '被动回复' ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '请求频繁机器人名单' ROW_FORMAT = Dynamic;

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
  `wxid` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `project_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `headimgurl` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `admin_id`(`admin_id`, `content`, `type`) USING BTREE,
  INDEX `todo`(`admin_id`, `step`, `status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 519 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '线索表' ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '配置表' ROW_FORMAT = Dynamic;

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
  `type` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1搜索  2添加',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 41 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '线索表' ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '项目表' ROW_FORMAT = Dynamic;

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '项目-机器人关联表' ROW_FORMAT = Dynamic;

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
-- 2.1.1
-- ----------------------------
ALTER TABLE `__PREFIX__moments` ADD COLUMN `plan_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT ' 计划发圈时间' AFTER `publish_time`;
ALTER TABLE `__PREFIX__moments` CHANGE COLUMN `media_id` `media_id` varchar(200) NOT NULL DEFAULT 0 COMMENT '触发应用';

-- ----------------------------
-- 2.1.2
-- ----------------------------
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
CREATE TABLE `__PREFIX__media_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
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
CREATE TABLE `__PREFIX__admin_addon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `company_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商户id',
  `app_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '应用标识',
  `deadline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '到期时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`,`app_name`,`deadline`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='客户-应用关联表';