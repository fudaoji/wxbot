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
-- Table structure for ky_district
-- ----------------------------
DROP TABLE IF EXISTS `__PREFIX__district`;
CREATE TABLE `__PREFIX__district` (
  `id` int(10) unsigned NOT NULL,
  `title` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级id',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `id` (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='省市地区字典';

-- ----------------------------
-- Records of ky_district
-- ----------------------------
INSERT INTO `__PREFIX__district` VALUES ('110000', '北京', '0');
INSERT INTO `__PREFIX__district` VALUES ('110100', '北京市', '110000');
INSERT INTO `__PREFIX__district` VALUES ('110101', '东城区', '110100');
INSERT INTO `__PREFIX__district` VALUES ('110102', '西城区', '110100');
INSERT INTO `__PREFIX__district` VALUES ('110105', '朝阳区', '110100');
INSERT INTO `__PREFIX__district` VALUES ('110106', '丰台区', '110100');
INSERT INTO `__PREFIX__district` VALUES ('110107', '石景山区', '110100');
INSERT INTO `__PREFIX__district` VALUES ('110108', '海淀区', '110100');
INSERT INTO `__PREFIX__district` VALUES ('110109', '门头沟区', '110100');
INSERT INTO `__PREFIX__district` VALUES ('110111', '房山区', '110100');
INSERT INTO `__PREFIX__district` VALUES ('110112', '通州区', '110100');
INSERT INTO `__PREFIX__district` VALUES ('110113', '顺义区', '110100');
INSERT INTO `__PREFIX__district` VALUES ('110114', '昌平区', '110100');
INSERT INTO `__PREFIX__district` VALUES ('110115', '大兴区', '110100');
INSERT INTO `__PREFIX__district` VALUES ('110116', '怀柔区', '110100');
INSERT INTO `__PREFIX__district` VALUES ('110117', '平谷区', '110100');
INSERT INTO `__PREFIX__district` VALUES ('110228', '密云县', '110100');
INSERT INTO `__PREFIX__district` VALUES ('110229', '延庆县', '110100');
INSERT INTO `__PREFIX__district` VALUES ('120000', '天津', '0');
INSERT INTO `__PREFIX__district` VALUES ('120100', '天津市', '120000');
INSERT INTO `__PREFIX__district` VALUES ('120101', '和平区', '120100');
INSERT INTO `__PREFIX__district` VALUES ('120102', '河东区', '120100');
INSERT INTO `__PREFIX__district` VALUES ('120103', '河西区', '120100');
INSERT INTO `__PREFIX__district` VALUES ('120104', '南开区', '120100');
INSERT INTO `__PREFIX__district` VALUES ('120105', '河北区', '120100');
INSERT INTO `__PREFIX__district` VALUES ('120106', '红桥区', '120100');
INSERT INTO `__PREFIX__district` VALUES ('120110', '东丽区', '120100');
INSERT INTO `__PREFIX__district` VALUES ('120111', '西青区', '120100');
INSERT INTO `__PREFIX__district` VALUES ('120112', '津南区', '120100');
INSERT INTO `__PREFIX__district` VALUES ('120113', '北辰区', '120100');
INSERT INTO `__PREFIX__district` VALUES ('120114', '武清区', '120100');
INSERT INTO `__PREFIX__district` VALUES ('120115', '宝坻区', '120100');
INSERT INTO `__PREFIX__district` VALUES ('120116', '滨海新区', '120100');
INSERT INTO `__PREFIX__district` VALUES ('120221', '宁河县', '120100');
INSERT INTO `__PREFIX__district` VALUES ('120223', '静海县', '120100');
INSERT INTO `__PREFIX__district` VALUES ('120225', '蓟县', '120100');
INSERT INTO `__PREFIX__district` VALUES ('130000', '河北省', '0');
INSERT INTO `__PREFIX__district` VALUES ('130100', '石家庄市', '130000');
INSERT INTO `__PREFIX__district` VALUES ('130101', '市辖区', '130100');
INSERT INTO `__PREFIX__district` VALUES ('130102', '长安区', '130100');
INSERT INTO `__PREFIX__district` VALUES ('130104', '桥西区', '130100');
INSERT INTO `__PREFIX__district` VALUES ('130105', '新华区', '130100');
INSERT INTO `__PREFIX__district` VALUES ('130107', '井陉矿区', '130100');
INSERT INTO `__PREFIX__district` VALUES ('130108', '裕华区', '130100');
INSERT INTO `__PREFIX__district` VALUES ('130109', '藁城区', '130100');
INSERT INTO `__PREFIX__district` VALUES ('130110', '鹿泉区', '130100');
INSERT INTO `__PREFIX__district` VALUES ('130111', '栾城区', '130100');
INSERT INTO `__PREFIX__district` VALUES ('130121', '井陉县', '130100');
INSERT INTO `__PREFIX__district` VALUES ('130123', '正定县', '130100');
INSERT INTO `__PREFIX__district` VALUES ('130125', '行唐县', '130100');
INSERT INTO `__PREFIX__district` VALUES ('130126', '灵寿县', '130100');
INSERT INTO `__PREFIX__district` VALUES ('130127', '高邑县', '130100');
INSERT INTO `__PREFIX__district` VALUES ('130128', '深泽县', '130100');
INSERT INTO `__PREFIX__district` VALUES ('130129', '赞皇县', '130100');
INSERT INTO `__PREFIX__district` VALUES ('130130', '无极县', '130100');
INSERT INTO `__PREFIX__district` VALUES ('130131', '平山县', '130100');
INSERT INTO `__PREFIX__district` VALUES ('130132', '元氏县', '130100');
INSERT INTO `__PREFIX__district` VALUES ('130133', '赵县', '130100');
INSERT INTO `__PREFIX__district` VALUES ('130181', '辛集市', '130100');
INSERT INTO `__PREFIX__district` VALUES ('130183', '晋州市', '130100');
INSERT INTO `__PREFIX__district` VALUES ('130184', '新乐市', '130100');
INSERT INTO `__PREFIX__district` VALUES ('130200', '唐山市', '130000');
INSERT INTO `__PREFIX__district` VALUES ('130201', '市辖区', '130200');
INSERT INTO `__PREFIX__district` VALUES ('130202', '路南区', '130200');
INSERT INTO `__PREFIX__district` VALUES ('130203', '路北区', '130200');
INSERT INTO `__PREFIX__district` VALUES ('130204', '古冶区', '130200');
INSERT INTO `__PREFIX__district` VALUES ('130205', '开平区', '130200');
INSERT INTO `__PREFIX__district` VALUES ('130207', '丰南区', '130200');
INSERT INTO `__PREFIX__district` VALUES ('130208', '丰润区', '130200');
INSERT INTO `__PREFIX__district` VALUES ('130209', '曹妃甸区', '130200');
INSERT INTO `__PREFIX__district` VALUES ('130223', '滦县', '130200');
INSERT INTO `__PREFIX__district` VALUES ('130224', '滦南县', '130200');
INSERT INTO `__PREFIX__district` VALUES ('130225', '乐亭县', '130200');
INSERT INTO `__PREFIX__district` VALUES ('130227', '迁西县', '130200');
INSERT INTO `__PREFIX__district` VALUES ('130229', '玉田县', '130200');
INSERT INTO `__PREFIX__district` VALUES ('130281', '遵化市', '130200');
INSERT INTO `__PREFIX__district` VALUES ('130283', '迁安市', '130200');
INSERT INTO `__PREFIX__district` VALUES ('130300', '秦皇岛市', '130000');
INSERT INTO `__PREFIX__district` VALUES ('130301', '市辖区', '130300');
INSERT INTO `__PREFIX__district` VALUES ('130302', '海港区', '130300');
INSERT INTO `__PREFIX__district` VALUES ('130303', '山海关区', '130300');
INSERT INTO `__PREFIX__district` VALUES ('130304', '北戴河区', '130300');
INSERT INTO `__PREFIX__district` VALUES ('130321', '青龙满族自治县', '130300');
INSERT INTO `__PREFIX__district` VALUES ('130322', '昌黎县', '130300');
INSERT INTO `__PREFIX__district` VALUES ('130323', '抚宁县', '130300');
INSERT INTO `__PREFIX__district` VALUES ('130324', '卢龙县', '130300');
INSERT INTO `__PREFIX__district` VALUES ('130400', '邯郸市', '130000');
INSERT INTO `__PREFIX__district` VALUES ('130401', '市辖区', '130400');
INSERT INTO `__PREFIX__district` VALUES ('130402', '邯山区', '130400');
INSERT INTO `__PREFIX__district` VALUES ('130403', '丛台区', '130400');
INSERT INTO `__PREFIX__district` VALUES ('130404', '复兴区', '130400');
INSERT INTO `__PREFIX__district` VALUES ('130406', '峰峰矿区', '130400');
INSERT INTO `__PREFIX__district` VALUES ('130421', '邯郸县', '130400');
INSERT INTO `__PREFIX__district` VALUES ('130423', '临漳县', '130400');
INSERT INTO `__PREFIX__district` VALUES ('130424', '成安县', '130400');
INSERT INTO `__PREFIX__district` VALUES ('130425', '大名县', '130400');
INSERT INTO `__PREFIX__district` VALUES ('130426', '涉县', '130400');
INSERT INTO `__PREFIX__district` VALUES ('130427', '磁县', '130400');
INSERT INTO `__PREFIX__district` VALUES ('130428', '肥乡县', '130400');
INSERT INTO `__PREFIX__district` VALUES ('130429', '永年县', '130400');
INSERT INTO `__PREFIX__district` VALUES ('130430', '邱县', '130400');
INSERT INTO `__PREFIX__district` VALUES ('130431', '鸡泽县', '130400');
INSERT INTO `__PREFIX__district` VALUES ('130432', '广平县', '130400');
INSERT INTO `__PREFIX__district` VALUES ('130433', '馆陶县', '130400');
INSERT INTO `__PREFIX__district` VALUES ('130434', '魏县', '130400');
INSERT INTO `__PREFIX__district` VALUES ('130435', '曲周县', '130400');
INSERT INTO `__PREFIX__district` VALUES ('130481', '武安市', '130400');
INSERT INTO `__PREFIX__district` VALUES ('130500', '邢台市', '130000');
INSERT INTO `__PREFIX__district` VALUES ('130501', '市辖区', '130500');
INSERT INTO `__PREFIX__district` VALUES ('130502', '桥东区', '130500');
INSERT INTO `__PREFIX__district` VALUES ('130503', '桥西区', '130500');
INSERT INTO `__PREFIX__district` VALUES ('130521', '邢台县', '130500');
INSERT INTO `__PREFIX__district` VALUES ('130522', '临城县', '130500');
INSERT INTO `__PREFIX__district` VALUES ('130523', '内丘县', '130500');
INSERT INTO `__PREFIX__district` VALUES ('130524', '柏乡县', '130500');
INSERT INTO `__PREFIX__district` VALUES ('130525', '隆尧县', '130500');
INSERT INTO `__PREFIX__district` VALUES ('130526', '任县', '130500');
INSERT INTO `__PREFIX__district` VALUES ('130527', '南和县', '130500');
INSERT INTO `__PREFIX__district` VALUES ('130528', '宁晋县', '130500');
INSERT INTO `__PREFIX__district` VALUES ('130529', '巨鹿县', '130500');
INSERT INTO `__PREFIX__district` VALUES ('130530', '新河县', '130500');
INSERT INTO `__PREFIX__district` VALUES ('130531', '广宗县', '130500');
INSERT INTO `__PREFIX__district` VALUES ('130532', '平乡县', '130500');
INSERT INTO `__PREFIX__district` VALUES ('130533', '威县', '130500');
INSERT INTO `__PREFIX__district` VALUES ('130534', '清河县', '130500');
INSERT INTO `__PREFIX__district` VALUES ('130535', '临西县', '130500');
INSERT INTO `__PREFIX__district` VALUES ('130581', '南宫市', '130500');
INSERT INTO `__PREFIX__district` VALUES ('130582', '沙河市', '130500');
INSERT INTO `__PREFIX__district` VALUES ('130600', '保定市', '130000');
INSERT INTO `__PREFIX__district` VALUES ('130601', '市辖区', '130600');
INSERT INTO `__PREFIX__district` VALUES ('130602', '新市区', '130600');
INSERT INTO `__PREFIX__district` VALUES ('130603', '北市区', '130600');
INSERT INTO `__PREFIX__district` VALUES ('130604', '南市区', '130600');
INSERT INTO `__PREFIX__district` VALUES ('130621', '满城县', '130600');
INSERT INTO `__PREFIX__district` VALUES ('130622', '清苑县', '130600');
INSERT INTO `__PREFIX__district` VALUES ('130623', '涞水县', '130600');
INSERT INTO `__PREFIX__district` VALUES ('130624', '阜平县', '130600');
INSERT INTO `__PREFIX__district` VALUES ('130625', '徐水县', '130600');
INSERT INTO `__PREFIX__district` VALUES ('130626', '定兴县', '130600');
INSERT INTO `__PREFIX__district` VALUES ('130627', '唐县', '130600');
INSERT INTO `__PREFIX__district` VALUES ('130628', '高阳县', '130600');
INSERT INTO `__PREFIX__district` VALUES ('130629', '容城县', '130600');
INSERT INTO `__PREFIX__district` VALUES ('130630', '涞源县', '130600');
INSERT INTO `__PREFIX__district` VALUES ('130631', '望都县', '130600');
INSERT INTO `__PREFIX__district` VALUES ('130632', '安新县', '130600');
INSERT INTO `__PREFIX__district` VALUES ('130633', '易县', '130600');
INSERT INTO `__PREFIX__district` VALUES ('130634', '曲阳县', '130600');
INSERT INTO `__PREFIX__district` VALUES ('130635', '蠡县', '130600');
INSERT INTO `__PREFIX__district` VALUES ('130636', '顺平县', '130600');
INSERT INTO `__PREFIX__district` VALUES ('130637', '博野县', '130600');
INSERT INTO `__PREFIX__district` VALUES ('130638', '雄县', '130600');
INSERT INTO `__PREFIX__district` VALUES ('130681', '涿州市', '130600');
INSERT INTO `__PREFIX__district` VALUES ('130682', '定州市', '130600');
INSERT INTO `__PREFIX__district` VALUES ('130683', '安国市', '130600');
INSERT INTO `__PREFIX__district` VALUES ('130684', '高碑店市', '130600');
INSERT INTO `__PREFIX__district` VALUES ('130700', '张家口市', '130000');
INSERT INTO `__PREFIX__district` VALUES ('130701', '市辖区', '130700');
INSERT INTO `__PREFIX__district` VALUES ('130702', '桥东区', '130700');
INSERT INTO `__PREFIX__district` VALUES ('130703', '桥西区', '130700');
INSERT INTO `__PREFIX__district` VALUES ('130705', '宣化区', '130700');
INSERT INTO `__PREFIX__district` VALUES ('130706', '下花园区', '130700');
INSERT INTO `__PREFIX__district` VALUES ('130721', '宣化县', '130700');
INSERT INTO `__PREFIX__district` VALUES ('130722', '张北县', '130700');
INSERT INTO `__PREFIX__district` VALUES ('130723', '康保县', '130700');
INSERT INTO `__PREFIX__district` VALUES ('130724', '沽源县', '130700');
INSERT INTO `__PREFIX__district` VALUES ('130725', '尚义县', '130700');
INSERT INTO `__PREFIX__district` VALUES ('130726', '蔚县', '130700');
INSERT INTO `__PREFIX__district` VALUES ('130727', '阳原县', '130700');
INSERT INTO `__PREFIX__district` VALUES ('130728', '怀安县', '130700');
INSERT INTO `__PREFIX__district` VALUES ('130729', '万全县', '130700');
INSERT INTO `__PREFIX__district` VALUES ('130730', '怀来县', '130700');
INSERT INTO `__PREFIX__district` VALUES ('130731', '涿鹿县', '130700');
INSERT INTO `__PREFIX__district` VALUES ('130732', '赤城县', '130700');
INSERT INTO `__PREFIX__district` VALUES ('130733', '崇礼县', '130700');
INSERT INTO `__PREFIX__district` VALUES ('130800', '承德市', '130000');
INSERT INTO `__PREFIX__district` VALUES ('130801', '市辖区', '130800');
INSERT INTO `__PREFIX__district` VALUES ('130802', '双桥区', '130800');
INSERT INTO `__PREFIX__district` VALUES ('130803', '双滦区', '130800');
INSERT INTO `__PREFIX__district` VALUES ('130804', '鹰手营子矿区', '130800');
INSERT INTO `__PREFIX__district` VALUES ('130821', '承德县', '130800');
INSERT INTO `__PREFIX__district` VALUES ('130822', '兴隆县', '130800');
INSERT INTO `__PREFIX__district` VALUES ('130823', '平泉县', '130800');
INSERT INTO `__PREFIX__district` VALUES ('130824', '滦平县', '130800');
INSERT INTO `__PREFIX__district` VALUES ('130825', '隆化县', '130800');
INSERT INTO `__PREFIX__district` VALUES ('130826', '丰宁满族自治县', '130800');
INSERT INTO `__PREFIX__district` VALUES ('130827', '宽城满族自治县', '130800');
INSERT INTO `__PREFIX__district` VALUES ('130828', '围场满族蒙古族自治县', '130800');
INSERT INTO `__PREFIX__district` VALUES ('130900', '沧州市', '130000');
INSERT INTO `__PREFIX__district` VALUES ('130901', '市辖区', '130900');
INSERT INTO `__PREFIX__district` VALUES ('130902', '新华区', '130900');
INSERT INTO `__PREFIX__district` VALUES ('130903', '运河区', '130900');
INSERT INTO `__PREFIX__district` VALUES ('130921', '沧县', '130900');
INSERT INTO `__PREFIX__district` VALUES ('130922', '青县', '130900');
INSERT INTO `__PREFIX__district` VALUES ('130923', '东光县', '130900');
INSERT INTO `__PREFIX__district` VALUES ('130924', '海兴县', '130900');
INSERT INTO `__PREFIX__district` VALUES ('130925', '盐山县', '130900');
INSERT INTO `__PREFIX__district` VALUES ('130926', '肃宁县', '130900');
INSERT INTO `__PREFIX__district` VALUES ('130927', '南皮县', '130900');
INSERT INTO `__PREFIX__district` VALUES ('130928', '吴桥县', '130900');
INSERT INTO `__PREFIX__district` VALUES ('130929', '献县', '130900');
INSERT INTO `__PREFIX__district` VALUES ('130930', '孟村回族自治县', '130900');
INSERT INTO `__PREFIX__district` VALUES ('130981', '泊头市', '130900');
INSERT INTO `__PREFIX__district` VALUES ('130982', '任丘市', '130900');
INSERT INTO `__PREFIX__district` VALUES ('130983', '黄骅市', '130900');
INSERT INTO `__PREFIX__district` VALUES ('130984', '河间市', '130900');
INSERT INTO `__PREFIX__district` VALUES ('131000', '廊坊市', '130000');
INSERT INTO `__PREFIX__district` VALUES ('131001', '市辖区', '131000');
INSERT INTO `__PREFIX__district` VALUES ('131002', '安次区', '131000');
INSERT INTO `__PREFIX__district` VALUES ('131003', '广阳区', '131000');
INSERT INTO `__PREFIX__district` VALUES ('131022', '固安县', '131000');
INSERT INTO `__PREFIX__district` VALUES ('131023', '永清县', '131000');
INSERT INTO `__PREFIX__district` VALUES ('131024', '香河县', '131000');
INSERT INTO `__PREFIX__district` VALUES ('131025', '大城县', '131000');
INSERT INTO `__PREFIX__district` VALUES ('131026', '文安县', '131000');
INSERT INTO `__PREFIX__district` VALUES ('131028', '大厂回族自治县', '131000');
INSERT INTO `__PREFIX__district` VALUES ('131081', '霸州市', '131000');
INSERT INTO `__PREFIX__district` VALUES ('131082', '三河市', '131000');
INSERT INTO `__PREFIX__district` VALUES ('131100', '衡水市', '130000');
INSERT INTO `__PREFIX__district` VALUES ('131101', '市辖区', '131100');
INSERT INTO `__PREFIX__district` VALUES ('131102', '桃城区', '131100');
INSERT INTO `__PREFIX__district` VALUES ('131121', '枣强县', '131100');
INSERT INTO `__PREFIX__district` VALUES ('131122', '武邑县', '131100');
INSERT INTO `__PREFIX__district` VALUES ('131123', '武强县', '131100');
INSERT INTO `__PREFIX__district` VALUES ('131124', '饶阳县', '131100');
INSERT INTO `__PREFIX__district` VALUES ('131125', '安平县', '131100');
INSERT INTO `__PREFIX__district` VALUES ('131126', '故城县', '131100');
INSERT INTO `__PREFIX__district` VALUES ('131127', '景县', '131100');
INSERT INTO `__PREFIX__district` VALUES ('131128', '阜城县', '131100');
INSERT INTO `__PREFIX__district` VALUES ('131181', '冀州市', '131100');
INSERT INTO `__PREFIX__district` VALUES ('131182', '深州市', '131100');
INSERT INTO `__PREFIX__district` VALUES ('140000', '山西省', '0');
INSERT INTO `__PREFIX__district` VALUES ('140100', '太原市', '140000');
INSERT INTO `__PREFIX__district` VALUES ('140101', '市辖区', '140100');
INSERT INTO `__PREFIX__district` VALUES ('140105', '小店区', '140100');
INSERT INTO `__PREFIX__district` VALUES ('140106', '迎泽区', '140100');
INSERT INTO `__PREFIX__district` VALUES ('140107', '杏花岭区', '140100');
INSERT INTO `__PREFIX__district` VALUES ('140108', '尖草坪区', '140100');
INSERT INTO `__PREFIX__district` VALUES ('140109', '万柏林区', '140100');
INSERT INTO `__PREFIX__district` VALUES ('140110', '晋源区', '140100');
INSERT INTO `__PREFIX__district` VALUES ('140121', '清徐县', '140100');
INSERT INTO `__PREFIX__district` VALUES ('140122', '阳曲县', '140100');
INSERT INTO `__PREFIX__district` VALUES ('140123', '娄烦县', '140100');
INSERT INTO `__PREFIX__district` VALUES ('140181', '古交市', '140100');
INSERT INTO `__PREFIX__district` VALUES ('140200', '大同市', '140000');
INSERT INTO `__PREFIX__district` VALUES ('140201', '市辖区', '140200');
INSERT INTO `__PREFIX__district` VALUES ('140202', '城区', '140200');
INSERT INTO `__PREFIX__district` VALUES ('140203', '矿区', '140200');
INSERT INTO `__PREFIX__district` VALUES ('140211', '南郊区', '140200');
INSERT INTO `__PREFIX__district` VALUES ('140212', '新荣区', '140200');
INSERT INTO `__PREFIX__district` VALUES ('140221', '阳高县', '140200');
INSERT INTO `__PREFIX__district` VALUES ('140222', '天镇县', '140200');
INSERT INTO `__PREFIX__district` VALUES ('140223', '广灵县', '140200');
INSERT INTO `__PREFIX__district` VALUES ('140224', '灵丘县', '140200');
INSERT INTO `__PREFIX__district` VALUES ('140225', '浑源县', '140200');
INSERT INTO `__PREFIX__district` VALUES ('140226', '左云县', '140200');
INSERT INTO `__PREFIX__district` VALUES ('140227', '大同县', '140200');
INSERT INTO `__PREFIX__district` VALUES ('140300', '阳泉市', '140000');
INSERT INTO `__PREFIX__district` VALUES ('140301', '市辖区', '140300');
INSERT INTO `__PREFIX__district` VALUES ('140302', '城区', '140300');
INSERT INTO `__PREFIX__district` VALUES ('140303', '矿区', '140300');
INSERT INTO `__PREFIX__district` VALUES ('140311', '郊区', '140300');
INSERT INTO `__PREFIX__district` VALUES ('140321', '平定县', '140300');
INSERT INTO `__PREFIX__district` VALUES ('140322', '盂县', '140300');
INSERT INTO `__PREFIX__district` VALUES ('140400', '长治市', '140000');
INSERT INTO `__PREFIX__district` VALUES ('140401', '市辖区', '140400');
INSERT INTO `__PREFIX__district` VALUES ('140402', '城区', '140400');
INSERT INTO `__PREFIX__district` VALUES ('140411', '郊区', '140400');
INSERT INTO `__PREFIX__district` VALUES ('140421', '长治县', '140400');
INSERT INTO `__PREFIX__district` VALUES ('140423', '襄垣县', '140400');
INSERT INTO `__PREFIX__district` VALUES ('140424', '屯留县', '140400');
INSERT INTO `__PREFIX__district` VALUES ('140425', '平顺县', '140400');
INSERT INTO `__PREFIX__district` VALUES ('140426', '黎城县', '140400');
INSERT INTO `__PREFIX__district` VALUES ('140427', '壶关县', '140400');
INSERT INTO `__PREFIX__district` VALUES ('140428', '长子县', '140400');
INSERT INTO `__PREFIX__district` VALUES ('140429', '武乡县', '140400');
INSERT INTO `__PREFIX__district` VALUES ('140430', '沁县', '140400');
INSERT INTO `__PREFIX__district` VALUES ('140431', '沁源县', '140400');
INSERT INTO `__PREFIX__district` VALUES ('140481', '潞城市', '140400');
INSERT INTO `__PREFIX__district` VALUES ('140500', '晋城市', '140000');
INSERT INTO `__PREFIX__district` VALUES ('140501', '市辖区', '140500');
INSERT INTO `__PREFIX__district` VALUES ('140502', '城区', '140500');
INSERT INTO `__PREFIX__district` VALUES ('140521', '沁水县', '140500');
INSERT INTO `__PREFIX__district` VALUES ('140522', '阳城县', '140500');
INSERT INTO `__PREFIX__district` VALUES ('140524', '陵川县', '140500');
INSERT INTO `__PREFIX__district` VALUES ('140525', '泽州县', '140500');
INSERT INTO `__PREFIX__district` VALUES ('140581', '高平市', '140500');
INSERT INTO `__PREFIX__district` VALUES ('140600', '朔州市', '140000');
INSERT INTO `__PREFIX__district` VALUES ('140601', '市辖区', '140600');
INSERT INTO `__PREFIX__district` VALUES ('140602', '朔城区', '140600');
INSERT INTO `__PREFIX__district` VALUES ('140603', '平鲁区', '140600');
INSERT INTO `__PREFIX__district` VALUES ('140621', '山阴县', '140600');
INSERT INTO `__PREFIX__district` VALUES ('140622', '应县', '140600');
INSERT INTO `__PREFIX__district` VALUES ('140623', '右玉县', '140600');
INSERT INTO `__PREFIX__district` VALUES ('140624', '怀仁县', '140600');
INSERT INTO `__PREFIX__district` VALUES ('140700', '晋中市', '140000');
INSERT INTO `__PREFIX__district` VALUES ('140701', '市辖区', '140700');
INSERT INTO `__PREFIX__district` VALUES ('140702', '榆次区', '140700');
INSERT INTO `__PREFIX__district` VALUES ('140721', '榆社县', '140700');
INSERT INTO `__PREFIX__district` VALUES ('140722', '左权县', '140700');
INSERT INTO `__PREFIX__district` VALUES ('140723', '和顺县', '140700');
INSERT INTO `__PREFIX__district` VALUES ('140724', '昔阳县', '140700');
INSERT INTO `__PREFIX__district` VALUES ('140725', '寿阳县', '140700');
INSERT INTO `__PREFIX__district` VALUES ('140726', '太谷县', '140700');
INSERT INTO `__PREFIX__district` VALUES ('140727', '祁县', '140700');
INSERT INTO `__PREFIX__district` VALUES ('140728', '平遥县', '140700');
INSERT INTO `__PREFIX__district` VALUES ('140729', '灵石县', '140700');
INSERT INTO `__PREFIX__district` VALUES ('140781', '介休市', '140700');
INSERT INTO `__PREFIX__district` VALUES ('140800', '运城市', '140000');
INSERT INTO `__PREFIX__district` VALUES ('140801', '市辖区', '140800');
INSERT INTO `__PREFIX__district` VALUES ('140802', '盐湖区', '140800');
INSERT INTO `__PREFIX__district` VALUES ('140821', '临猗县', '140800');
INSERT INTO `__PREFIX__district` VALUES ('140822', '万荣县', '140800');
INSERT INTO `__PREFIX__district` VALUES ('140823', '闻喜县', '140800');
INSERT INTO `__PREFIX__district` VALUES ('140824', '稷山县', '140800');
INSERT INTO `__PREFIX__district` VALUES ('140825', '新绛县', '140800');
INSERT INTO `__PREFIX__district` VALUES ('140826', '绛县', '140800');
INSERT INTO `__PREFIX__district` VALUES ('140827', '垣曲县', '140800');
INSERT INTO `__PREFIX__district` VALUES ('140828', '夏县', '140800');
INSERT INTO `__PREFIX__district` VALUES ('140829', '平陆县', '140800');
INSERT INTO `__PREFIX__district` VALUES ('140830', '芮城县', '140800');
INSERT INTO `__PREFIX__district` VALUES ('140881', '永济市', '140800');
INSERT INTO `__PREFIX__district` VALUES ('140882', '河津市', '140800');
INSERT INTO `__PREFIX__district` VALUES ('140900', '忻州市', '140000');
INSERT INTO `__PREFIX__district` VALUES ('140901', '市辖区', '140900');
INSERT INTO `__PREFIX__district` VALUES ('140902', '忻府区', '140900');
INSERT INTO `__PREFIX__district` VALUES ('140921', '定襄县', '140900');
INSERT INTO `__PREFIX__district` VALUES ('140922', '五台县', '140900');
INSERT INTO `__PREFIX__district` VALUES ('140923', '代县', '140900');
INSERT INTO `__PREFIX__district` VALUES ('140924', '繁峙县', '140900');
INSERT INTO `__PREFIX__district` VALUES ('140925', '宁武县', '140900');
INSERT INTO `__PREFIX__district` VALUES ('140926', '静乐县', '140900');
INSERT INTO `__PREFIX__district` VALUES ('140927', '神池县', '140900');
INSERT INTO `__PREFIX__district` VALUES ('140928', '五寨县', '140900');
INSERT INTO `__PREFIX__district` VALUES ('140929', '岢岚县', '140900');
INSERT INTO `__PREFIX__district` VALUES ('140930', '河曲县', '140900');
INSERT INTO `__PREFIX__district` VALUES ('140931', '保德县', '140900');
INSERT INTO `__PREFIX__district` VALUES ('140932', '偏关县', '140900');
INSERT INTO `__PREFIX__district` VALUES ('140981', '原平市', '140900');
INSERT INTO `__PREFIX__district` VALUES ('141000', '临汾市', '140000');
INSERT INTO `__PREFIX__district` VALUES ('141001', '市辖区', '141000');
INSERT INTO `__PREFIX__district` VALUES ('141002', '尧都区', '141000');
INSERT INTO `__PREFIX__district` VALUES ('141021', '曲沃县', '141000');
INSERT INTO `__PREFIX__district` VALUES ('141022', '翼城县', '141000');
INSERT INTO `__PREFIX__district` VALUES ('141023', '襄汾县', '141000');
INSERT INTO `__PREFIX__district` VALUES ('141024', '洪洞县', '141000');
INSERT INTO `__PREFIX__district` VALUES ('141025', '古县', '141000');
INSERT INTO `__PREFIX__district` VALUES ('141026', '安泽县', '141000');
INSERT INTO `__PREFIX__district` VALUES ('141027', '浮山县', '141000');
INSERT INTO `__PREFIX__district` VALUES ('141028', '吉县', '141000');
INSERT INTO `__PREFIX__district` VALUES ('141029', '乡宁县', '141000');
INSERT INTO `__PREFIX__district` VALUES ('141030', '大宁县', '141000');
INSERT INTO `__PREFIX__district` VALUES ('141031', '隰县', '141000');
INSERT INTO `__PREFIX__district` VALUES ('141032', '永和县', '141000');
INSERT INTO `__PREFIX__district` VALUES ('141033', '蒲县', '141000');
INSERT INTO `__PREFIX__district` VALUES ('141034', '汾西县', '141000');
INSERT INTO `__PREFIX__district` VALUES ('141081', '侯马市', '141000');
INSERT INTO `__PREFIX__district` VALUES ('141082', '霍州市', '141000');
INSERT INTO `__PREFIX__district` VALUES ('141100', '吕梁市', '140000');
INSERT INTO `__PREFIX__district` VALUES ('141101', '市辖区', '141100');
INSERT INTO `__PREFIX__district` VALUES ('141102', '离石区', '141100');
INSERT INTO `__PREFIX__district` VALUES ('141121', '文水县', '141100');
INSERT INTO `__PREFIX__district` VALUES ('141122', '交城县', '141100');
INSERT INTO `__PREFIX__district` VALUES ('141123', '兴县', '141100');
INSERT INTO `__PREFIX__district` VALUES ('141124', '临县', '141100');
INSERT INTO `__PREFIX__district` VALUES ('141125', '柳林县', '141100');
INSERT INTO `__PREFIX__district` VALUES ('141126', '石楼县', '141100');
INSERT INTO `__PREFIX__district` VALUES ('141127', '岚县', '141100');
INSERT INTO `__PREFIX__district` VALUES ('141128', '方山县', '141100');
INSERT INTO `__PREFIX__district` VALUES ('141129', '中阳县', '141100');
INSERT INTO `__PREFIX__district` VALUES ('141130', '交口县', '141100');
INSERT INTO `__PREFIX__district` VALUES ('141181', '孝义市', '141100');
INSERT INTO `__PREFIX__district` VALUES ('141182', '汾阳市', '141100');
INSERT INTO `__PREFIX__district` VALUES ('150000', '内蒙古自治区', '0');
INSERT INTO `__PREFIX__district` VALUES ('150100', '呼和浩特市', '150000');
INSERT INTO `__PREFIX__district` VALUES ('150101', '市辖区', '150100');
INSERT INTO `__PREFIX__district` VALUES ('150102', '新城区', '150100');
INSERT INTO `__PREFIX__district` VALUES ('150103', '回民区', '150100');
INSERT INTO `__PREFIX__district` VALUES ('150104', '玉泉区', '150100');
INSERT INTO `__PREFIX__district` VALUES ('150105', '赛罕区', '150100');
INSERT INTO `__PREFIX__district` VALUES ('150121', '土默特左旗', '150100');
INSERT INTO `__PREFIX__district` VALUES ('150122', '托克托县', '150100');
INSERT INTO `__PREFIX__district` VALUES ('150123', '和林格尔县', '150100');
INSERT INTO `__PREFIX__district` VALUES ('150124', '清水河县', '150100');
INSERT INTO `__PREFIX__district` VALUES ('150125', '武川县', '150100');
INSERT INTO `__PREFIX__district` VALUES ('150200', '包头市', '150000');
INSERT INTO `__PREFIX__district` VALUES ('150201', '市辖区', '150200');
INSERT INTO `__PREFIX__district` VALUES ('150202', '东河区', '150200');
INSERT INTO `__PREFIX__district` VALUES ('150203', '昆都仑区', '150200');
INSERT INTO `__PREFIX__district` VALUES ('150204', '青山区', '150200');
INSERT INTO `__PREFIX__district` VALUES ('150205', '石拐区', '150200');
INSERT INTO `__PREFIX__district` VALUES ('150206', '白云鄂博矿区', '150200');
INSERT INTO `__PREFIX__district` VALUES ('150207', '九原区', '150200');
INSERT INTO `__PREFIX__district` VALUES ('150221', '土默特右旗', '150200');
INSERT INTO `__PREFIX__district` VALUES ('150222', '固阳县', '150200');
INSERT INTO `__PREFIX__district` VALUES ('150223', '达尔罕茂明安联合旗', '150200');
INSERT INTO `__PREFIX__district` VALUES ('150300', '乌海市', '150000');
INSERT INTO `__PREFIX__district` VALUES ('150301', '市辖区', '150300');
INSERT INTO `__PREFIX__district` VALUES ('150302', '海勃湾区', '150300');
INSERT INTO `__PREFIX__district` VALUES ('150303', '海南区', '150300');
INSERT INTO `__PREFIX__district` VALUES ('150304', '乌达区', '150300');
INSERT INTO `__PREFIX__district` VALUES ('150400', '赤峰市', '150000');
INSERT INTO `__PREFIX__district` VALUES ('150401', '市辖区', '150400');
INSERT INTO `__PREFIX__district` VALUES ('150402', '红山区', '150400');
INSERT INTO `__PREFIX__district` VALUES ('150403', '元宝山区', '150400');
INSERT INTO `__PREFIX__district` VALUES ('150404', '松山区', '150400');
INSERT INTO `__PREFIX__district` VALUES ('150421', '阿鲁科尔沁旗', '150400');
INSERT INTO `__PREFIX__district` VALUES ('150422', '巴林左旗', '150400');
INSERT INTO `__PREFIX__district` VALUES ('150423', '巴林右旗', '150400');
INSERT INTO `__PREFIX__district` VALUES ('150424', '林西县', '150400');
INSERT INTO `__PREFIX__district` VALUES ('150425', '克什克腾旗', '150400');
INSERT INTO `__PREFIX__district` VALUES ('150426', '翁牛特旗', '150400');
INSERT INTO `__PREFIX__district` VALUES ('150428', '喀喇沁旗', '150400');
INSERT INTO `__PREFIX__district` VALUES ('150429', '宁城县', '150400');
INSERT INTO `__PREFIX__district` VALUES ('150430', '敖汉旗', '150400');
INSERT INTO `__PREFIX__district` VALUES ('150500', '通辽市', '150000');
INSERT INTO `__PREFIX__district` VALUES ('150501', '市辖区', '150500');
INSERT INTO `__PREFIX__district` VALUES ('150502', '科尔沁区', '150500');
INSERT INTO `__PREFIX__district` VALUES ('150521', '科尔沁左翼中旗', '150500');
INSERT INTO `__PREFIX__district` VALUES ('150522', '科尔沁左翼后旗', '150500');
INSERT INTO `__PREFIX__district` VALUES ('150523', '开鲁县', '150500');
INSERT INTO `__PREFIX__district` VALUES ('150524', '库伦旗', '150500');
INSERT INTO `__PREFIX__district` VALUES ('150525', '奈曼旗', '150500');
INSERT INTO `__PREFIX__district` VALUES ('150526', '扎鲁特旗', '150500');
INSERT INTO `__PREFIX__district` VALUES ('150581', '霍林郭勒市', '150500');
INSERT INTO `__PREFIX__district` VALUES ('150600', '鄂尔多斯市', '150000');
INSERT INTO `__PREFIX__district` VALUES ('150601', '市辖区', '150600');
INSERT INTO `__PREFIX__district` VALUES ('150602', '东胜区', '150600');
INSERT INTO `__PREFIX__district` VALUES ('150621', '达拉特旗', '150600');
INSERT INTO `__PREFIX__district` VALUES ('150622', '准格尔旗', '150600');
INSERT INTO `__PREFIX__district` VALUES ('150623', '鄂托克前旗', '150600');
INSERT INTO `__PREFIX__district` VALUES ('150624', '鄂托克旗', '150600');
INSERT INTO `__PREFIX__district` VALUES ('150625', '杭锦旗', '150600');
INSERT INTO `__PREFIX__district` VALUES ('150626', '乌审旗', '150600');
INSERT INTO `__PREFIX__district` VALUES ('150627', '伊金霍洛旗', '150600');
INSERT INTO `__PREFIX__district` VALUES ('150700', '呼伦贝尔市', '150000');
INSERT INTO `__PREFIX__district` VALUES ('150701', '市辖区', '150700');
INSERT INTO `__PREFIX__district` VALUES ('150702', '海拉尔区', '150700');
INSERT INTO `__PREFIX__district` VALUES ('150703', '扎赉诺尔区', '150700');
INSERT INTO `__PREFIX__district` VALUES ('150721', '阿荣旗', '150700');
INSERT INTO `__PREFIX__district` VALUES ('150722', '莫力达瓦达斡尔族自治旗', '150700');
INSERT INTO `__PREFIX__district` VALUES ('150723', '鄂伦春自治旗', '150700');
INSERT INTO `__PREFIX__district` VALUES ('150724', '鄂温克族自治旗', '150700');
INSERT INTO `__PREFIX__district` VALUES ('150725', '陈巴尔虎旗', '150700');
INSERT INTO `__PREFIX__district` VALUES ('150726', '新巴尔虎左旗', '150700');
INSERT INTO `__PREFIX__district` VALUES ('150727', '新巴尔虎右旗', '150700');
INSERT INTO `__PREFIX__district` VALUES ('150781', '满洲里市', '150700');
INSERT INTO `__PREFIX__district` VALUES ('150782', '牙克石市', '150700');
INSERT INTO `__PREFIX__district` VALUES ('150783', '扎兰屯市', '150700');
INSERT INTO `__PREFIX__district` VALUES ('150784', '额尔古纳市', '150700');
INSERT INTO `__PREFIX__district` VALUES ('150785', '根河市', '150700');
INSERT INTO `__PREFIX__district` VALUES ('150800', '巴彦淖尔市', '150000');
INSERT INTO `__PREFIX__district` VALUES ('150801', '市辖区', '150800');
INSERT INTO `__PREFIX__district` VALUES ('150802', '临河区', '150800');
INSERT INTO `__PREFIX__district` VALUES ('150821', '五原县', '150800');
INSERT INTO `__PREFIX__district` VALUES ('150822', '磴口县', '150800');
INSERT INTO `__PREFIX__district` VALUES ('150823', '乌拉特前旗', '150800');
INSERT INTO `__PREFIX__district` VALUES ('150824', '乌拉特中旗', '150800');
INSERT INTO `__PREFIX__district` VALUES ('150825', '乌拉特后旗', '150800');
INSERT INTO `__PREFIX__district` VALUES ('150826', '杭锦后旗', '150800');
INSERT INTO `__PREFIX__district` VALUES ('150900', '乌兰察布市', '150000');
INSERT INTO `__PREFIX__district` VALUES ('150901', '市辖区', '150900');
INSERT INTO `__PREFIX__district` VALUES ('150902', '集宁区', '150900');
INSERT INTO `__PREFIX__district` VALUES ('150921', '卓资县', '150900');
INSERT INTO `__PREFIX__district` VALUES ('150922', '化德县', '150900');
INSERT INTO `__PREFIX__district` VALUES ('150923', '商都县', '150900');
INSERT INTO `__PREFIX__district` VALUES ('150924', '兴和县', '150900');
INSERT INTO `__PREFIX__district` VALUES ('150925', '凉城县', '150900');
INSERT INTO `__PREFIX__district` VALUES ('150926', '察哈尔右翼前旗', '150900');
INSERT INTO `__PREFIX__district` VALUES ('150927', '察哈尔右翼中旗', '150900');
INSERT INTO `__PREFIX__district` VALUES ('150928', '察哈尔右翼后旗', '150900');
INSERT INTO `__PREFIX__district` VALUES ('150929', '四子王旗', '150900');
INSERT INTO `__PREFIX__district` VALUES ('150981', '丰镇市', '150900');
INSERT INTO `__PREFIX__district` VALUES ('152200', '兴安盟', '150000');
INSERT INTO `__PREFIX__district` VALUES ('152201', '乌兰浩特市', '152200');
INSERT INTO `__PREFIX__district` VALUES ('152202', '阿尔山市', '152200');
INSERT INTO `__PREFIX__district` VALUES ('152221', '科尔沁右翼前旗', '152200');
INSERT INTO `__PREFIX__district` VALUES ('152222', '科尔沁右翼中旗', '152200');
INSERT INTO `__PREFIX__district` VALUES ('152223', '扎赉特旗', '152200');
INSERT INTO `__PREFIX__district` VALUES ('152224', '突泉县', '152200');
INSERT INTO `__PREFIX__district` VALUES ('152500', '锡林郭勒盟', '150000');
INSERT INTO `__PREFIX__district` VALUES ('152501', '二连浩特市', '152500');
INSERT INTO `__PREFIX__district` VALUES ('152502', '锡林浩特市', '152500');
INSERT INTO `__PREFIX__district` VALUES ('152522', '阿巴嘎旗', '152500');
INSERT INTO `__PREFIX__district` VALUES ('152523', '苏尼特左旗', '152500');
INSERT INTO `__PREFIX__district` VALUES ('152524', '苏尼特右旗', '152500');
INSERT INTO `__PREFIX__district` VALUES ('152525', '东乌珠穆沁旗', '152500');
INSERT INTO `__PREFIX__district` VALUES ('152526', '西乌珠穆沁旗', '152500');
INSERT INTO `__PREFIX__district` VALUES ('152527', '太仆寺旗', '152500');
INSERT INTO `__PREFIX__district` VALUES ('152528', '镶黄旗', '152500');
INSERT INTO `__PREFIX__district` VALUES ('152529', '正镶白旗', '152500');
INSERT INTO `__PREFIX__district` VALUES ('152530', '正蓝旗', '152500');
INSERT INTO `__PREFIX__district` VALUES ('152531', '多伦县', '152500');
INSERT INTO `__PREFIX__district` VALUES ('152900', '阿拉善盟', '150000');
INSERT INTO `__PREFIX__district` VALUES ('152921', '阿拉善左旗', '152900');
INSERT INTO `__PREFIX__district` VALUES ('152922', '阿拉善右旗', '152900');
INSERT INTO `__PREFIX__district` VALUES ('152923', '额济纳旗', '152900');
INSERT INTO `__PREFIX__district` VALUES ('210000', '辽宁省', '0');
INSERT INTO `__PREFIX__district` VALUES ('210100', '沈阳市', '210000');
INSERT INTO `__PREFIX__district` VALUES ('210101', '市辖区', '210100');
INSERT INTO `__PREFIX__district` VALUES ('210102', '和平区', '210100');
INSERT INTO `__PREFIX__district` VALUES ('210103', '沈河区', '210100');
INSERT INTO `__PREFIX__district` VALUES ('210104', '大东区', '210100');
INSERT INTO `__PREFIX__district` VALUES ('210105', '皇姑区', '210100');
INSERT INTO `__PREFIX__district` VALUES ('210106', '铁西区', '210100');
INSERT INTO `__PREFIX__district` VALUES ('210111', '苏家屯区', '210100');
INSERT INTO `__PREFIX__district` VALUES ('210112', '浑南区', '210100');
INSERT INTO `__PREFIX__district` VALUES ('210113', '沈北新区', '210100');
INSERT INTO `__PREFIX__district` VALUES ('210114', '于洪区', '210100');
INSERT INTO `__PREFIX__district` VALUES ('210122', '辽中县', '210100');
INSERT INTO `__PREFIX__district` VALUES ('210123', '康平县', '210100');
INSERT INTO `__PREFIX__district` VALUES ('210124', '法库县', '210100');
INSERT INTO `__PREFIX__district` VALUES ('210181', '新民市', '210100');
INSERT INTO `__PREFIX__district` VALUES ('210200', '大连市', '210000');
INSERT INTO `__PREFIX__district` VALUES ('210201', '市辖区', '210200');
INSERT INTO `__PREFIX__district` VALUES ('210202', '中山区', '210200');
INSERT INTO `__PREFIX__district` VALUES ('210203', '西岗区', '210200');
INSERT INTO `__PREFIX__district` VALUES ('210204', '沙河口区', '210200');
INSERT INTO `__PREFIX__district` VALUES ('210211', '甘井子区', '210200');
INSERT INTO `__PREFIX__district` VALUES ('210212', '旅顺口区', '210200');
INSERT INTO `__PREFIX__district` VALUES ('210213', '金州区', '210200');
INSERT INTO `__PREFIX__district` VALUES ('210224', '长海县', '210200');
INSERT INTO `__PREFIX__district` VALUES ('210281', '瓦房店市', '210200');
INSERT INTO `__PREFIX__district` VALUES ('210282', '普兰店市', '210200');
INSERT INTO `__PREFIX__district` VALUES ('210283', '庄河市', '210200');
INSERT INTO `__PREFIX__district` VALUES ('210300', '鞍山市', '210000');
INSERT INTO `__PREFIX__district` VALUES ('210301', '市辖区', '210300');
INSERT INTO `__PREFIX__district` VALUES ('210302', '铁东区', '210300');
INSERT INTO `__PREFIX__district` VALUES ('210303', '铁西区', '210300');
INSERT INTO `__PREFIX__district` VALUES ('210304', '立山区', '210300');
INSERT INTO `__PREFIX__district` VALUES ('210311', '千山区', '210300');
INSERT INTO `__PREFIX__district` VALUES ('210321', '台安县', '210300');
INSERT INTO `__PREFIX__district` VALUES ('210323', '岫岩满族自治县', '210300');
INSERT INTO `__PREFIX__district` VALUES ('210381', '海城市', '210300');
INSERT INTO `__PREFIX__district` VALUES ('210400', '抚顺市', '210000');
INSERT INTO `__PREFIX__district` VALUES ('210401', '市辖区', '210400');
INSERT INTO `__PREFIX__district` VALUES ('210402', '新抚区', '210400');
INSERT INTO `__PREFIX__district` VALUES ('210403', '东洲区', '210400');
INSERT INTO `__PREFIX__district` VALUES ('210404', '望花区', '210400');
INSERT INTO `__PREFIX__district` VALUES ('210411', '顺城区', '210400');
INSERT INTO `__PREFIX__district` VALUES ('210421', '抚顺县', '210400');
INSERT INTO `__PREFIX__district` VALUES ('210422', '新宾满族自治县', '210400');
INSERT INTO `__PREFIX__district` VALUES ('210423', '清原满族自治县', '210400');
INSERT INTO `__PREFIX__district` VALUES ('210500', '本溪市', '210000');
INSERT INTO `__PREFIX__district` VALUES ('210501', '市辖区', '210500');
INSERT INTO `__PREFIX__district` VALUES ('210502', '平山区', '210500');
INSERT INTO `__PREFIX__district` VALUES ('210503', '溪湖区', '210500');
INSERT INTO `__PREFIX__district` VALUES ('210504', '明山区', '210500');
INSERT INTO `__PREFIX__district` VALUES ('210505', '南芬区', '210500');
INSERT INTO `__PREFIX__district` VALUES ('210521', '本溪满族自治县', '210500');
INSERT INTO `__PREFIX__district` VALUES ('210522', '桓仁满族自治县', '210500');
INSERT INTO `__PREFIX__district` VALUES ('210600', '丹东市', '210000');
INSERT INTO `__PREFIX__district` VALUES ('210601', '市辖区', '210600');
INSERT INTO `__PREFIX__district` VALUES ('210602', '元宝区', '210600');
INSERT INTO `__PREFIX__district` VALUES ('210603', '振兴区', '210600');
INSERT INTO `__PREFIX__district` VALUES ('210604', '振安区', '210600');
INSERT INTO `__PREFIX__district` VALUES ('210624', '宽甸满族自治县', '210600');
INSERT INTO `__PREFIX__district` VALUES ('210681', '东港市', '210600');
INSERT INTO `__PREFIX__district` VALUES ('210682', '凤城市', '210600');
INSERT INTO `__PREFIX__district` VALUES ('210700', '锦州市', '210000');
INSERT INTO `__PREFIX__district` VALUES ('210701', '市辖区', '210700');
INSERT INTO `__PREFIX__district` VALUES ('210702', '古塔区', '210700');
INSERT INTO `__PREFIX__district` VALUES ('210703', '凌河区', '210700');
INSERT INTO `__PREFIX__district` VALUES ('210711', '太和区', '210700');
INSERT INTO `__PREFIX__district` VALUES ('210726', '黑山县', '210700');
INSERT INTO `__PREFIX__district` VALUES ('210727', '义县', '210700');
INSERT INTO `__PREFIX__district` VALUES ('210781', '凌海市', '210700');
INSERT INTO `__PREFIX__district` VALUES ('210782', '北镇市', '210700');
INSERT INTO `__PREFIX__district` VALUES ('210800', '营口市', '210000');
INSERT INTO `__PREFIX__district` VALUES ('210801', '市辖区', '210800');
INSERT INTO `__PREFIX__district` VALUES ('210802', '站前区', '210800');
INSERT INTO `__PREFIX__district` VALUES ('210803', '西市区', '210800');
INSERT INTO `__PREFIX__district` VALUES ('210804', '鲅鱼圈区', '210800');
INSERT INTO `__PREFIX__district` VALUES ('210811', '老边区', '210800');
INSERT INTO `__PREFIX__district` VALUES ('210881', '盖州市', '210800');
INSERT INTO `__PREFIX__district` VALUES ('210882', '大石桥市', '210800');
INSERT INTO `__PREFIX__district` VALUES ('210900', '阜新市', '210000');
INSERT INTO `__PREFIX__district` VALUES ('210901', '市辖区', '210900');
INSERT INTO `__PREFIX__district` VALUES ('210902', '海州区', '210900');
INSERT INTO `__PREFIX__district` VALUES ('210903', '新邱区', '210900');
INSERT INTO `__PREFIX__district` VALUES ('210904', '太平区', '210900');
INSERT INTO `__PREFIX__district` VALUES ('210905', '清河门区', '210900');
INSERT INTO `__PREFIX__district` VALUES ('210911', '细河区', '210900');
INSERT INTO `__PREFIX__district` VALUES ('210921', '阜新蒙古族自治县', '210900');
INSERT INTO `__PREFIX__district` VALUES ('210922', '彰武县', '210900');
INSERT INTO `__PREFIX__district` VALUES ('211000', '辽阳市', '210000');
INSERT INTO `__PREFIX__district` VALUES ('211001', '市辖区', '211000');
INSERT INTO `__PREFIX__district` VALUES ('211002', '白塔区', '211000');
INSERT INTO `__PREFIX__district` VALUES ('211003', '文圣区', '211000');
INSERT INTO `__PREFIX__district` VALUES ('211004', '宏伟区', '211000');
INSERT INTO `__PREFIX__district` VALUES ('211005', '弓长岭区', '211000');
INSERT INTO `__PREFIX__district` VALUES ('211011', '太子河区', '211000');
INSERT INTO `__PREFIX__district` VALUES ('211021', '辽阳县', '211000');
INSERT INTO `__PREFIX__district` VALUES ('211081', '灯塔市', '211000');
INSERT INTO `__PREFIX__district` VALUES ('211100', '盘锦市', '210000');
INSERT INTO `__PREFIX__district` VALUES ('211101', '市辖区', '211100');
INSERT INTO `__PREFIX__district` VALUES ('211102', '双台子区', '211100');
INSERT INTO `__PREFIX__district` VALUES ('211103', '兴隆台区', '211100');
INSERT INTO `__PREFIX__district` VALUES ('211121', '大洼县', '211100');
INSERT INTO `__PREFIX__district` VALUES ('211122', '盘山县', '211100');
INSERT INTO `__PREFIX__district` VALUES ('211200', '铁岭市', '210000');
INSERT INTO `__PREFIX__district` VALUES ('211201', '市辖区', '211200');
INSERT INTO `__PREFIX__district` VALUES ('211202', '银州区', '211200');
INSERT INTO `__PREFIX__district` VALUES ('211204', '清河区', '211200');
INSERT INTO `__PREFIX__district` VALUES ('211221', '铁岭县', '211200');
INSERT INTO `__PREFIX__district` VALUES ('211223', '西丰县', '211200');
INSERT INTO `__PREFIX__district` VALUES ('211224', '昌图县', '211200');
INSERT INTO `__PREFIX__district` VALUES ('211281', '调兵山市', '211200');
INSERT INTO `__PREFIX__district` VALUES ('211282', '开原市', '211200');
INSERT INTO `__PREFIX__district` VALUES ('211300', '朝阳市', '210000');
INSERT INTO `__PREFIX__district` VALUES ('211301', '市辖区', '211300');
INSERT INTO `__PREFIX__district` VALUES ('211302', '双塔区', '211300');
INSERT INTO `__PREFIX__district` VALUES ('211303', '龙城区', '211300');
INSERT INTO `__PREFIX__district` VALUES ('211321', '朝阳县', '211300');
INSERT INTO `__PREFIX__district` VALUES ('211322', '建平县', '211300');
INSERT INTO `__PREFIX__district` VALUES ('211324', '喀喇沁左翼蒙古族自治县', '211300');
INSERT INTO `__PREFIX__district` VALUES ('211381', '北票市', '211300');
INSERT INTO `__PREFIX__district` VALUES ('211382', '凌源市', '211300');
INSERT INTO `__PREFIX__district` VALUES ('211400', '葫芦岛市', '210000');
INSERT INTO `__PREFIX__district` VALUES ('211401', '市辖区', '211400');
INSERT INTO `__PREFIX__district` VALUES ('211402', '连山区', '211400');
INSERT INTO `__PREFIX__district` VALUES ('211403', '龙港区', '211400');
INSERT INTO `__PREFIX__district` VALUES ('211404', '南票区', '211400');
INSERT INTO `__PREFIX__district` VALUES ('211421', '绥中县', '211400');
INSERT INTO `__PREFIX__district` VALUES ('211422', '建昌县', '211400');
INSERT INTO `__PREFIX__district` VALUES ('211481', '兴城市', '211400');
INSERT INTO `__PREFIX__district` VALUES ('220000', '吉林省', '0');
INSERT INTO `__PREFIX__district` VALUES ('220100', '长春市', '220000');
INSERT INTO `__PREFIX__district` VALUES ('220101', '市辖区', '220100');
INSERT INTO `__PREFIX__district` VALUES ('220102', '南关区', '220100');
INSERT INTO `__PREFIX__district` VALUES ('220103', '宽城区', '220100');
INSERT INTO `__PREFIX__district` VALUES ('220104', '朝阳区', '220100');
INSERT INTO `__PREFIX__district` VALUES ('220105', '二道区', '220100');
INSERT INTO `__PREFIX__district` VALUES ('220106', '绿园区', '220100');
INSERT INTO `__PREFIX__district` VALUES ('220112', '双阳区', '220100');
INSERT INTO `__PREFIX__district` VALUES ('220113', '九台区', '220100');
INSERT INTO `__PREFIX__district` VALUES ('220122', '农安县', '220100');
INSERT INTO `__PREFIX__district` VALUES ('220182', '榆树市', '220100');
INSERT INTO `__PREFIX__district` VALUES ('220183', '德惠市', '220100');
INSERT INTO `__PREFIX__district` VALUES ('220200', '吉林市', '220000');
INSERT INTO `__PREFIX__district` VALUES ('220201', '市辖区', '220200');
INSERT INTO `__PREFIX__district` VALUES ('220202', '昌邑区', '220200');
INSERT INTO `__PREFIX__district` VALUES ('220203', '龙潭区', '220200');
INSERT INTO `__PREFIX__district` VALUES ('220204', '船营区', '220200');
INSERT INTO `__PREFIX__district` VALUES ('220211', '丰满区', '220200');
INSERT INTO `__PREFIX__district` VALUES ('220221', '永吉县', '220200');
INSERT INTO `__PREFIX__district` VALUES ('220281', '蛟河市', '220200');
INSERT INTO `__PREFIX__district` VALUES ('220282', '桦甸市', '220200');
INSERT INTO `__PREFIX__district` VALUES ('220283', '舒兰市', '220200');
INSERT INTO `__PREFIX__district` VALUES ('220284', '磐石市', '220200');
INSERT INTO `__PREFIX__district` VALUES ('220300', '四平市', '220000');
INSERT INTO `__PREFIX__district` VALUES ('220301', '市辖区', '220300');
INSERT INTO `__PREFIX__district` VALUES ('220302', '铁西区', '220300');
INSERT INTO `__PREFIX__district` VALUES ('220303', '铁东区', '220300');
INSERT INTO `__PREFIX__district` VALUES ('220322', '梨树县', '220300');
INSERT INTO `__PREFIX__district` VALUES ('220323', '伊通满族自治县', '220300');
INSERT INTO `__PREFIX__district` VALUES ('220381', '公主岭市', '220300');
INSERT INTO `__PREFIX__district` VALUES ('220382', '双辽市', '220300');
INSERT INTO `__PREFIX__district` VALUES ('220400', '辽源市', '220000');
INSERT INTO `__PREFIX__district` VALUES ('220401', '市辖区', '220400');
INSERT INTO `__PREFIX__district` VALUES ('220402', '龙山区', '220400');
INSERT INTO `__PREFIX__district` VALUES ('220403', '西安区', '220400');
INSERT INTO `__PREFIX__district` VALUES ('220421', '东丰县', '220400');
INSERT INTO `__PREFIX__district` VALUES ('220422', '东辽县', '220400');
INSERT INTO `__PREFIX__district` VALUES ('220500', '通化市', '220000');
INSERT INTO `__PREFIX__district` VALUES ('220501', '市辖区', '220500');
INSERT INTO `__PREFIX__district` VALUES ('220502', '东昌区', '220500');
INSERT INTO `__PREFIX__district` VALUES ('220503', '二道江区', '220500');
INSERT INTO `__PREFIX__district` VALUES ('220521', '通化县', '220500');
INSERT INTO `__PREFIX__district` VALUES ('220523', '辉南县', '220500');
INSERT INTO `__PREFIX__district` VALUES ('220524', '柳河县', '220500');
INSERT INTO `__PREFIX__district` VALUES ('220581', '梅河口市', '220500');
INSERT INTO `__PREFIX__district` VALUES ('220582', '集安市', '220500');
INSERT INTO `__PREFIX__district` VALUES ('220600', '白山市', '220000');
INSERT INTO `__PREFIX__district` VALUES ('220601', '市辖区', '220600');
INSERT INTO `__PREFIX__district` VALUES ('220602', '浑江区', '220600');
INSERT INTO `__PREFIX__district` VALUES ('220605', '江源区', '220600');
INSERT INTO `__PREFIX__district` VALUES ('220621', '抚松县', '220600');
INSERT INTO `__PREFIX__district` VALUES ('220622', '靖宇县', '220600');
INSERT INTO `__PREFIX__district` VALUES ('220623', '长白朝鲜族自治县', '220600');
INSERT INTO `__PREFIX__district` VALUES ('220681', '临江市', '220600');
INSERT INTO `__PREFIX__district` VALUES ('220700', '松原市', '220000');
INSERT INTO `__PREFIX__district` VALUES ('220701', '市辖区', '220700');
INSERT INTO `__PREFIX__district` VALUES ('220702', '宁江区', '220700');
INSERT INTO `__PREFIX__district` VALUES ('220721', '前郭尔罗斯蒙古族自治县', '220700');
INSERT INTO `__PREFIX__district` VALUES ('220722', '长岭县', '220700');
INSERT INTO `__PREFIX__district` VALUES ('220723', '乾安县', '220700');
INSERT INTO `__PREFIX__district` VALUES ('220781', '扶余市', '220700');
INSERT INTO `__PREFIX__district` VALUES ('220800', '白城市', '220000');
INSERT INTO `__PREFIX__district` VALUES ('220801', '市辖区', '220800');
INSERT INTO `__PREFIX__district` VALUES ('220802', '洮北区', '220800');
INSERT INTO `__PREFIX__district` VALUES ('220821', '镇赉县', '220800');
INSERT INTO `__PREFIX__district` VALUES ('220822', '通榆县', '220800');
INSERT INTO `__PREFIX__district` VALUES ('220881', '洮南市', '220800');
INSERT INTO `__PREFIX__district` VALUES ('220882', '大安市', '220800');
INSERT INTO `__PREFIX__district` VALUES ('222400', '延边朝鲜族自治州', '220000');
INSERT INTO `__PREFIX__district` VALUES ('222401', '延吉市', '222400');
INSERT INTO `__PREFIX__district` VALUES ('222402', '图们市', '222400');
INSERT INTO `__PREFIX__district` VALUES ('222403', '敦化市', '222400');
INSERT INTO `__PREFIX__district` VALUES ('222404', '珲春市', '222400');
INSERT INTO `__PREFIX__district` VALUES ('222405', '龙井市', '222400');
INSERT INTO `__PREFIX__district` VALUES ('222406', '和龙市', '222400');
INSERT INTO `__PREFIX__district` VALUES ('222424', '汪清县', '222400');
INSERT INTO `__PREFIX__district` VALUES ('222426', '安图县', '222400');
INSERT INTO `__PREFIX__district` VALUES ('230000', '黑龙江省', '0');
INSERT INTO `__PREFIX__district` VALUES ('230100', '哈尔滨市', '230000');
INSERT INTO `__PREFIX__district` VALUES ('230101', '市辖区', '230100');
INSERT INTO `__PREFIX__district` VALUES ('230102', '道里区', '230100');
INSERT INTO `__PREFIX__district` VALUES ('230103', '南岗区', '230100');
INSERT INTO `__PREFIX__district` VALUES ('230104', '道外区', '230100');
INSERT INTO `__PREFIX__district` VALUES ('230108', '平房区', '230100');
INSERT INTO `__PREFIX__district` VALUES ('230109', '松北区', '230100');
INSERT INTO `__PREFIX__district` VALUES ('230110', '香坊区', '230100');
INSERT INTO `__PREFIX__district` VALUES ('230111', '呼兰区', '230100');
INSERT INTO `__PREFIX__district` VALUES ('230112', '阿城区', '230100');
INSERT INTO `__PREFIX__district` VALUES ('230113', '双城区', '230100');
INSERT INTO `__PREFIX__district` VALUES ('230123', '依兰县', '230100');
INSERT INTO `__PREFIX__district` VALUES ('230124', '方正县', '230100');
INSERT INTO `__PREFIX__district` VALUES ('230125', '宾县', '230100');
INSERT INTO `__PREFIX__district` VALUES ('230126', '巴彦县', '230100');
INSERT INTO `__PREFIX__district` VALUES ('230127', '木兰县', '230100');
INSERT INTO `__PREFIX__district` VALUES ('230128', '通河县', '230100');
INSERT INTO `__PREFIX__district` VALUES ('230129', '延寿县', '230100');
INSERT INTO `__PREFIX__district` VALUES ('230183', '尚志市', '230100');
INSERT INTO `__PREFIX__district` VALUES ('230184', '五常市', '230100');
INSERT INTO `__PREFIX__district` VALUES ('230200', '齐齐哈尔市', '230000');
INSERT INTO `__PREFIX__district` VALUES ('230201', '市辖区', '230200');
INSERT INTO `__PREFIX__district` VALUES ('230202', '龙沙区', '230200');
INSERT INTO `__PREFIX__district` VALUES ('230203', '建华区', '230200');
INSERT INTO `__PREFIX__district` VALUES ('230204', '铁锋区', '230200');
INSERT INTO `__PREFIX__district` VALUES ('230205', '昂昂溪区', '230200');
INSERT INTO `__PREFIX__district` VALUES ('230206', '富拉尔基区', '230200');
INSERT INTO `__PREFIX__district` VALUES ('230207', '碾子山区', '230200');
INSERT INTO `__PREFIX__district` VALUES ('230208', '梅里斯达斡尔族区', '230200');
INSERT INTO `__PREFIX__district` VALUES ('230221', '龙江县', '230200');
INSERT INTO `__PREFIX__district` VALUES ('230223', '依安县', '230200');
INSERT INTO `__PREFIX__district` VALUES ('230224', '泰来县', '230200');
INSERT INTO `__PREFIX__district` VALUES ('230225', '甘南县', '230200');
INSERT INTO `__PREFIX__district` VALUES ('230227', '富裕县', '230200');
INSERT INTO `__PREFIX__district` VALUES ('230229', '克山县', '230200');
INSERT INTO `__PREFIX__district` VALUES ('230230', '克东县', '230200');
INSERT INTO `__PREFIX__district` VALUES ('230231', '拜泉县', '230200');
INSERT INTO `__PREFIX__district` VALUES ('230281', '讷河市', '230200');
INSERT INTO `__PREFIX__district` VALUES ('230300', '鸡西市', '230000');
INSERT INTO `__PREFIX__district` VALUES ('230301', '市辖区', '230300');
INSERT INTO `__PREFIX__district` VALUES ('230302', '鸡冠区', '230300');
INSERT INTO `__PREFIX__district` VALUES ('230303', '恒山区', '230300');
INSERT INTO `__PREFIX__district` VALUES ('230304', '滴道区', '230300');
INSERT INTO `__PREFIX__district` VALUES ('230305', '梨树区', '230300');
INSERT INTO `__PREFIX__district` VALUES ('230306', '城子河区', '230300');
INSERT INTO `__PREFIX__district` VALUES ('230307', '麻山区', '230300');
INSERT INTO `__PREFIX__district` VALUES ('230321', '鸡东县', '230300');
INSERT INTO `__PREFIX__district` VALUES ('230381', '虎林市', '230300');
INSERT INTO `__PREFIX__district` VALUES ('230382', '密山市', '230300');
INSERT INTO `__PREFIX__district` VALUES ('230400', '鹤岗市', '230000');
INSERT INTO `__PREFIX__district` VALUES ('230401', '市辖区', '230400');
INSERT INTO `__PREFIX__district` VALUES ('230402', '向阳区', '230400');
INSERT INTO `__PREFIX__district` VALUES ('230403', '工农区', '230400');
INSERT INTO `__PREFIX__district` VALUES ('230404', '南山区', '230400');
INSERT INTO `__PREFIX__district` VALUES ('230405', '兴安区', '230400');
INSERT INTO `__PREFIX__district` VALUES ('230406', '东山区', '230400');
INSERT INTO `__PREFIX__district` VALUES ('230407', '兴山区', '230400');
INSERT INTO `__PREFIX__district` VALUES ('230421', '萝北县', '230400');
INSERT INTO `__PREFIX__district` VALUES ('230422', '绥滨县', '230400');
INSERT INTO `__PREFIX__district` VALUES ('230500', '双鸭山市', '230000');
INSERT INTO `__PREFIX__district` VALUES ('230501', '市辖区', '230500');
INSERT INTO `__PREFIX__district` VALUES ('230502', '尖山区', '230500');
INSERT INTO `__PREFIX__district` VALUES ('230503', '岭东区', '230500');
INSERT INTO `__PREFIX__district` VALUES ('230505', '四方台区', '230500');
INSERT INTO `__PREFIX__district` VALUES ('230506', '宝山区', '230500');
INSERT INTO `__PREFIX__district` VALUES ('230521', '集贤县', '230500');
INSERT INTO `__PREFIX__district` VALUES ('230522', '友谊县', '230500');
INSERT INTO `__PREFIX__district` VALUES ('230523', '宝清县', '230500');
INSERT INTO `__PREFIX__district` VALUES ('230524', '饶河县', '230500');
INSERT INTO `__PREFIX__district` VALUES ('230600', '大庆市', '230000');
INSERT INTO `__PREFIX__district` VALUES ('230601', '市辖区', '230600');
INSERT INTO `__PREFIX__district` VALUES ('230602', '萨尔图区', '230600');
INSERT INTO `__PREFIX__district` VALUES ('230603', '龙凤区', '230600');
INSERT INTO `__PREFIX__district` VALUES ('230604', '让胡路区', '230600');
INSERT INTO `__PREFIX__district` VALUES ('230605', '红岗区', '230600');
INSERT INTO `__PREFIX__district` VALUES ('230606', '大同区', '230600');
INSERT INTO `__PREFIX__district` VALUES ('230621', '肇州县', '230600');
INSERT INTO `__PREFIX__district` VALUES ('230622', '肇源县', '230600');
INSERT INTO `__PREFIX__district` VALUES ('230623', '林甸县', '230600');
INSERT INTO `__PREFIX__district` VALUES ('230624', '杜尔伯特蒙古族自治县', '230600');
INSERT INTO `__PREFIX__district` VALUES ('230700', '伊春市', '230000');
INSERT INTO `__PREFIX__district` VALUES ('230701', '市辖区', '230700');
INSERT INTO `__PREFIX__district` VALUES ('230702', '伊春区', '230700');
INSERT INTO `__PREFIX__district` VALUES ('230703', '南岔区', '230700');
INSERT INTO `__PREFIX__district` VALUES ('230704', '友好区', '230700');
INSERT INTO `__PREFIX__district` VALUES ('230705', '西林区', '230700');
INSERT INTO `__PREFIX__district` VALUES ('230706', '翠峦区', '230700');
INSERT INTO `__PREFIX__district` VALUES ('230707', '新青区', '230700');
INSERT INTO `__PREFIX__district` VALUES ('230708', '美溪区', '230700');
INSERT INTO `__PREFIX__district` VALUES ('230709', '金山屯区', '230700');
INSERT INTO `__PREFIX__district` VALUES ('230710', '五营区', '230700');
INSERT INTO `__PREFIX__district` VALUES ('230711', '乌马河区', '230700');
INSERT INTO `__PREFIX__district` VALUES ('230712', '汤旺河区', '230700');
INSERT INTO `__PREFIX__district` VALUES ('230713', '带岭区', '230700');
INSERT INTO `__PREFIX__district` VALUES ('230714', '乌伊岭区', '230700');
INSERT INTO `__PREFIX__district` VALUES ('230715', '红星区', '230700');
INSERT INTO `__PREFIX__district` VALUES ('230716', '上甘岭区', '230700');
INSERT INTO `__PREFIX__district` VALUES ('230722', '嘉荫县', '230700');
INSERT INTO `__PREFIX__district` VALUES ('230781', '铁力市', '230700');
INSERT INTO `__PREFIX__district` VALUES ('230800', '佳木斯市', '230000');
INSERT INTO `__PREFIX__district` VALUES ('230801', '市辖区', '230800');
INSERT INTO `__PREFIX__district` VALUES ('230803', '向阳区', '230800');
INSERT INTO `__PREFIX__district` VALUES ('230804', '前进区', '230800');
INSERT INTO `__PREFIX__district` VALUES ('230805', '东风区', '230800');
INSERT INTO `__PREFIX__district` VALUES ('230811', '郊区', '230800');
INSERT INTO `__PREFIX__district` VALUES ('230822', '桦南县', '230800');
INSERT INTO `__PREFIX__district` VALUES ('230826', '桦川县', '230800');
INSERT INTO `__PREFIX__district` VALUES ('230828', '汤原县', '230800');
INSERT INTO `__PREFIX__district` VALUES ('230833', '抚远县', '230800');
INSERT INTO `__PREFIX__district` VALUES ('230881', '同江市', '230800');
INSERT INTO `__PREFIX__district` VALUES ('230882', '富锦市', '230800');
INSERT INTO `__PREFIX__district` VALUES ('230900', '七台河市', '230000');
INSERT INTO `__PREFIX__district` VALUES ('230901', '市辖区', '230900');
INSERT INTO `__PREFIX__district` VALUES ('230902', '新兴区', '230900');
INSERT INTO `__PREFIX__district` VALUES ('230903', '桃山区', '230900');
INSERT INTO `__PREFIX__district` VALUES ('230904', '茄子河区', '230900');
INSERT INTO `__PREFIX__district` VALUES ('230921', '勃利县', '230900');
INSERT INTO `__PREFIX__district` VALUES ('231000', '牡丹江市', '230000');
INSERT INTO `__PREFIX__district` VALUES ('231001', '市辖区', '231000');
INSERT INTO `__PREFIX__district` VALUES ('231002', '东安区', '231000');
INSERT INTO `__PREFIX__district` VALUES ('231003', '阳明区', '231000');
INSERT INTO `__PREFIX__district` VALUES ('231004', '爱民区', '231000');
INSERT INTO `__PREFIX__district` VALUES ('231005', '西安区', '231000');
INSERT INTO `__PREFIX__district` VALUES ('231024', '东宁县', '231000');
INSERT INTO `__PREFIX__district` VALUES ('231025', '林口县', '231000');
INSERT INTO `__PREFIX__district` VALUES ('231081', '绥芬河市', '231000');
INSERT INTO `__PREFIX__district` VALUES ('231083', '海林市', '231000');
INSERT INTO `__PREFIX__district` VALUES ('231084', '宁安市', '231000');
INSERT INTO `__PREFIX__district` VALUES ('231085', '穆棱市', '231000');
INSERT INTO `__PREFIX__district` VALUES ('231100', '黑河市', '230000');
INSERT INTO `__PREFIX__district` VALUES ('231101', '市辖区', '231100');
INSERT INTO `__PREFIX__district` VALUES ('231102', '爱辉区', '231100');
INSERT INTO `__PREFIX__district` VALUES ('231121', '嫩江县', '231100');
INSERT INTO `__PREFIX__district` VALUES ('231123', '逊克县', '231100');
INSERT INTO `__PREFIX__district` VALUES ('231124', '孙吴县', '231100');
INSERT INTO `__PREFIX__district` VALUES ('231181', '北安市', '231100');
INSERT INTO `__PREFIX__district` VALUES ('231182', '五大连池市', '231100');
INSERT INTO `__PREFIX__district` VALUES ('231200', '绥化市', '230000');
INSERT INTO `__PREFIX__district` VALUES ('231201', '市辖区', '231200');
INSERT INTO `__PREFIX__district` VALUES ('231202', '北林区', '231200');
INSERT INTO `__PREFIX__district` VALUES ('231221', '望奎县', '231200');
INSERT INTO `__PREFIX__district` VALUES ('231222', '兰西县', '231200');
INSERT INTO `__PREFIX__district` VALUES ('231223', '青冈县', '231200');
INSERT INTO `__PREFIX__district` VALUES ('231224', '庆安县', '231200');
INSERT INTO `__PREFIX__district` VALUES ('231225', '明水县', '231200');
INSERT INTO `__PREFIX__district` VALUES ('231226', '绥棱县', '231200');
INSERT INTO `__PREFIX__district` VALUES ('231281', '安达市', '231200');
INSERT INTO `__PREFIX__district` VALUES ('231282', '肇东市', '231200');
INSERT INTO `__PREFIX__district` VALUES ('231283', '海伦市', '231200');
INSERT INTO `__PREFIX__district` VALUES ('232700', '大兴安岭地区', '230000');
INSERT INTO `__PREFIX__district` VALUES ('232721', '呼玛县', '232700');
INSERT INTO `__PREFIX__district` VALUES ('232722', '塔河县', '232700');
INSERT INTO `__PREFIX__district` VALUES ('232723', '漠河县', '232700');
INSERT INTO `__PREFIX__district` VALUES ('310000', '上海', '0');
INSERT INTO `__PREFIX__district` VALUES ('310100', '上海市', '310000');
INSERT INTO `__PREFIX__district` VALUES ('310101', '黄浦区', '310100');
INSERT INTO `__PREFIX__district` VALUES ('310104', '徐汇区', '310100');
INSERT INTO `__PREFIX__district` VALUES ('310105', '长宁区', '310100');
INSERT INTO `__PREFIX__district` VALUES ('310106', '静安区', '310100');
INSERT INTO `__PREFIX__district` VALUES ('310107', '普陀区', '310100');
INSERT INTO `__PREFIX__district` VALUES ('310108', '闸北区', '310100');
INSERT INTO `__PREFIX__district` VALUES ('310109', '虹口区', '310100');
INSERT INTO `__PREFIX__district` VALUES ('310110', '杨浦区', '310100');
INSERT INTO `__PREFIX__district` VALUES ('310112', '闵行区', '310100');
INSERT INTO `__PREFIX__district` VALUES ('310113', '宝山区', '310100');
INSERT INTO `__PREFIX__district` VALUES ('310114', '嘉定区', '310100');
INSERT INTO `__PREFIX__district` VALUES ('310115', '浦东新区', '310100');
INSERT INTO `__PREFIX__district` VALUES ('310116', '金山区', '310100');
INSERT INTO `__PREFIX__district` VALUES ('310117', '松江区', '310100');
INSERT INTO `__PREFIX__district` VALUES ('310118', '青浦区', '310100');
INSERT INTO `__PREFIX__district` VALUES ('310120', '奉贤区', '310100');
INSERT INTO `__PREFIX__district` VALUES ('310230', '崇明县', '310100');
INSERT INTO `__PREFIX__district` VALUES ('320000', '江苏省', '0');
INSERT INTO `__PREFIX__district` VALUES ('320100', '南京市', '320000');
INSERT INTO `__PREFIX__district` VALUES ('320101', '市辖区', '320100');
INSERT INTO `__PREFIX__district` VALUES ('320102', '玄武区', '320100');
INSERT INTO `__PREFIX__district` VALUES ('320104', '秦淮区', '320100');
INSERT INTO `__PREFIX__district` VALUES ('320105', '建邺区', '320100');
INSERT INTO `__PREFIX__district` VALUES ('320106', '鼓楼区', '320100');
INSERT INTO `__PREFIX__district` VALUES ('320111', '浦口区', '320100');
INSERT INTO `__PREFIX__district` VALUES ('320113', '栖霞区', '320100');
INSERT INTO `__PREFIX__district` VALUES ('320114', '雨花台区', '320100');
INSERT INTO `__PREFIX__district` VALUES ('320115', '江宁区', '320100');
INSERT INTO `__PREFIX__district` VALUES ('320116', '六合区', '320100');
INSERT INTO `__PREFIX__district` VALUES ('320117', '溧水区', '320100');
INSERT INTO `__PREFIX__district` VALUES ('320118', '高淳区', '320100');
INSERT INTO `__PREFIX__district` VALUES ('320200', '无锡市', '320000');
INSERT INTO `__PREFIX__district` VALUES ('320201', '市辖区', '320200');
INSERT INTO `__PREFIX__district` VALUES ('320202', '崇安区', '320200');
INSERT INTO `__PREFIX__district` VALUES ('320203', '南长区', '320200');
INSERT INTO `__PREFIX__district` VALUES ('320204', '北塘区', '320200');
INSERT INTO `__PREFIX__district` VALUES ('320205', '锡山区', '320200');
INSERT INTO `__PREFIX__district` VALUES ('320206', '惠山区', '320200');
INSERT INTO `__PREFIX__district` VALUES ('320211', '滨湖区', '320200');
INSERT INTO `__PREFIX__district` VALUES ('320281', '江阴市', '320200');
INSERT INTO `__PREFIX__district` VALUES ('320282', '宜兴市', '320200');
INSERT INTO `__PREFIX__district` VALUES ('320300', '徐州市', '320000');
INSERT INTO `__PREFIX__district` VALUES ('320301', '市辖区', '320300');
INSERT INTO `__PREFIX__district` VALUES ('320302', '鼓楼区', '320300');
INSERT INTO `__PREFIX__district` VALUES ('320303', '云龙区', '320300');
INSERT INTO `__PREFIX__district` VALUES ('320305', '贾汪区', '320300');
INSERT INTO `__PREFIX__district` VALUES ('320311', '泉山区', '320300');
INSERT INTO `__PREFIX__district` VALUES ('320312', '铜山区', '320300');
INSERT INTO `__PREFIX__district` VALUES ('320321', '丰县', '320300');
INSERT INTO `__PREFIX__district` VALUES ('320322', '沛县', '320300');
INSERT INTO `__PREFIX__district` VALUES ('320324', '睢宁县', '320300');
INSERT INTO `__PREFIX__district` VALUES ('320381', '新沂市', '320300');
INSERT INTO `__PREFIX__district` VALUES ('320382', '邳州市', '320300');
INSERT INTO `__PREFIX__district` VALUES ('320400', '常州市', '320000');
INSERT INTO `__PREFIX__district` VALUES ('320401', '市辖区', '320400');
INSERT INTO `__PREFIX__district` VALUES ('320402', '天宁区', '320400');
INSERT INTO `__PREFIX__district` VALUES ('320404', '钟楼区', '320400');
INSERT INTO `__PREFIX__district` VALUES ('320405', '戚墅堰区', '320400');
INSERT INTO `__PREFIX__district` VALUES ('320411', '新北区', '320400');
INSERT INTO `__PREFIX__district` VALUES ('320412', '武进区', '320400');
INSERT INTO `__PREFIX__district` VALUES ('320481', '溧阳市', '320400');
INSERT INTO `__PREFIX__district` VALUES ('320482', '金坛市', '320400');
INSERT INTO `__PREFIX__district` VALUES ('320500', '苏州市', '320000');
INSERT INTO `__PREFIX__district` VALUES ('320501', '市辖区', '320500');
INSERT INTO `__PREFIX__district` VALUES ('320505', '虎丘区', '320500');
INSERT INTO `__PREFIX__district` VALUES ('320506', '吴中区', '320500');
INSERT INTO `__PREFIX__district` VALUES ('320507', '相城区', '320500');
INSERT INTO `__PREFIX__district` VALUES ('320508', '姑苏区', '320500');
INSERT INTO `__PREFIX__district` VALUES ('320509', '吴江区', '320500');
INSERT INTO `__PREFIX__district` VALUES ('320581', '常熟市', '320500');
INSERT INTO `__PREFIX__district` VALUES ('320582', '张家港市', '320500');
INSERT INTO `__PREFIX__district` VALUES ('320583', '昆山市', '320500');
INSERT INTO `__PREFIX__district` VALUES ('320585', '太仓市', '320500');
INSERT INTO `__PREFIX__district` VALUES ('320600', '南通市', '320000');
INSERT INTO `__PREFIX__district` VALUES ('320601', '市辖区', '320600');
INSERT INTO `__PREFIX__district` VALUES ('320602', '崇川区', '320600');
INSERT INTO `__PREFIX__district` VALUES ('320611', '港闸区', '320600');
INSERT INTO `__PREFIX__district` VALUES ('320612', '通州区', '320600');
INSERT INTO `__PREFIX__district` VALUES ('320621', '海安县', '320600');
INSERT INTO `__PREFIX__district` VALUES ('320623', '如东县', '320600');
INSERT INTO `__PREFIX__district` VALUES ('320671', '经济技术开发区', '320600');
INSERT INTO `__PREFIX__district` VALUES ('320681', '启东市', '320600');
INSERT INTO `__PREFIX__district` VALUES ('320682', '如皋市', '320600');
INSERT INTO `__PREFIX__district` VALUES ('320684', '海门市', '320600');
INSERT INTO `__PREFIX__district` VALUES ('320700', '连云港市', '320000');
INSERT INTO `__PREFIX__district` VALUES ('320701', '市辖区', '320700');
INSERT INTO `__PREFIX__district` VALUES ('320703', '连云区', '320700');
INSERT INTO `__PREFIX__district` VALUES ('320706', '海州区', '320700');
INSERT INTO `__PREFIX__district` VALUES ('320707', '赣榆区', '320700');
INSERT INTO `__PREFIX__district` VALUES ('320722', '东海县', '320700');
INSERT INTO `__PREFIX__district` VALUES ('320723', '灌云县', '320700');
INSERT INTO `__PREFIX__district` VALUES ('320724', '灌南县', '320700');
INSERT INTO `__PREFIX__district` VALUES ('320800', '淮安市', '320000');
INSERT INTO `__PREFIX__district` VALUES ('320801', '市辖区', '320800');
INSERT INTO `__PREFIX__district` VALUES ('320802', '清河区', '320800');
INSERT INTO `__PREFIX__district` VALUES ('320803', '淮安区', '320800');
INSERT INTO `__PREFIX__district` VALUES ('320804', '淮阴区', '320800');
INSERT INTO `__PREFIX__district` VALUES ('320811', '清浦区', '320800');
INSERT INTO `__PREFIX__district` VALUES ('320812', '清江浦区', '320800');
INSERT INTO `__PREFIX__district` VALUES ('320826', '涟水县', '320800');
INSERT INTO `__PREFIX__district` VALUES ('320829', '洪泽县', '320800');
INSERT INTO `__PREFIX__district` VALUES ('320830', '盱眙县', '320800');
INSERT INTO `__PREFIX__district` VALUES ('320831', '金湖县', '320800');
INSERT INTO `__PREFIX__district` VALUES ('320900', '盐城市', '320000');
INSERT INTO `__PREFIX__district` VALUES ('320901', '市辖区', '320900');
INSERT INTO `__PREFIX__district` VALUES ('320902', '亭湖区', '320900');
INSERT INTO `__PREFIX__district` VALUES ('320903', '盐都区', '320900');
INSERT INTO `__PREFIX__district` VALUES ('320921', '响水县', '320900');
INSERT INTO `__PREFIX__district` VALUES ('320922', '滨海县', '320900');
INSERT INTO `__PREFIX__district` VALUES ('320923', '阜宁县', '320900');
INSERT INTO `__PREFIX__district` VALUES ('320924', '射阳县', '320900');
INSERT INTO `__PREFIX__district` VALUES ('320925', '建湖县', '320900');
INSERT INTO `__PREFIX__district` VALUES ('320981', '东台市', '320900');
INSERT INTO `__PREFIX__district` VALUES ('320982', '大丰市', '320900');
INSERT INTO `__PREFIX__district` VALUES ('321000', '扬州市', '320000');
INSERT INTO `__PREFIX__district` VALUES ('321001', '市辖区', '321000');
INSERT INTO `__PREFIX__district` VALUES ('321002', '广陵区', '321000');
INSERT INTO `__PREFIX__district` VALUES ('321003', '邗江区', '321000');
INSERT INTO `__PREFIX__district` VALUES ('321012', '江都区', '321000');
INSERT INTO `__PREFIX__district` VALUES ('321023', '宝应县', '321000');
INSERT INTO `__PREFIX__district` VALUES ('321081', '仪征市', '321000');
INSERT INTO `__PREFIX__district` VALUES ('321084', '高邮市', '321000');
INSERT INTO `__PREFIX__district` VALUES ('321100', '镇江市', '320000');
INSERT INTO `__PREFIX__district` VALUES ('321101', '市辖区', '321100');
INSERT INTO `__PREFIX__district` VALUES ('321102', '京口区', '321100');
INSERT INTO `__PREFIX__district` VALUES ('321111', '润州区', '321100');
INSERT INTO `__PREFIX__district` VALUES ('321112', '丹徒区', '321100');
INSERT INTO `__PREFIX__district` VALUES ('321181', '丹阳市', '321100');
INSERT INTO `__PREFIX__district` VALUES ('321182', '扬中市', '321100');
INSERT INTO `__PREFIX__district` VALUES ('321183', '句容市', '321100');
INSERT INTO `__PREFIX__district` VALUES ('321200', '泰州市', '320000');
INSERT INTO `__PREFIX__district` VALUES ('321201', '市辖区', '321200');
INSERT INTO `__PREFIX__district` VALUES ('321202', '海陵区', '321200');
INSERT INTO `__PREFIX__district` VALUES ('321203', '高港区', '321200');
INSERT INTO `__PREFIX__district` VALUES ('321204', '姜堰区', '321200');
INSERT INTO `__PREFIX__district` VALUES ('321281', '兴化市', '321200');
INSERT INTO `__PREFIX__district` VALUES ('321282', '靖江市', '321200');
INSERT INTO `__PREFIX__district` VALUES ('321283', '泰兴市', '321200');
INSERT INTO `__PREFIX__district` VALUES ('321300', '宿迁市', '320000');
INSERT INTO `__PREFIX__district` VALUES ('321301', '市辖区', '321300');
INSERT INTO `__PREFIX__district` VALUES ('321302', '宿城区', '321300');
INSERT INTO `__PREFIX__district` VALUES ('321311', '宿豫区', '321300');
INSERT INTO `__PREFIX__district` VALUES ('321322', '沭阳县', '321300');
INSERT INTO `__PREFIX__district` VALUES ('321323', '泗阳县', '321300');
INSERT INTO `__PREFIX__district` VALUES ('321324', '泗洪县', '321300');
INSERT INTO `__PREFIX__district` VALUES ('330000', '浙江省', '0');
INSERT INTO `__PREFIX__district` VALUES ('330100', '杭州市', '330000');
INSERT INTO `__PREFIX__district` VALUES ('330101', '市辖区', '330100');
INSERT INTO `__PREFIX__district` VALUES ('330102', '上城区', '330100');
INSERT INTO `__PREFIX__district` VALUES ('330103', '下城区', '330100');
INSERT INTO `__PREFIX__district` VALUES ('330104', '江干区', '330100');
INSERT INTO `__PREFIX__district` VALUES ('330105', '拱墅区', '330100');
INSERT INTO `__PREFIX__district` VALUES ('330106', '西湖区', '330100');
INSERT INTO `__PREFIX__district` VALUES ('330108', '滨江区', '330100');
INSERT INTO `__PREFIX__district` VALUES ('330109', '萧山区', '330100');
INSERT INTO `__PREFIX__district` VALUES ('330110', '余杭区', '330100');
INSERT INTO `__PREFIX__district` VALUES ('330111', '富阳区', '330100');
INSERT INTO `__PREFIX__district` VALUES ('330122', '桐庐县', '330100');
INSERT INTO `__PREFIX__district` VALUES ('330127', '淳安县', '330100');
INSERT INTO `__PREFIX__district` VALUES ('330182', '建德市', '330100');
INSERT INTO `__PREFIX__district` VALUES ('330185', '临安市', '330100');
INSERT INTO `__PREFIX__district` VALUES ('330200', '宁波市', '330000');
INSERT INTO `__PREFIX__district` VALUES ('330201', '市辖区', '330200');
INSERT INTO `__PREFIX__district` VALUES ('330203', '海曙区', '330200');
INSERT INTO `__PREFIX__district` VALUES ('330204', '江东区', '330200');
INSERT INTO `__PREFIX__district` VALUES ('330205', '江北区', '330200');
INSERT INTO `__PREFIX__district` VALUES ('330206', '北仑区', '330200');
INSERT INTO `__PREFIX__district` VALUES ('330211', '镇海区', '330200');
INSERT INTO `__PREFIX__district` VALUES ('330212', '鄞州区', '330200');
INSERT INTO `__PREFIX__district` VALUES ('330225', '象山县', '330200');
INSERT INTO `__PREFIX__district` VALUES ('330226', '宁海县', '330200');
INSERT INTO `__PREFIX__district` VALUES ('330281', '余姚市', '330200');
INSERT INTO `__PREFIX__district` VALUES ('330282', '慈溪市', '330200');
INSERT INTO `__PREFIX__district` VALUES ('330283', '奉化市', '330200');
INSERT INTO `__PREFIX__district` VALUES ('330300', '温州市', '330000');
INSERT INTO `__PREFIX__district` VALUES ('330301', '市辖区', '330300');
INSERT INTO `__PREFIX__district` VALUES ('330302', '鹿城区', '330300');
INSERT INTO `__PREFIX__district` VALUES ('330303', '龙湾区', '330300');
INSERT INTO `__PREFIX__district` VALUES ('330304', '瓯海区', '330300');
INSERT INTO `__PREFIX__district` VALUES ('330322', '洞头县', '330300');
INSERT INTO `__PREFIX__district` VALUES ('330324', '永嘉县', '330300');
INSERT INTO `__PREFIX__district` VALUES ('330326', '平阳县', '330300');
INSERT INTO `__PREFIX__district` VALUES ('330327', '苍南县', '330300');
INSERT INTO `__PREFIX__district` VALUES ('330328', '文成县', '330300');
INSERT INTO `__PREFIX__district` VALUES ('330329', '泰顺县', '330300');
INSERT INTO `__PREFIX__district` VALUES ('330381', '瑞安市', '330300');
INSERT INTO `__PREFIX__district` VALUES ('330382', '乐清市', '330300');
INSERT INTO `__PREFIX__district` VALUES ('330400', '嘉兴市', '330000');
INSERT INTO `__PREFIX__district` VALUES ('330401', '市辖区', '330400');
INSERT INTO `__PREFIX__district` VALUES ('330402', '南湖区', '330400');
INSERT INTO `__PREFIX__district` VALUES ('330411', '秀洲区', '330400');
INSERT INTO `__PREFIX__district` VALUES ('330421', '嘉善县', '330400');
INSERT INTO `__PREFIX__district` VALUES ('330424', '海盐县', '330400');
INSERT INTO `__PREFIX__district` VALUES ('330481', '海宁市', '330400');
INSERT INTO `__PREFIX__district` VALUES ('330482', '平湖市', '330400');
INSERT INTO `__PREFIX__district` VALUES ('330483', '桐乡市', '330400');
INSERT INTO `__PREFIX__district` VALUES ('330500', '湖州市', '330000');
INSERT INTO `__PREFIX__district` VALUES ('330501', '市辖区', '330500');
INSERT INTO `__PREFIX__district` VALUES ('330502', '吴兴区', '330500');
INSERT INTO `__PREFIX__district` VALUES ('330503', '南浔区', '330500');
INSERT INTO `__PREFIX__district` VALUES ('330521', '德清县', '330500');
INSERT INTO `__PREFIX__district` VALUES ('330522', '长兴县', '330500');
INSERT INTO `__PREFIX__district` VALUES ('330523', '安吉县', '330500');
INSERT INTO `__PREFIX__district` VALUES ('330600', '绍兴市', '330000');
INSERT INTO `__PREFIX__district` VALUES ('330601', '市辖区', '330600');
INSERT INTO `__PREFIX__district` VALUES ('330602', '越城区', '330600');
INSERT INTO `__PREFIX__district` VALUES ('330603', '柯桥区', '330600');
INSERT INTO `__PREFIX__district` VALUES ('330604', '上虞区', '330600');
INSERT INTO `__PREFIX__district` VALUES ('330624', '新昌县', '330600');
INSERT INTO `__PREFIX__district` VALUES ('330681', '诸暨市', '330600');
INSERT INTO `__PREFIX__district` VALUES ('330683', '嵊州市', '330600');
INSERT INTO `__PREFIX__district` VALUES ('330700', '金华市', '330000');
INSERT INTO `__PREFIX__district` VALUES ('330701', '市辖区', '330700');
INSERT INTO `__PREFIX__district` VALUES ('330702', '婺城区', '330700');
INSERT INTO `__PREFIX__district` VALUES ('330703', '金东区', '330700');
INSERT INTO `__PREFIX__district` VALUES ('330723', '武义县', '330700');
INSERT INTO `__PREFIX__district` VALUES ('330726', '浦江县', '330700');
INSERT INTO `__PREFIX__district` VALUES ('330727', '磐安县', '330700');
INSERT INTO `__PREFIX__district` VALUES ('330781', '兰溪市', '330700');
INSERT INTO `__PREFIX__district` VALUES ('330782', '义乌市', '330700');
INSERT INTO `__PREFIX__district` VALUES ('330783', '东阳市', '330700');
INSERT INTO `__PREFIX__district` VALUES ('330784', '永康市', '330700');
INSERT INTO `__PREFIX__district` VALUES ('330800', '衢州市', '330000');
INSERT INTO `__PREFIX__district` VALUES ('330801', '市辖区', '330800');
INSERT INTO `__PREFIX__district` VALUES ('330802', '柯城区', '330800');
INSERT INTO `__PREFIX__district` VALUES ('330803', '衢江区', '330800');
INSERT INTO `__PREFIX__district` VALUES ('330822', '常山县', '330800');
INSERT INTO `__PREFIX__district` VALUES ('330824', '开化县', '330800');
INSERT INTO `__PREFIX__district` VALUES ('330825', '龙游县', '330800');
INSERT INTO `__PREFIX__district` VALUES ('330881', '江山市', '330800');
INSERT INTO `__PREFIX__district` VALUES ('330900', '舟山市', '330000');
INSERT INTO `__PREFIX__district` VALUES ('330901', '市辖区', '330900');
INSERT INTO `__PREFIX__district` VALUES ('330902', '定海区', '330900');
INSERT INTO `__PREFIX__district` VALUES ('330903', '普陀区', '330900');
INSERT INTO `__PREFIX__district` VALUES ('330921', '岱山县', '330900');
INSERT INTO `__PREFIX__district` VALUES ('330922', '嵊泗县', '330900');
INSERT INTO `__PREFIX__district` VALUES ('331000', '台州市', '330000');
INSERT INTO `__PREFIX__district` VALUES ('331001', '市辖区', '331000');
INSERT INTO `__PREFIX__district` VALUES ('331002', '椒江区', '331000');
INSERT INTO `__PREFIX__district` VALUES ('331003', '黄岩区', '331000');
INSERT INTO `__PREFIX__district` VALUES ('331004', '路桥区', '331000');
INSERT INTO `__PREFIX__district` VALUES ('331021', '玉环县', '331000');
INSERT INTO `__PREFIX__district` VALUES ('331022', '三门县', '331000');
INSERT INTO `__PREFIX__district` VALUES ('331023', '天台县', '331000');
INSERT INTO `__PREFIX__district` VALUES ('331024', '仙居县', '331000');
INSERT INTO `__PREFIX__district` VALUES ('331081', '温岭市', '331000');
INSERT INTO `__PREFIX__district` VALUES ('331082', '临海市', '331000');
INSERT INTO `__PREFIX__district` VALUES ('331100', '丽水市', '330000');
INSERT INTO `__PREFIX__district` VALUES ('331101', '市辖区', '331100');
INSERT INTO `__PREFIX__district` VALUES ('331102', '莲都区', '331100');
INSERT INTO `__PREFIX__district` VALUES ('331121', '青田县', '331100');
INSERT INTO `__PREFIX__district` VALUES ('331122', '缙云县', '331100');
INSERT INTO `__PREFIX__district` VALUES ('331123', '遂昌县', '331100');
INSERT INTO `__PREFIX__district` VALUES ('331124', '松阳县', '331100');
INSERT INTO `__PREFIX__district` VALUES ('331125', '云和县', '331100');
INSERT INTO `__PREFIX__district` VALUES ('331126', '庆元县', '331100');
INSERT INTO `__PREFIX__district` VALUES ('331127', '景宁畲族自治县', '331100');
INSERT INTO `__PREFIX__district` VALUES ('331181', '龙泉市', '331100');
INSERT INTO `__PREFIX__district` VALUES ('340000', '安徽省', '0');
INSERT INTO `__PREFIX__district` VALUES ('340100', '合肥市', '340000');
INSERT INTO `__PREFIX__district` VALUES ('340101', '市辖区', '340100');
INSERT INTO `__PREFIX__district` VALUES ('340102', '瑶海区', '340100');
INSERT INTO `__PREFIX__district` VALUES ('340103', '庐阳区', '340100');
INSERT INTO `__PREFIX__district` VALUES ('340104', '蜀山区', '340100');
INSERT INTO `__PREFIX__district` VALUES ('340111', '包河区', '340100');
INSERT INTO `__PREFIX__district` VALUES ('340121', '长丰县', '340100');
INSERT INTO `__PREFIX__district` VALUES ('340122', '肥东县', '340100');
INSERT INTO `__PREFIX__district` VALUES ('340123', '肥西县', '340100');
INSERT INTO `__PREFIX__district` VALUES ('340124', '庐江县', '340100');
INSERT INTO `__PREFIX__district` VALUES ('340181', '巢湖市', '340100');
INSERT INTO `__PREFIX__district` VALUES ('340200', '芜湖市', '340000');
INSERT INTO `__PREFIX__district` VALUES ('340201', '市辖区', '340200');
INSERT INTO `__PREFIX__district` VALUES ('340202', '镜湖区', '340200');
INSERT INTO `__PREFIX__district` VALUES ('340203', '弋江区', '340200');
INSERT INTO `__PREFIX__district` VALUES ('340207', '鸠江区', '340200');
INSERT INTO `__PREFIX__district` VALUES ('340208', '三山区', '340200');
INSERT INTO `__PREFIX__district` VALUES ('340221', '芜湖县', '340200');
INSERT INTO `__PREFIX__district` VALUES ('340222', '繁昌县', '340200');
INSERT INTO `__PREFIX__district` VALUES ('340223', '南陵县', '340200');
INSERT INTO `__PREFIX__district` VALUES ('340225', '无为县', '340200');
INSERT INTO `__PREFIX__district` VALUES ('340300', '蚌埠市', '340000');
INSERT INTO `__PREFIX__district` VALUES ('340301', '市辖区', '340300');
INSERT INTO `__PREFIX__district` VALUES ('340302', '龙子湖区', '340300');
INSERT INTO `__PREFIX__district` VALUES ('340303', '蚌山区', '340300');
INSERT INTO `__PREFIX__district` VALUES ('340304', '禹会区', '340300');
INSERT INTO `__PREFIX__district` VALUES ('340311', '淮上区', '340300');
INSERT INTO `__PREFIX__district` VALUES ('340321', '怀远县', '340300');
INSERT INTO `__PREFIX__district` VALUES ('340322', '五河县', '340300');
INSERT INTO `__PREFIX__district` VALUES ('340323', '固镇县', '340300');
INSERT INTO `__PREFIX__district` VALUES ('340400', '淮南市', '340000');
INSERT INTO `__PREFIX__district` VALUES ('340401', '市辖区', '340400');
INSERT INTO `__PREFIX__district` VALUES ('340402', '大通区', '340400');
INSERT INTO `__PREFIX__district` VALUES ('340403', '田家庵区', '340400');
INSERT INTO `__PREFIX__district` VALUES ('340404', '谢家集区', '340400');
INSERT INTO `__PREFIX__district` VALUES ('340405', '八公山区', '340400');
INSERT INTO `__PREFIX__district` VALUES ('340406', '潘集区', '340400');
INSERT INTO `__PREFIX__district` VALUES ('340421', '凤台县', '340400');
INSERT INTO `__PREFIX__district` VALUES ('340500', '马鞍山市', '340000');
INSERT INTO `__PREFIX__district` VALUES ('340501', '市辖区', '340500');
INSERT INTO `__PREFIX__district` VALUES ('340503', '花山区', '340500');
INSERT INTO `__PREFIX__district` VALUES ('340504', '雨山区', '340500');
INSERT INTO `__PREFIX__district` VALUES ('340506', '博望区', '340500');
INSERT INTO `__PREFIX__district` VALUES ('340521', '当涂县', '340500');
INSERT INTO `__PREFIX__district` VALUES ('340522', '含山县', '340500');
INSERT INTO `__PREFIX__district` VALUES ('340523', '和县', '340500');
INSERT INTO `__PREFIX__district` VALUES ('340600', '淮北市', '340000');
INSERT INTO `__PREFIX__district` VALUES ('340601', '市辖区', '340600');
INSERT INTO `__PREFIX__district` VALUES ('340602', '杜集区', '340600');
INSERT INTO `__PREFIX__district` VALUES ('340603', '相山区', '340600');
INSERT INTO `__PREFIX__district` VALUES ('340604', '烈山区', '340600');
INSERT INTO `__PREFIX__district` VALUES ('340621', '濉溪县', '340600');
INSERT INTO `__PREFIX__district` VALUES ('340700', '铜陵市', '340000');
INSERT INTO `__PREFIX__district` VALUES ('340701', '市辖区', '340700');
INSERT INTO `__PREFIX__district` VALUES ('340702', '铜官山区', '340700');
INSERT INTO `__PREFIX__district` VALUES ('340703', '狮子山区', '340700');
INSERT INTO `__PREFIX__district` VALUES ('340711', '郊区', '340700');
INSERT INTO `__PREFIX__district` VALUES ('340721', '铜陵县', '340700');
INSERT INTO `__PREFIX__district` VALUES ('340800', '安庆市', '340000');
INSERT INTO `__PREFIX__district` VALUES ('340801', '市辖区', '340800');
INSERT INTO `__PREFIX__district` VALUES ('340802', '迎江区', '340800');
INSERT INTO `__PREFIX__district` VALUES ('340803', '大观区', '340800');
INSERT INTO `__PREFIX__district` VALUES ('340811', '宜秀区', '340800');
INSERT INTO `__PREFIX__district` VALUES ('340822', '怀宁县', '340800');
INSERT INTO `__PREFIX__district` VALUES ('340823', '枞阳县', '340800');
INSERT INTO `__PREFIX__district` VALUES ('340824', '潜山县', '340800');
INSERT INTO `__PREFIX__district` VALUES ('340825', '太湖县', '340800');
INSERT INTO `__PREFIX__district` VALUES ('340826', '宿松县', '340800');
INSERT INTO `__PREFIX__district` VALUES ('340827', '望江县', '340800');
INSERT INTO `__PREFIX__district` VALUES ('340828', '岳西县', '340800');
INSERT INTO `__PREFIX__district` VALUES ('340881', '桐城市', '340800');
INSERT INTO `__PREFIX__district` VALUES ('341000', '黄山市', '340000');
INSERT INTO `__PREFIX__district` VALUES ('341001', '市辖区', '341000');
INSERT INTO `__PREFIX__district` VALUES ('341002', '屯溪区', '341000');
INSERT INTO `__PREFIX__district` VALUES ('341003', '黄山区', '341000');
INSERT INTO `__PREFIX__district` VALUES ('341004', '徽州区', '341000');
INSERT INTO `__PREFIX__district` VALUES ('341021', '歙县', '341000');
INSERT INTO `__PREFIX__district` VALUES ('341022', '休宁县', '341000');
INSERT INTO `__PREFIX__district` VALUES ('341023', '黟县', '341000');
INSERT INTO `__PREFIX__district` VALUES ('341024', '祁门县', '341000');
INSERT INTO `__PREFIX__district` VALUES ('341100', '滁州市', '340000');
INSERT INTO `__PREFIX__district` VALUES ('341101', '市辖区', '341100');
INSERT INTO `__PREFIX__district` VALUES ('341102', '琅琊区', '341100');
INSERT INTO `__PREFIX__district` VALUES ('341103', '南谯区', '341100');
INSERT INTO `__PREFIX__district` VALUES ('341122', '来安县', '341100');
INSERT INTO `__PREFIX__district` VALUES ('341124', '全椒县', '341100');
INSERT INTO `__PREFIX__district` VALUES ('341125', '定远县', '341100');
INSERT INTO `__PREFIX__district` VALUES ('341126', '凤阳县', '341100');
INSERT INTO `__PREFIX__district` VALUES ('341181', '天长市', '341100');
INSERT INTO `__PREFIX__district` VALUES ('341182', '明光市', '341100');
INSERT INTO `__PREFIX__district` VALUES ('341200', '阜阳市', '340000');
INSERT INTO `__PREFIX__district` VALUES ('341201', '市辖区', '341200');
INSERT INTO `__PREFIX__district` VALUES ('341202', '颍州区', '341200');
INSERT INTO `__PREFIX__district` VALUES ('341203', '颍东区', '341200');
INSERT INTO `__PREFIX__district` VALUES ('341204', '颍泉区', '341200');
INSERT INTO `__PREFIX__district` VALUES ('341221', '临泉县', '341200');
INSERT INTO `__PREFIX__district` VALUES ('341222', '太和县', '341200');
INSERT INTO `__PREFIX__district` VALUES ('341225', '阜南县', '341200');
INSERT INTO `__PREFIX__district` VALUES ('341226', '颍上县', '341200');
INSERT INTO `__PREFIX__district` VALUES ('341282', '界首市', '341200');
INSERT INTO `__PREFIX__district` VALUES ('341300', '宿州市', '340000');
INSERT INTO `__PREFIX__district` VALUES ('341301', '市辖区', '341300');
INSERT INTO `__PREFIX__district` VALUES ('341302', '埇桥区', '341300');
INSERT INTO `__PREFIX__district` VALUES ('341321', '砀山县', '341300');
INSERT INTO `__PREFIX__district` VALUES ('341322', '萧县', '341300');
INSERT INTO `__PREFIX__district` VALUES ('341323', '灵璧县', '341300');
INSERT INTO `__PREFIX__district` VALUES ('341324', '泗县', '341300');
INSERT INTO `__PREFIX__district` VALUES ('341500', '六安市', '340000');
INSERT INTO `__PREFIX__district` VALUES ('341501', '市辖区', '341500');
INSERT INTO `__PREFIX__district` VALUES ('341502', '金安区', '341500');
INSERT INTO `__PREFIX__district` VALUES ('341503', '裕安区', '341500');
INSERT INTO `__PREFIX__district` VALUES ('341521', '寿县', '341500');
INSERT INTO `__PREFIX__district` VALUES ('341522', '霍邱县', '341500');
INSERT INTO `__PREFIX__district` VALUES ('341523', '舒城县', '341500');
INSERT INTO `__PREFIX__district` VALUES ('341524', '金寨县', '341500');
INSERT INTO `__PREFIX__district` VALUES ('341525', '霍山县', '341500');
INSERT INTO `__PREFIX__district` VALUES ('341600', '亳州市', '340000');
INSERT INTO `__PREFIX__district` VALUES ('341601', '市辖区', '341600');
INSERT INTO `__PREFIX__district` VALUES ('341602', '谯城区', '341600');
INSERT INTO `__PREFIX__district` VALUES ('341621', '涡阳县', '341600');
INSERT INTO `__PREFIX__district` VALUES ('341622', '蒙城县', '341600');
INSERT INTO `__PREFIX__district` VALUES ('341623', '利辛县', '341600');
INSERT INTO `__PREFIX__district` VALUES ('341700', '池州市', '340000');
INSERT INTO `__PREFIX__district` VALUES ('341701', '市辖区', '341700');
INSERT INTO `__PREFIX__district` VALUES ('341702', '贵池区', '341700');
INSERT INTO `__PREFIX__district` VALUES ('341721', '东至县', '341700');
INSERT INTO `__PREFIX__district` VALUES ('341722', '石台县', '341700');
INSERT INTO `__PREFIX__district` VALUES ('341723', '青阳县', '341700');
INSERT INTO `__PREFIX__district` VALUES ('341800', '宣城市', '340000');
INSERT INTO `__PREFIX__district` VALUES ('341801', '市辖区', '341800');
INSERT INTO `__PREFIX__district` VALUES ('341802', '宣州区', '341800');
INSERT INTO `__PREFIX__district` VALUES ('341821', '郎溪县', '341800');
INSERT INTO `__PREFIX__district` VALUES ('341822', '广德县', '341800');
INSERT INTO `__PREFIX__district` VALUES ('341823', '泾县', '341800');
INSERT INTO `__PREFIX__district` VALUES ('341824', '绩溪县', '341800');
INSERT INTO `__PREFIX__district` VALUES ('341825', '旌德县', '341800');
INSERT INTO `__PREFIX__district` VALUES ('341881', '宁国市', '341800');
INSERT INTO `__PREFIX__district` VALUES ('350000', '福建省', '0');
INSERT INTO `__PREFIX__district` VALUES ('350100', '福州市', '350000');
INSERT INTO `__PREFIX__district` VALUES ('350101', '市辖区', '350100');
INSERT INTO `__PREFIX__district` VALUES ('350102', '鼓楼区', '350100');
INSERT INTO `__PREFIX__district` VALUES ('350103', '台江区', '350100');
INSERT INTO `__PREFIX__district` VALUES ('350104', '仓山区', '350100');
INSERT INTO `__PREFIX__district` VALUES ('350105', '马尾区', '350100');
INSERT INTO `__PREFIX__district` VALUES ('350111', '晋安区', '350100');
INSERT INTO `__PREFIX__district` VALUES ('350121', '闽侯县', '350100');
INSERT INTO `__PREFIX__district` VALUES ('350122', '连江县', '350100');
INSERT INTO `__PREFIX__district` VALUES ('350123', '罗源县', '350100');
INSERT INTO `__PREFIX__district` VALUES ('350124', '闽清县', '350100');
INSERT INTO `__PREFIX__district` VALUES ('350125', '永泰县', '350100');
INSERT INTO `__PREFIX__district` VALUES ('350128', '平潭县', '350100');
INSERT INTO `__PREFIX__district` VALUES ('350181', '福清市', '350100');
INSERT INTO `__PREFIX__district` VALUES ('350182', '长乐市', '350100');
INSERT INTO `__PREFIX__district` VALUES ('350200', '厦门市', '350000');
INSERT INTO `__PREFIX__district` VALUES ('350201', '市辖区', '350200');
INSERT INTO `__PREFIX__district` VALUES ('350203', '思明区', '350200');
INSERT INTO `__PREFIX__district` VALUES ('350205', '海沧区', '350200');
INSERT INTO `__PREFIX__district` VALUES ('350206', '湖里区', '350200');
INSERT INTO `__PREFIX__district` VALUES ('350211', '集美区', '350200');
INSERT INTO `__PREFIX__district` VALUES ('350212', '同安区', '350200');
INSERT INTO `__PREFIX__district` VALUES ('350213', '翔安区', '350200');
INSERT INTO `__PREFIX__district` VALUES ('350300', '莆田市', '350000');
INSERT INTO `__PREFIX__district` VALUES ('350301', '市辖区', '350300');
INSERT INTO `__PREFIX__district` VALUES ('350302', '城厢区', '350300');
INSERT INTO `__PREFIX__district` VALUES ('350303', '涵江区', '350300');
INSERT INTO `__PREFIX__district` VALUES ('350304', '荔城区', '350300');
INSERT INTO `__PREFIX__district` VALUES ('350305', '秀屿区', '350300');
INSERT INTO `__PREFIX__district` VALUES ('350322', '仙游县', '350300');
INSERT INTO `__PREFIX__district` VALUES ('350400', '三明市', '350000');
INSERT INTO `__PREFIX__district` VALUES ('350401', '市辖区', '350400');
INSERT INTO `__PREFIX__district` VALUES ('350402', '梅列区', '350400');
INSERT INTO `__PREFIX__district` VALUES ('350403', '三元区', '350400');
INSERT INTO `__PREFIX__district` VALUES ('350421', '明溪县', '350400');
INSERT INTO `__PREFIX__district` VALUES ('350423', '清流县', '350400');
INSERT INTO `__PREFIX__district` VALUES ('350424', '宁化县', '350400');
INSERT INTO `__PREFIX__district` VALUES ('350425', '大田县', '350400');
INSERT INTO `__PREFIX__district` VALUES ('350426', '尤溪县', '350400');
INSERT INTO `__PREFIX__district` VALUES ('350427', '沙县', '350400');
INSERT INTO `__PREFIX__district` VALUES ('350428', '将乐县', '350400');
INSERT INTO `__PREFIX__district` VALUES ('350429', '泰宁县', '350400');
INSERT INTO `__PREFIX__district` VALUES ('350430', '建宁县', '350400');
INSERT INTO `__PREFIX__district` VALUES ('350481', '永安市', '350400');
INSERT INTO `__PREFIX__district` VALUES ('350500', '泉州市', '350000');
INSERT INTO `__PREFIX__district` VALUES ('350501', '市辖区', '350500');
INSERT INTO `__PREFIX__district` VALUES ('350502', '鲤城区', '350500');
INSERT INTO `__PREFIX__district` VALUES ('350503', '丰泽区', '350500');
INSERT INTO `__PREFIX__district` VALUES ('350504', '洛江区', '350500');
INSERT INTO `__PREFIX__district` VALUES ('350505', '泉港区', '350500');
INSERT INTO `__PREFIX__district` VALUES ('350521', '惠安县', '350500');
INSERT INTO `__PREFIX__district` VALUES ('350524', '安溪县', '350500');
INSERT INTO `__PREFIX__district` VALUES ('350525', '永春县', '350500');
INSERT INTO `__PREFIX__district` VALUES ('350526', '德化县', '350500');
INSERT INTO `__PREFIX__district` VALUES ('350527', '金门县', '350500');
INSERT INTO `__PREFIX__district` VALUES ('350581', '石狮市', '350500');
INSERT INTO `__PREFIX__district` VALUES ('350582', '晋江市', '350500');
INSERT INTO `__PREFIX__district` VALUES ('350583', '南安市', '350500');
INSERT INTO `__PREFIX__district` VALUES ('350600', '漳州市', '350000');
INSERT INTO `__PREFIX__district` VALUES ('350601', '市辖区', '350600');
INSERT INTO `__PREFIX__district` VALUES ('350602', '芗城区', '350600');
INSERT INTO `__PREFIX__district` VALUES ('350603', '龙文区', '350600');
INSERT INTO `__PREFIX__district` VALUES ('350622', '云霄县', '350600');
INSERT INTO `__PREFIX__district` VALUES ('350623', '漳浦县', '350600');
INSERT INTO `__PREFIX__district` VALUES ('350624', '诏安县', '350600');
INSERT INTO `__PREFIX__district` VALUES ('350625', '长泰县', '350600');
INSERT INTO `__PREFIX__district` VALUES ('350626', '东山县', '350600');
INSERT INTO `__PREFIX__district` VALUES ('350627', '南靖县', '350600');
INSERT INTO `__PREFIX__district` VALUES ('350628', '平和县', '350600');
INSERT INTO `__PREFIX__district` VALUES ('350629', '华安县', '350600');
INSERT INTO `__PREFIX__district` VALUES ('350681', '龙海市', '350600');
INSERT INTO `__PREFIX__district` VALUES ('350700', '南平市', '350000');
INSERT INTO `__PREFIX__district` VALUES ('350701', '市辖区', '350700');
INSERT INTO `__PREFIX__district` VALUES ('350702', '延平区', '350700');
INSERT INTO `__PREFIX__district` VALUES ('350703', '建阳区', '350700');
INSERT INTO `__PREFIX__district` VALUES ('350721', '顺昌县', '350700');
INSERT INTO `__PREFIX__district` VALUES ('350722', '浦城县', '350700');
INSERT INTO `__PREFIX__district` VALUES ('350723', '光泽县', '350700');
INSERT INTO `__PREFIX__district` VALUES ('350724', '松溪县', '350700');
INSERT INTO `__PREFIX__district` VALUES ('350725', '政和县', '350700');
INSERT INTO `__PREFIX__district` VALUES ('350781', '邵武市', '350700');
INSERT INTO `__PREFIX__district` VALUES ('350782', '武夷山市', '350700');
INSERT INTO `__PREFIX__district` VALUES ('350783', '建瓯市', '350700');
INSERT INTO `__PREFIX__district` VALUES ('350800', '龙岩市', '350000');
INSERT INTO `__PREFIX__district` VALUES ('350801', '市辖区', '350800');
INSERT INTO `__PREFIX__district` VALUES ('350802', '新罗区', '350800');
INSERT INTO `__PREFIX__district` VALUES ('350803', '永定区', '350800');
INSERT INTO `__PREFIX__district` VALUES ('350821', '长汀县', '350800');
INSERT INTO `__PREFIX__district` VALUES ('350823', '上杭县', '350800');
INSERT INTO `__PREFIX__district` VALUES ('350824', '武平县', '350800');
INSERT INTO `__PREFIX__district` VALUES ('350825', '连城县', '350800');
INSERT INTO `__PREFIX__district` VALUES ('350881', '漳平市', '350800');
INSERT INTO `__PREFIX__district` VALUES ('350900', '宁德市', '350000');
INSERT INTO `__PREFIX__district` VALUES ('350901', '市辖区', '350900');
INSERT INTO `__PREFIX__district` VALUES ('350902', '蕉城区', '350900');
INSERT INTO `__PREFIX__district` VALUES ('350921', '霞浦县', '350900');
INSERT INTO `__PREFIX__district` VALUES ('350922', '古田县', '350900');
INSERT INTO `__PREFIX__district` VALUES ('350923', '屏南县', '350900');
INSERT INTO `__PREFIX__district` VALUES ('350924', '寿宁县', '350900');
INSERT INTO `__PREFIX__district` VALUES ('350925', '周宁县', '350900');
INSERT INTO `__PREFIX__district` VALUES ('350926', '柘荣县', '350900');
INSERT INTO `__PREFIX__district` VALUES ('350981', '福安市', '350900');
INSERT INTO `__PREFIX__district` VALUES ('350982', '福鼎市', '350900');
INSERT INTO `__PREFIX__district` VALUES ('360000', '江西省', '0');
INSERT INTO `__PREFIX__district` VALUES ('360100', '南昌市', '360000');
INSERT INTO `__PREFIX__district` VALUES ('360101', '市辖区', '360100');
INSERT INTO `__PREFIX__district` VALUES ('360102', '东湖区', '360100');
INSERT INTO `__PREFIX__district` VALUES ('360103', '西湖区', '360100');
INSERT INTO `__PREFIX__district` VALUES ('360104', '青云谱区', '360100');
INSERT INTO `__PREFIX__district` VALUES ('360105', '湾里区', '360100');
INSERT INTO `__PREFIX__district` VALUES ('360111', '青山湖区', '360100');
INSERT INTO `__PREFIX__district` VALUES ('360121', '南昌县', '360100');
INSERT INTO `__PREFIX__district` VALUES ('360122', '新建县', '360100');
INSERT INTO `__PREFIX__district` VALUES ('360123', '安义县', '360100');
INSERT INTO `__PREFIX__district` VALUES ('360124', '进贤县', '360100');
INSERT INTO `__PREFIX__district` VALUES ('360200', '景德镇市', '360000');
INSERT INTO `__PREFIX__district` VALUES ('360201', '市辖区', '360200');
INSERT INTO `__PREFIX__district` VALUES ('360202', '昌江区', '360200');
INSERT INTO `__PREFIX__district` VALUES ('360203', '珠山区', '360200');
INSERT INTO `__PREFIX__district` VALUES ('360222', '浮梁县', '360200');
INSERT INTO `__PREFIX__district` VALUES ('360281', '乐平市', '360200');
INSERT INTO `__PREFIX__district` VALUES ('360300', '萍乡市', '360000');
INSERT INTO `__PREFIX__district` VALUES ('360301', '市辖区', '360300');
INSERT INTO `__PREFIX__district` VALUES ('360302', '安源区', '360300');
INSERT INTO `__PREFIX__district` VALUES ('360313', '湘东区', '360300');
INSERT INTO `__PREFIX__district` VALUES ('360321', '莲花县', '360300');
INSERT INTO `__PREFIX__district` VALUES ('360322', '上栗县', '360300');
INSERT INTO `__PREFIX__district` VALUES ('360323', '芦溪县', '360300');
INSERT INTO `__PREFIX__district` VALUES ('360400', '九江市', '360000');
INSERT INTO `__PREFIX__district` VALUES ('360401', '市辖区', '360400');
INSERT INTO `__PREFIX__district` VALUES ('360402', '濂溪区', '360400');
INSERT INTO `__PREFIX__district` VALUES ('360403', '浔阳区', '360400');
INSERT INTO `__PREFIX__district` VALUES ('360404', '庐山区', '360400');
INSERT INTO `__PREFIX__district` VALUES ('360421', '九江县', '360400');
INSERT INTO `__PREFIX__district` VALUES ('360423', '武宁县', '360400');
INSERT INTO `__PREFIX__district` VALUES ('360424', '修水县', '360400');
INSERT INTO `__PREFIX__district` VALUES ('360425', '永修县', '360400');
INSERT INTO `__PREFIX__district` VALUES ('360426', '德安县', '360400');
INSERT INTO `__PREFIX__district` VALUES ('360427', '星子县', '360400');
INSERT INTO `__PREFIX__district` VALUES ('360428', '都昌县', '360400');
INSERT INTO `__PREFIX__district` VALUES ('360429', '湖口县', '360400');
INSERT INTO `__PREFIX__district` VALUES ('360430', '彭泽县', '360400');
INSERT INTO `__PREFIX__district` VALUES ('360481', '瑞昌市', '360400');
INSERT INTO `__PREFIX__district` VALUES ('360482', '共青城市', '360400');
INSERT INTO `__PREFIX__district` VALUES ('360500', '新余市', '360000');
INSERT INTO `__PREFIX__district` VALUES ('360501', '市辖区', '360500');
INSERT INTO `__PREFIX__district` VALUES ('360502', '渝水区', '360500');
INSERT INTO `__PREFIX__district` VALUES ('360503', '高新区', '360500');
INSERT INTO `__PREFIX__district` VALUES ('360521', '分宜县', '360500');
INSERT INTO `__PREFIX__district` VALUES ('360600', '鹰潭市', '360000');
INSERT INTO `__PREFIX__district` VALUES ('360601', '市辖区', '360600');
INSERT INTO `__PREFIX__district` VALUES ('360602', '月湖区', '360600');
INSERT INTO `__PREFIX__district` VALUES ('360622', '余江县', '360600');
INSERT INTO `__PREFIX__district` VALUES ('360681', '贵溪市', '360600');
INSERT INTO `__PREFIX__district` VALUES ('360700', '赣州市', '360000');
INSERT INTO `__PREFIX__district` VALUES ('360701', '市辖区', '360700');
INSERT INTO `__PREFIX__district` VALUES ('360702', '章贡区', '360700');
INSERT INTO `__PREFIX__district` VALUES ('360703', '南康区', '360700');
INSERT INTO `__PREFIX__district` VALUES ('360721', '赣县', '360700');
INSERT INTO `__PREFIX__district` VALUES ('360722', '信丰县', '360700');
INSERT INTO `__PREFIX__district` VALUES ('360723', '大余县', '360700');
INSERT INTO `__PREFIX__district` VALUES ('360724', '上犹县', '360700');
INSERT INTO `__PREFIX__district` VALUES ('360725', '崇义县', '360700');
INSERT INTO `__PREFIX__district` VALUES ('360726', '安远县', '360700');
INSERT INTO `__PREFIX__district` VALUES ('360727', '龙南县', '360700');
INSERT INTO `__PREFIX__district` VALUES ('360728', '定南县', '360700');
INSERT INTO `__PREFIX__district` VALUES ('360729', '全南县', '360700');
INSERT INTO `__PREFIX__district` VALUES ('360730', '宁都县', '360700');
INSERT INTO `__PREFIX__district` VALUES ('360731', '于都县', '360700');
INSERT INTO `__PREFIX__district` VALUES ('360732', '兴国县', '360700');
INSERT INTO `__PREFIX__district` VALUES ('360733', '会昌县', '360700');
INSERT INTO `__PREFIX__district` VALUES ('360734', '寻乌县', '360700');
INSERT INTO `__PREFIX__district` VALUES ('360735', '石城县', '360700');
INSERT INTO `__PREFIX__district` VALUES ('360781', '瑞金市', '360700');
INSERT INTO `__PREFIX__district` VALUES ('360783', '经济技术开发区', '360700');
INSERT INTO `__PREFIX__district` VALUES ('360800', '吉安市', '360000');
INSERT INTO `__PREFIX__district` VALUES ('360801', '市辖区', '360800');
INSERT INTO `__PREFIX__district` VALUES ('360802', '吉州区', '360800');
INSERT INTO `__PREFIX__district` VALUES ('360803', '青原区', '360800');
INSERT INTO `__PREFIX__district` VALUES ('360821', '吉安县', '360800');
INSERT INTO `__PREFIX__district` VALUES ('360822', '吉水县', '360800');
INSERT INTO `__PREFIX__district` VALUES ('360823', '峡江县', '360800');
INSERT INTO `__PREFIX__district` VALUES ('360824', '新干县', '360800');
INSERT INTO `__PREFIX__district` VALUES ('360825', '永丰县', '360800');
INSERT INTO `__PREFIX__district` VALUES ('360826', '泰和县', '360800');
INSERT INTO `__PREFIX__district` VALUES ('360827', '遂川县', '360800');
INSERT INTO `__PREFIX__district` VALUES ('360828', '万安县', '360800');
INSERT INTO `__PREFIX__district` VALUES ('360829', '安福县', '360800');
INSERT INTO `__PREFIX__district` VALUES ('360830', '永新县', '360800');
INSERT INTO `__PREFIX__district` VALUES ('360881', '井冈山市', '360800');
INSERT INTO `__PREFIX__district` VALUES ('360900', '宜春市', '360000');
INSERT INTO `__PREFIX__district` VALUES ('360901', '市辖区', '360900');
INSERT INTO `__PREFIX__district` VALUES ('360902', '袁州区', '360900');
INSERT INTO `__PREFIX__district` VALUES ('360921', '奉新县', '360900');
INSERT INTO `__PREFIX__district` VALUES ('360922', '万载县', '360900');
INSERT INTO `__PREFIX__district` VALUES ('360923', '上高县', '360900');
INSERT INTO `__PREFIX__district` VALUES ('360924', '宜丰县', '360900');
INSERT INTO `__PREFIX__district` VALUES ('360925', '靖安县', '360900');
INSERT INTO `__PREFIX__district` VALUES ('360926', '铜鼓县', '360900');
INSERT INTO `__PREFIX__district` VALUES ('360981', '丰城市', '360900');
INSERT INTO `__PREFIX__district` VALUES ('360982', '樟树市', '360900');
INSERT INTO `__PREFIX__district` VALUES ('360983', '高安市', '360900');
INSERT INTO `__PREFIX__district` VALUES ('361000', '抚州市', '360000');
INSERT INTO `__PREFIX__district` VALUES ('361001', '市辖区', '361000');
INSERT INTO `__PREFIX__district` VALUES ('361002', '临川区', '361000');
INSERT INTO `__PREFIX__district` VALUES ('361021', '南城县', '361000');
INSERT INTO `__PREFIX__district` VALUES ('361022', '黎川县', '361000');
INSERT INTO `__PREFIX__district` VALUES ('361023', '南丰县', '361000');
INSERT INTO `__PREFIX__district` VALUES ('361024', '崇仁县', '361000');
INSERT INTO `__PREFIX__district` VALUES ('361025', '乐安县', '361000');
INSERT INTO `__PREFIX__district` VALUES ('361026', '宜黄县', '361000');
INSERT INTO `__PREFIX__district` VALUES ('361027', '金溪县', '361000');
INSERT INTO `__PREFIX__district` VALUES ('361028', '资溪县', '361000');
INSERT INTO `__PREFIX__district` VALUES ('361029', '东乡县', '361000');
INSERT INTO `__PREFIX__district` VALUES ('361030', '广昌县', '361000');
INSERT INTO `__PREFIX__district` VALUES ('361100', '上饶市', '360000');
INSERT INTO `__PREFIX__district` VALUES ('361101', '市辖区', '361100');
INSERT INTO `__PREFIX__district` VALUES ('361102', '信州区', '361100');
INSERT INTO `__PREFIX__district` VALUES ('361121', '上饶县', '361100');
INSERT INTO `__PREFIX__district` VALUES ('361122', '广丰县', '361100');
INSERT INTO `__PREFIX__district` VALUES ('361123', '玉山县', '361100');
INSERT INTO `__PREFIX__district` VALUES ('361124', '铅山县', '361100');
INSERT INTO `__PREFIX__district` VALUES ('361125', '横峰县', '361100');
INSERT INTO `__PREFIX__district` VALUES ('361126', '弋阳县', '361100');
INSERT INTO `__PREFIX__district` VALUES ('361127', '余干县', '361100');
INSERT INTO `__PREFIX__district` VALUES ('361128', '鄱阳县', '361100');
INSERT INTO `__PREFIX__district` VALUES ('361129', '万年县', '361100');
INSERT INTO `__PREFIX__district` VALUES ('361130', '婺源县', '361100');
INSERT INTO `__PREFIX__district` VALUES ('361181', '德兴市', '361100');
INSERT INTO `__PREFIX__district` VALUES ('370000', '山东省', '0');
INSERT INTO `__PREFIX__district` VALUES ('370100', '济南市', '370000');
INSERT INTO `__PREFIX__district` VALUES ('370101', '市辖区', '370100');
INSERT INTO `__PREFIX__district` VALUES ('370102', '历下区', '370100');
INSERT INTO `__PREFIX__district` VALUES ('370103', '市中区', '370100');
INSERT INTO `__PREFIX__district` VALUES ('370104', '槐荫区', '370100');
INSERT INTO `__PREFIX__district` VALUES ('370105', '天桥区', '370100');
INSERT INTO `__PREFIX__district` VALUES ('370112', '历城区', '370100');
INSERT INTO `__PREFIX__district` VALUES ('370113', '长清区', '370100');
INSERT INTO `__PREFIX__district` VALUES ('370124', '平阴县', '370100');
INSERT INTO `__PREFIX__district` VALUES ('370125', '济阳县', '370100');
INSERT INTO `__PREFIX__district` VALUES ('370126', '商河县', '370100');
INSERT INTO `__PREFIX__district` VALUES ('370130', '莱芜区', '370100');
INSERT INTO `__PREFIX__district` VALUES ('370181', '章丘市', '370100');
INSERT INTO `__PREFIX__district` VALUES ('370200', '青岛市', '370000');
INSERT INTO `__PREFIX__district` VALUES ('370201', '市辖区', '370200');
INSERT INTO `__PREFIX__district` VALUES ('370202', '市南区', '370200');
INSERT INTO `__PREFIX__district` VALUES ('370203', '市北区', '370200');
INSERT INTO `__PREFIX__district` VALUES ('370211', '黄岛区', '370200');
INSERT INTO `__PREFIX__district` VALUES ('370212', '崂山区', '370200');
INSERT INTO `__PREFIX__district` VALUES ('370213', '李沧区', '370200');
INSERT INTO `__PREFIX__district` VALUES ('370214', '城阳区', '370200');
INSERT INTO `__PREFIX__district` VALUES ('370281', '胶州市', '370200');
INSERT INTO `__PREFIX__district` VALUES ('370282', '即墨市', '370200');
INSERT INTO `__PREFIX__district` VALUES ('370283', '平度市', '370200');
INSERT INTO `__PREFIX__district` VALUES ('370285', '莱西市', '370200');
INSERT INTO `__PREFIX__district` VALUES ('370300', '淄博市', '370000');
INSERT INTO `__PREFIX__district` VALUES ('370301', '市辖区', '370300');
INSERT INTO `__PREFIX__district` VALUES ('370302', '淄川区', '370300');
INSERT INTO `__PREFIX__district` VALUES ('370303', '张店区', '370300');
INSERT INTO `__PREFIX__district` VALUES ('370304', '博山区', '370300');
INSERT INTO `__PREFIX__district` VALUES ('370305', '临淄区', '370300');
INSERT INTO `__PREFIX__district` VALUES ('370306', '周村区', '370300');
INSERT INTO `__PREFIX__district` VALUES ('370321', '桓台县', '370300');
INSERT INTO `__PREFIX__district` VALUES ('370322', '高青县', '370300');
INSERT INTO `__PREFIX__district` VALUES ('370323', '沂源县', '370300');
INSERT INTO `__PREFIX__district` VALUES ('370400', '枣庄市', '370000');
INSERT INTO `__PREFIX__district` VALUES ('370401', '市辖区', '370400');
INSERT INTO `__PREFIX__district` VALUES ('370402', '市中区', '370400');
INSERT INTO `__PREFIX__district` VALUES ('370403', '薛城区', '370400');
INSERT INTO `__PREFIX__district` VALUES ('370404', '峄城区', '370400');
INSERT INTO `__PREFIX__district` VALUES ('370405', '台儿庄区', '370400');
INSERT INTO `__PREFIX__district` VALUES ('370406', '山亭区', '370400');
INSERT INTO `__PREFIX__district` VALUES ('370481', '滕州市', '370400');
INSERT INTO `__PREFIX__district` VALUES ('370500', '东营市', '370000');
INSERT INTO `__PREFIX__district` VALUES ('370501', '市辖区', '370500');
INSERT INTO `__PREFIX__district` VALUES ('370502', '东营区', '370500');
INSERT INTO `__PREFIX__district` VALUES ('370503', '河口区', '370500');
INSERT INTO `__PREFIX__district` VALUES ('370521', '垦利县', '370500');
INSERT INTO `__PREFIX__district` VALUES ('370522', '利津县', '370500');
INSERT INTO `__PREFIX__district` VALUES ('370523', '广饶县', '370500');
INSERT INTO `__PREFIX__district` VALUES ('370600', '烟台市', '370000');
INSERT INTO `__PREFIX__district` VALUES ('370601', '市辖区', '370600');
INSERT INTO `__PREFIX__district` VALUES ('370602', '芝罘区', '370600');
INSERT INTO `__PREFIX__district` VALUES ('370611', '福山区', '370600');
INSERT INTO `__PREFIX__district` VALUES ('370612', '牟平区', '370600');
INSERT INTO `__PREFIX__district` VALUES ('370613', '莱山区', '370600');
INSERT INTO `__PREFIX__district` VALUES ('370634', '长岛县', '370600');
INSERT INTO `__PREFIX__district` VALUES ('370681', '龙口市', '370600');
INSERT INTO `__PREFIX__district` VALUES ('370682', '莱阳市', '370600');
INSERT INTO `__PREFIX__district` VALUES ('370683', '莱州市', '370600');
INSERT INTO `__PREFIX__district` VALUES ('370684', '蓬莱市', '370600');
INSERT INTO `__PREFIX__district` VALUES ('370685', '招远市', '370600');
INSERT INTO `__PREFIX__district` VALUES ('370686', '栖霞市', '370600');
INSERT INTO `__PREFIX__district` VALUES ('370687', '海阳市', '370600');
INSERT INTO `__PREFIX__district` VALUES ('370700', '潍坊市', '370000');
INSERT INTO `__PREFIX__district` VALUES ('370701', '市辖区', '370700');
INSERT INTO `__PREFIX__district` VALUES ('370702', '潍城区', '370700');
INSERT INTO `__PREFIX__district` VALUES ('370703', '寒亭区', '370700');
INSERT INTO `__PREFIX__district` VALUES ('370704', '坊子区', '370700');
INSERT INTO `__PREFIX__district` VALUES ('370705', '奎文区', '370700');
INSERT INTO `__PREFIX__district` VALUES ('370724', '临朐县', '370700');
INSERT INTO `__PREFIX__district` VALUES ('370725', '昌乐县', '370700');
INSERT INTO `__PREFIX__district` VALUES ('370781', '青州市', '370700');
INSERT INTO `__PREFIX__district` VALUES ('370782', '诸城市', '370700');
INSERT INTO `__PREFIX__district` VALUES ('370783', '寿光市', '370700');
INSERT INTO `__PREFIX__district` VALUES ('370784', '安丘市', '370700');
INSERT INTO `__PREFIX__district` VALUES ('370785', '高密市', '370700');
INSERT INTO `__PREFIX__district` VALUES ('370786', '昌邑市', '370700');
INSERT INTO `__PREFIX__district` VALUES ('370800', '济宁市', '370000');
INSERT INTO `__PREFIX__district` VALUES ('370801', '市辖区', '370800');
INSERT INTO `__PREFIX__district` VALUES ('370811', '任城区', '370800');
INSERT INTO `__PREFIX__district` VALUES ('370812', '兖州区', '370800');
INSERT INTO `__PREFIX__district` VALUES ('370826', '微山县', '370800');
INSERT INTO `__PREFIX__district` VALUES ('370827', '鱼台县', '370800');
INSERT INTO `__PREFIX__district` VALUES ('370828', '金乡县', '370800');
INSERT INTO `__PREFIX__district` VALUES ('370829', '嘉祥县', '370800');
INSERT INTO `__PREFIX__district` VALUES ('370830', '汶上县', '370800');
INSERT INTO `__PREFIX__district` VALUES ('370831', '泗水县', '370800');
INSERT INTO `__PREFIX__district` VALUES ('370832', '梁山县', '370800');
INSERT INTO `__PREFIX__district` VALUES ('370881', '曲阜市', '370800');
INSERT INTO `__PREFIX__district` VALUES ('370883', '邹城市', '370800');
INSERT INTO `__PREFIX__district` VALUES ('370900', '泰安市', '370000');
INSERT INTO `__PREFIX__district` VALUES ('370901', '市辖区', '370900');
INSERT INTO `__PREFIX__district` VALUES ('370902', '泰山区', '370900');
INSERT INTO `__PREFIX__district` VALUES ('370911', '岱岳区', '370900');
INSERT INTO `__PREFIX__district` VALUES ('370921', '宁阳县', '370900');
INSERT INTO `__PREFIX__district` VALUES ('370923', '东平县', '370900');
INSERT INTO `__PREFIX__district` VALUES ('370982', '新泰市', '370900');
INSERT INTO `__PREFIX__district` VALUES ('370983', '肥城市', '370900');
INSERT INTO `__PREFIX__district` VALUES ('371000', '威海市', '370000');
INSERT INTO `__PREFIX__district` VALUES ('371001', '市辖区', '371000');
INSERT INTO `__PREFIX__district` VALUES ('371002', '环翠区', '371000');
INSERT INTO `__PREFIX__district` VALUES ('371081', '文登市', '371000');
INSERT INTO `__PREFIX__district` VALUES ('371082', '荣成市', '371000');
INSERT INTO `__PREFIX__district` VALUES ('371083', '乳山市', '371000');
INSERT INTO `__PREFIX__district` VALUES ('371100', '日照市', '370000');
INSERT INTO `__PREFIX__district` VALUES ('371101', '市辖区', '371100');
INSERT INTO `__PREFIX__district` VALUES ('371102', '东港区', '371100');
INSERT INTO `__PREFIX__district` VALUES ('371103', '岚山区', '371100');
INSERT INTO `__PREFIX__district` VALUES ('371121', '五莲县', '371100');
INSERT INTO `__PREFIX__district` VALUES ('371122', '莒县', '371100');
INSERT INTO `__PREFIX__district` VALUES ('371200', '莱芜市', '370000');
INSERT INTO `__PREFIX__district` VALUES ('371201', '市辖区', '371200');
INSERT INTO `__PREFIX__district` VALUES ('371202', '莱城区', '371200');
INSERT INTO `__PREFIX__district` VALUES ('371203', '钢城区', '371200');
INSERT INTO `__PREFIX__district` VALUES ('371300', '临沂市', '370000');
INSERT INTO `__PREFIX__district` VALUES ('371301', '市辖区', '371300');
INSERT INTO `__PREFIX__district` VALUES ('371302', '兰山区', '371300');
INSERT INTO `__PREFIX__district` VALUES ('371311', '罗庄区', '371300');
INSERT INTO `__PREFIX__district` VALUES ('371312', '河东区', '371300');
INSERT INTO `__PREFIX__district` VALUES ('371321', '沂南县', '371300');
INSERT INTO `__PREFIX__district` VALUES ('371322', '郯城县', '371300');
INSERT INTO `__PREFIX__district` VALUES ('371323', '沂水县', '371300');
INSERT INTO `__PREFIX__district` VALUES ('371324', '兰陵县', '371300');
INSERT INTO `__PREFIX__district` VALUES ('371325', '费县', '371300');
INSERT INTO `__PREFIX__district` VALUES ('371326', '平邑县', '371300');
INSERT INTO `__PREFIX__district` VALUES ('371327', '莒南县', '371300');
INSERT INTO `__PREFIX__district` VALUES ('371328', '蒙阴县', '371300');
INSERT INTO `__PREFIX__district` VALUES ('371329', '临沭县', '371300');
INSERT INTO `__PREFIX__district` VALUES ('371400', '德州市', '370000');
INSERT INTO `__PREFIX__district` VALUES ('371401', '市辖区', '371400');
INSERT INTO `__PREFIX__district` VALUES ('371402', '德城区', '371400');
INSERT INTO `__PREFIX__district` VALUES ('371403', '陵城区', '371400');
INSERT INTO `__PREFIX__district` VALUES ('371422', '宁津县', '371400');
INSERT INTO `__PREFIX__district` VALUES ('371423', '庆云县', '371400');
INSERT INTO `__PREFIX__district` VALUES ('371424', '临邑县', '371400');
INSERT INTO `__PREFIX__district` VALUES ('371425', '齐河县', '371400');
INSERT INTO `__PREFIX__district` VALUES ('371426', '平原县', '371400');
INSERT INTO `__PREFIX__district` VALUES ('371427', '夏津县', '371400');
INSERT INTO `__PREFIX__district` VALUES ('371428', '武城县', '371400');
INSERT INTO `__PREFIX__district` VALUES ('371481', '乐陵市', '371400');
INSERT INTO `__PREFIX__district` VALUES ('371482', '禹城市', '371400');
INSERT INTO `__PREFIX__district` VALUES ('371500', '聊城市', '370000');
INSERT INTO `__PREFIX__district` VALUES ('371501', '市辖区', '371500');
INSERT INTO `__PREFIX__district` VALUES ('371502', '东昌府区', '371500');
INSERT INTO `__PREFIX__district` VALUES ('371521', '阳谷县', '371500');
INSERT INTO `__PREFIX__district` VALUES ('371522', '莘县', '371500');
INSERT INTO `__PREFIX__district` VALUES ('371523', '茌平县', '371500');
INSERT INTO `__PREFIX__district` VALUES ('371524', '东阿县', '371500');
INSERT INTO `__PREFIX__district` VALUES ('371525', '冠县', '371500');
INSERT INTO `__PREFIX__district` VALUES ('371526', '高唐县', '371500');
INSERT INTO `__PREFIX__district` VALUES ('371581', '临清市', '371500');
INSERT INTO `__PREFIX__district` VALUES ('371600', '滨州市', '370000');
INSERT INTO `__PREFIX__district` VALUES ('371601', '市辖区', '371600');
INSERT INTO `__PREFIX__district` VALUES ('371602', '滨城区', '371600');
INSERT INTO `__PREFIX__district` VALUES ('371603', '沾化区', '371600');
INSERT INTO `__PREFIX__district` VALUES ('371621', '惠民县', '371600');
INSERT INTO `__PREFIX__district` VALUES ('371622', '阳信县', '371600');
INSERT INTO `__PREFIX__district` VALUES ('371623', '无棣县', '371600');
INSERT INTO `__PREFIX__district` VALUES ('371625', '博兴县', '371600');
INSERT INTO `__PREFIX__district` VALUES ('371626', '邹平县', '371600');
INSERT INTO `__PREFIX__district` VALUES ('371700', '菏泽市', '370000');
INSERT INTO `__PREFIX__district` VALUES ('371701', '市辖区', '371700');
INSERT INTO `__PREFIX__district` VALUES ('371702', '牡丹区', '371700');
INSERT INTO `__PREFIX__district` VALUES ('371721', '曹县', '371700');
INSERT INTO `__PREFIX__district` VALUES ('371722', '单县', '371700');
INSERT INTO `__PREFIX__district` VALUES ('371723', '成武县', '371700');
INSERT INTO `__PREFIX__district` VALUES ('371724', '巨野县', '371700');
INSERT INTO `__PREFIX__district` VALUES ('371725', '郓城县', '371700');
INSERT INTO `__PREFIX__district` VALUES ('371726', '鄄城县', '371700');
INSERT INTO `__PREFIX__district` VALUES ('371727', '定陶县', '371700');
INSERT INTO `__PREFIX__district` VALUES ('371728', '东明县', '371700');
INSERT INTO `__PREFIX__district` VALUES ('410000', '河南省', '0');
INSERT INTO `__PREFIX__district` VALUES ('410100', '郑州市', '410000');
INSERT INTO `__PREFIX__district` VALUES ('410101', '市辖区', '410100');
INSERT INTO `__PREFIX__district` VALUES ('410102', '中原区', '410100');
INSERT INTO `__PREFIX__district` VALUES ('410103', '二七区', '410100');
INSERT INTO `__PREFIX__district` VALUES ('410104', '管城回族区', '410100');
INSERT INTO `__PREFIX__district` VALUES ('410105', '金水区', '410100');
INSERT INTO `__PREFIX__district` VALUES ('410106', '上街区', '410100');
INSERT INTO `__PREFIX__district` VALUES ('410108', '惠济区', '410100');
INSERT INTO `__PREFIX__district` VALUES ('410122', '中牟县', '410100');
INSERT INTO `__PREFIX__district` VALUES ('410181', '巩义市', '410100');
INSERT INTO `__PREFIX__district` VALUES ('410182', '荥阳市', '410100');
INSERT INTO `__PREFIX__district` VALUES ('410183', '新密市', '410100');
INSERT INTO `__PREFIX__district` VALUES ('410184', '新郑市', '410100');
INSERT INTO `__PREFIX__district` VALUES ('410185', '登封市', '410100');
INSERT INTO `__PREFIX__district` VALUES ('410186', '郑东新区', '410100');
INSERT INTO `__PREFIX__district` VALUES ('410200', '开封市', '410000');
INSERT INTO `__PREFIX__district` VALUES ('410201', '市辖区', '410200');
INSERT INTO `__PREFIX__district` VALUES ('410202', '龙亭区', '410200');
INSERT INTO `__PREFIX__district` VALUES ('410203', '顺河回族区', '410200');
INSERT INTO `__PREFIX__district` VALUES ('410204', '鼓楼区', '410200');
INSERT INTO `__PREFIX__district` VALUES ('410205', '禹王台区', '410200');
INSERT INTO `__PREFIX__district` VALUES ('410211', '金明新区', '410200');
INSERT INTO `__PREFIX__district` VALUES ('410212', '祥符区', '410200');
INSERT INTO `__PREFIX__district` VALUES ('410221', '杞县', '410200');
INSERT INTO `__PREFIX__district` VALUES ('410222', '通许县', '410200');
INSERT INTO `__PREFIX__district` VALUES ('410223', '尉氏县', '410200');
INSERT INTO `__PREFIX__district` VALUES ('410225', '兰考县', '410200');
INSERT INTO `__PREFIX__district` VALUES ('410300', '洛阳市', '410000');
INSERT INTO `__PREFIX__district` VALUES ('410301', '市辖区', '410300');
INSERT INTO `__PREFIX__district` VALUES ('410302', '老城区', '410300');
INSERT INTO `__PREFIX__district` VALUES ('410303', '西工区', '410300');
INSERT INTO `__PREFIX__district` VALUES ('410304', '瀍河回族区', '410300');
INSERT INTO `__PREFIX__district` VALUES ('410305', '涧西区', '410300');
INSERT INTO `__PREFIX__district` VALUES ('410306', '吉利区', '410300');
INSERT INTO `__PREFIX__district` VALUES ('410311', '洛龙区', '410300');
INSERT INTO `__PREFIX__district` VALUES ('410322', '孟津县', '410300');
INSERT INTO `__PREFIX__district` VALUES ('410323', '新安县', '410300');
INSERT INTO `__PREFIX__district` VALUES ('410324', '栾川县', '410300');
INSERT INTO `__PREFIX__district` VALUES ('410325', '嵩县', '410300');
INSERT INTO `__PREFIX__district` VALUES ('410326', '汝阳县', '410300');
INSERT INTO `__PREFIX__district` VALUES ('410327', '宜阳县', '410300');
INSERT INTO `__PREFIX__district` VALUES ('410328', '洛宁县', '410300');
INSERT INTO `__PREFIX__district` VALUES ('410329', '伊川县', '410300');
INSERT INTO `__PREFIX__district` VALUES ('410381', '偃师市', '410300');
INSERT INTO `__PREFIX__district` VALUES ('410400', '平顶山市', '410000');
INSERT INTO `__PREFIX__district` VALUES ('410401', '市辖区', '410400');
INSERT INTO `__PREFIX__district` VALUES ('410402', '新华区', '410400');
INSERT INTO `__PREFIX__district` VALUES ('410403', '卫东区', '410400');
INSERT INTO `__PREFIX__district` VALUES ('410404', '石龙区', '410400');
INSERT INTO `__PREFIX__district` VALUES ('410411', '湛河区', '410400');
INSERT INTO `__PREFIX__district` VALUES ('410421', '宝丰县', '410400');
INSERT INTO `__PREFIX__district` VALUES ('410422', '叶县', '410400');
INSERT INTO `__PREFIX__district` VALUES ('410423', '鲁山县', '410400');
INSERT INTO `__PREFIX__district` VALUES ('410425', '郏县', '410400');
INSERT INTO `__PREFIX__district` VALUES ('410481', '舞钢市', '410400');
INSERT INTO `__PREFIX__district` VALUES ('410482', '汝州市', '410400');
INSERT INTO `__PREFIX__district` VALUES ('410500', '安阳市', '410000');
INSERT INTO `__PREFIX__district` VALUES ('410501', '市辖区', '410500');
INSERT INTO `__PREFIX__district` VALUES ('410502', '文峰区', '410500');
INSERT INTO `__PREFIX__district` VALUES ('410503', '北关区', '410500');
INSERT INTO `__PREFIX__district` VALUES ('410505', '殷都区', '410500');
INSERT INTO `__PREFIX__district` VALUES ('410506', '龙安区', '410500');
INSERT INTO `__PREFIX__district` VALUES ('410522', '安阳县', '410500');
INSERT INTO `__PREFIX__district` VALUES ('410523', '汤阴县', '410500');
INSERT INTO `__PREFIX__district` VALUES ('410526', '滑县', '410500');
INSERT INTO `__PREFIX__district` VALUES ('410527', '内黄县', '410500');
INSERT INTO `__PREFIX__district` VALUES ('410581', '林州市', '410500');
INSERT INTO `__PREFIX__district` VALUES ('410600', '鹤壁市', '410000');
INSERT INTO `__PREFIX__district` VALUES ('410601', '市辖区', '410600');
INSERT INTO `__PREFIX__district` VALUES ('410602', '鹤山区', '410600');
INSERT INTO `__PREFIX__district` VALUES ('410603', '山城区', '410600');
INSERT INTO `__PREFIX__district` VALUES ('410611', '淇滨区', '410600');
INSERT INTO `__PREFIX__district` VALUES ('410621', '浚县', '410600');
INSERT INTO `__PREFIX__district` VALUES ('410622', '淇县', '410600');
INSERT INTO `__PREFIX__district` VALUES ('410700', '新乡市', '410000');
INSERT INTO `__PREFIX__district` VALUES ('410701', '市辖区', '410700');
INSERT INTO `__PREFIX__district` VALUES ('410702', '红旗区', '410700');
INSERT INTO `__PREFIX__district` VALUES ('410703', '卫滨区', '410700');
INSERT INTO `__PREFIX__district` VALUES ('410704', '凤泉区', '410700');
INSERT INTO `__PREFIX__district` VALUES ('410711', '牧野区', '410700');
INSERT INTO `__PREFIX__district` VALUES ('410721', '新乡县', '410700');
INSERT INTO `__PREFIX__district` VALUES ('410724', '获嘉县', '410700');
INSERT INTO `__PREFIX__district` VALUES ('410725', '原阳县', '410700');
INSERT INTO `__PREFIX__district` VALUES ('410726', '延津县', '410700');
INSERT INTO `__PREFIX__district` VALUES ('410727', '封丘县', '410700');
INSERT INTO `__PREFIX__district` VALUES ('410728', '长垣县', '410700');
INSERT INTO `__PREFIX__district` VALUES ('410781', '卫辉市', '410700');
INSERT INTO `__PREFIX__district` VALUES ('410782', '辉县市', '410700');
INSERT INTO `__PREFIX__district` VALUES ('410800', '焦作市', '410000');
INSERT INTO `__PREFIX__district` VALUES ('410801', '市辖区', '410800');
INSERT INTO `__PREFIX__district` VALUES ('410802', '解放区', '410800');
INSERT INTO `__PREFIX__district` VALUES ('410803', '中站区', '410800');
INSERT INTO `__PREFIX__district` VALUES ('410804', '马村区', '410800');
INSERT INTO `__PREFIX__district` VALUES ('410811', '山阳区', '410800');
INSERT INTO `__PREFIX__district` VALUES ('410821', '修武县', '410800');
INSERT INTO `__PREFIX__district` VALUES ('410822', '博爱县', '410800');
INSERT INTO `__PREFIX__district` VALUES ('410823', '武陟县', '410800');
INSERT INTO `__PREFIX__district` VALUES ('410825', '温县', '410800');
INSERT INTO `__PREFIX__district` VALUES ('410882', '沁阳市', '410800');
INSERT INTO `__PREFIX__district` VALUES ('410883', '孟州市', '410800');
INSERT INTO `__PREFIX__district` VALUES ('410900', '濮阳市', '410000');
INSERT INTO `__PREFIX__district` VALUES ('410901', '市辖区', '410900');
INSERT INTO `__PREFIX__district` VALUES ('410902', '华龙区', '410900');
INSERT INTO `__PREFIX__district` VALUES ('410922', '清丰县', '410900');
INSERT INTO `__PREFIX__district` VALUES ('410923', '南乐县', '410900');
INSERT INTO `__PREFIX__district` VALUES ('410926', '范县', '410900');
INSERT INTO `__PREFIX__district` VALUES ('410927', '台前县', '410900');
INSERT INTO `__PREFIX__district` VALUES ('410928', '濮阳县', '410900');
INSERT INTO `__PREFIX__district` VALUES ('411000', '许昌市', '410000');
INSERT INTO `__PREFIX__district` VALUES ('411001', '市辖区', '411000');
INSERT INTO `__PREFIX__district` VALUES ('411002', '魏都区', '411000');
INSERT INTO `__PREFIX__district` VALUES ('411023', '许昌县', '411000');
INSERT INTO `__PREFIX__district` VALUES ('411024', '鄢陵县', '411000');
INSERT INTO `__PREFIX__district` VALUES ('411025', '襄城县', '411000');
INSERT INTO `__PREFIX__district` VALUES ('411081', '禹州市', '411000');
INSERT INTO `__PREFIX__district` VALUES ('411082', '长葛市', '411000');
INSERT INTO `__PREFIX__district` VALUES ('411100', '漯河市', '410000');
INSERT INTO `__PREFIX__district` VALUES ('411101', '市辖区', '411100');
INSERT INTO `__PREFIX__district` VALUES ('411102', '源汇区', '411100');
INSERT INTO `__PREFIX__district` VALUES ('411103', '郾城区', '411100');
INSERT INTO `__PREFIX__district` VALUES ('411104', '召陵区', '411100');
INSERT INTO `__PREFIX__district` VALUES ('411121', '舞阳县', '411100');
INSERT INTO `__PREFIX__district` VALUES ('411122', '临颍县', '411100');
INSERT INTO `__PREFIX__district` VALUES ('411200', '三门峡市', '410000');
INSERT INTO `__PREFIX__district` VALUES ('411201', '市辖区', '411200');
INSERT INTO `__PREFIX__district` VALUES ('411202', '湖滨区', '411200');
INSERT INTO `__PREFIX__district` VALUES ('411221', '渑池县', '411200');
INSERT INTO `__PREFIX__district` VALUES ('411222', '陕县', '411200');
INSERT INTO `__PREFIX__district` VALUES ('411224', '卢氏县', '411200');
INSERT INTO `__PREFIX__district` VALUES ('411281', '义马市', '411200');
INSERT INTO `__PREFIX__district` VALUES ('411282', '灵宝市', '411200');
INSERT INTO `__PREFIX__district` VALUES ('411300', '南阳市', '410000');
INSERT INTO `__PREFIX__district` VALUES ('411301', '市辖区', '411300');
INSERT INTO `__PREFIX__district` VALUES ('411302', '宛城区', '411300');
INSERT INTO `__PREFIX__district` VALUES ('411303', '卧龙区', '411300');
INSERT INTO `__PREFIX__district` VALUES ('411321', '南召县', '411300');
INSERT INTO `__PREFIX__district` VALUES ('411322', '方城县', '411300');
INSERT INTO `__PREFIX__district` VALUES ('411323', '西峡县', '411300');
INSERT INTO `__PREFIX__district` VALUES ('411324', '镇平县', '411300');
INSERT INTO `__PREFIX__district` VALUES ('411325', '内乡县', '411300');
INSERT INTO `__PREFIX__district` VALUES ('411326', '淅川县', '411300');
INSERT INTO `__PREFIX__district` VALUES ('411327', '社旗县', '411300');
INSERT INTO `__PREFIX__district` VALUES ('411328', '唐河县', '411300');
INSERT INTO `__PREFIX__district` VALUES ('411329', '新野县', '411300');
INSERT INTO `__PREFIX__district` VALUES ('411330', '桐柏县', '411300');
INSERT INTO `__PREFIX__district` VALUES ('411381', '邓州市', '411300');
INSERT INTO `__PREFIX__district` VALUES ('411400', '商丘市', '410000');
INSERT INTO `__PREFIX__district` VALUES ('411401', '市辖区', '411400');
INSERT INTO `__PREFIX__district` VALUES ('411402', '梁园区', '411400');
INSERT INTO `__PREFIX__district` VALUES ('411403', '睢阳区', '411400');
INSERT INTO `__PREFIX__district` VALUES ('411421', '民权县', '411400');
INSERT INTO `__PREFIX__district` VALUES ('411422', '睢县', '411400');
INSERT INTO `__PREFIX__district` VALUES ('411423', '宁陵县', '411400');
INSERT INTO `__PREFIX__district` VALUES ('411424', '柘城县', '411400');
INSERT INTO `__PREFIX__district` VALUES ('411425', '虞城县', '411400');
INSERT INTO `__PREFIX__district` VALUES ('411426', '夏邑县', '411400');
INSERT INTO `__PREFIX__district` VALUES ('411481', '永城市', '411400');
INSERT INTO `__PREFIX__district` VALUES ('411500', '信阳市', '410000');
INSERT INTO `__PREFIX__district` VALUES ('411501', '市辖区', '411500');
INSERT INTO `__PREFIX__district` VALUES ('411502', '浉河区', '411500');
INSERT INTO `__PREFIX__district` VALUES ('411503', '平桥区', '411500');
INSERT INTO `__PREFIX__district` VALUES ('411521', '罗山县', '411500');
INSERT INTO `__PREFIX__district` VALUES ('411522', '光山县', '411500');
INSERT INTO `__PREFIX__district` VALUES ('411523', '新县', '411500');
INSERT INTO `__PREFIX__district` VALUES ('411524', '商城县', '411500');
INSERT INTO `__PREFIX__district` VALUES ('411525', '固始县', '411500');
INSERT INTO `__PREFIX__district` VALUES ('411526', '潢川县', '411500');
INSERT INTO `__PREFIX__district` VALUES ('411527', '淮滨县', '411500');
INSERT INTO `__PREFIX__district` VALUES ('411528', '息县', '411500');
INSERT INTO `__PREFIX__district` VALUES ('411600', '周口市', '410000');
INSERT INTO `__PREFIX__district` VALUES ('411601', '市辖区', '411600');
INSERT INTO `__PREFIX__district` VALUES ('411602', '川汇区', '411600');
INSERT INTO `__PREFIX__district` VALUES ('411621', '扶沟县', '411600');
INSERT INTO `__PREFIX__district` VALUES ('411622', '西华县', '411600');
INSERT INTO `__PREFIX__district` VALUES ('411623', '商水县', '411600');
INSERT INTO `__PREFIX__district` VALUES ('411624', '沈丘县', '411600');
INSERT INTO `__PREFIX__district` VALUES ('411625', '郸城县', '411600');
INSERT INTO `__PREFIX__district` VALUES ('411626', '淮阳县', '411600');
INSERT INTO `__PREFIX__district` VALUES ('411627', '太康县', '411600');
INSERT INTO `__PREFIX__district` VALUES ('411628', '鹿邑县', '411600');
INSERT INTO `__PREFIX__district` VALUES ('411681', '项城市', '411600');
INSERT INTO `__PREFIX__district` VALUES ('411700', '驻马店市', '410000');
INSERT INTO `__PREFIX__district` VALUES ('411701', '市辖区', '411700');
INSERT INTO `__PREFIX__district` VALUES ('411702', '驿城区', '411700');
INSERT INTO `__PREFIX__district` VALUES ('411721', '西平县', '411700');
INSERT INTO `__PREFIX__district` VALUES ('411722', '上蔡县', '411700');
INSERT INTO `__PREFIX__district` VALUES ('411723', '平舆县', '411700');
INSERT INTO `__PREFIX__district` VALUES ('411724', '正阳县', '411700');
INSERT INTO `__PREFIX__district` VALUES ('411725', '确山县', '411700');
INSERT INTO `__PREFIX__district` VALUES ('411726', '泌阳县', '411700');
INSERT INTO `__PREFIX__district` VALUES ('411727', '汝南县', '411700');
INSERT INTO `__PREFIX__district` VALUES ('411728', '遂平县', '411700');
INSERT INTO `__PREFIX__district` VALUES ('411729', '新蔡县', '411700');
INSERT INTO `__PREFIX__district` VALUES ('419000', '济源市', '410000');
INSERT INTO `__PREFIX__district` VALUES ('419001', '济源市', '410000');
INSERT INTO `__PREFIX__district` VALUES ('420000', '湖北省', '0');
INSERT INTO `__PREFIX__district` VALUES ('420100', '武汉市', '420000');
INSERT INTO `__PREFIX__district` VALUES ('420101', '市辖区', '420100');
INSERT INTO `__PREFIX__district` VALUES ('420102', '江岸区', '420100');
INSERT INTO `__PREFIX__district` VALUES ('420103', '江汉区', '420100');
INSERT INTO `__PREFIX__district` VALUES ('420104', '硚口区', '420100');
INSERT INTO `__PREFIX__district` VALUES ('420105', '汉阳区', '420100');
INSERT INTO `__PREFIX__district` VALUES ('420106', '武昌区', '420100');
INSERT INTO `__PREFIX__district` VALUES ('420107', '青山区', '420100');
INSERT INTO `__PREFIX__district` VALUES ('420111', '洪山区', '420100');
INSERT INTO `__PREFIX__district` VALUES ('420112', '东西湖区', '420100');
INSERT INTO `__PREFIX__district` VALUES ('420113', '汉南区', '420100');
INSERT INTO `__PREFIX__district` VALUES ('420114', '蔡甸区', '420100');
INSERT INTO `__PREFIX__district` VALUES ('420115', '江夏区', '420100');
INSERT INTO `__PREFIX__district` VALUES ('420116', '黄陂区', '420100');
INSERT INTO `__PREFIX__district` VALUES ('420117', '新洲区', '420100');
INSERT INTO `__PREFIX__district` VALUES ('420200', '黄石市', '420000');
INSERT INTO `__PREFIX__district` VALUES ('420201', '市辖区', '420200');
INSERT INTO `__PREFIX__district` VALUES ('420202', '黄石港区', '420200');
INSERT INTO `__PREFIX__district` VALUES ('420203', '西塞山区', '420200');
INSERT INTO `__PREFIX__district` VALUES ('420204', '下陆区', '420200');
INSERT INTO `__PREFIX__district` VALUES ('420205', '铁山区', '420200');
INSERT INTO `__PREFIX__district` VALUES ('420222', '阳新县', '420200');
INSERT INTO `__PREFIX__district` VALUES ('420281', '大冶市', '420200');
INSERT INTO `__PREFIX__district` VALUES ('420300', '十堰市', '420000');
INSERT INTO `__PREFIX__district` VALUES ('420301', '市辖区', '420300');
INSERT INTO `__PREFIX__district` VALUES ('420302', '茅箭区', '420300');
INSERT INTO `__PREFIX__district` VALUES ('420303', '张湾区', '420300');
INSERT INTO `__PREFIX__district` VALUES ('420304', '郧阳区', '420300');
INSERT INTO `__PREFIX__district` VALUES ('420322', '郧西县', '420300');
INSERT INTO `__PREFIX__district` VALUES ('420323', '竹山县', '420300');
INSERT INTO `__PREFIX__district` VALUES ('420324', '竹溪县', '420300');
INSERT INTO `__PREFIX__district` VALUES ('420325', '房县', '420300');
INSERT INTO `__PREFIX__district` VALUES ('420381', '丹江口市', '420300');
INSERT INTO `__PREFIX__district` VALUES ('420500', '宜昌市', '420000');
INSERT INTO `__PREFIX__district` VALUES ('420501', '市辖区', '420500');
INSERT INTO `__PREFIX__district` VALUES ('420502', '西陵区', '420500');
INSERT INTO `__PREFIX__district` VALUES ('420503', '伍家岗区', '420500');
INSERT INTO `__PREFIX__district` VALUES ('420504', '点军区', '420500');
INSERT INTO `__PREFIX__district` VALUES ('420505', '猇亭区', '420500');
INSERT INTO `__PREFIX__district` VALUES ('420506', '夷陵区', '420500');
INSERT INTO `__PREFIX__district` VALUES ('420525', '远安县', '420500');
INSERT INTO `__PREFIX__district` VALUES ('420526', '兴山县', '420500');
INSERT INTO `__PREFIX__district` VALUES ('420527', '秭归县', '420500');
INSERT INTO `__PREFIX__district` VALUES ('420528', '长阳土家族自治县', '420500');
INSERT INTO `__PREFIX__district` VALUES ('420529', '五峰土家族自治县', '420500');
INSERT INTO `__PREFIX__district` VALUES ('420581', '宜都市', '420500');
INSERT INTO `__PREFIX__district` VALUES ('420582', '当阳市', '420500');
INSERT INTO `__PREFIX__district` VALUES ('420583', '枝江市', '420500');
INSERT INTO `__PREFIX__district` VALUES ('420600', '襄阳市', '420000');
INSERT INTO `__PREFIX__district` VALUES ('420601', '市辖区', '420600');
INSERT INTO `__PREFIX__district` VALUES ('420602', '襄城区', '420600');
INSERT INTO `__PREFIX__district` VALUES ('420606', '樊城区', '420600');
INSERT INTO `__PREFIX__district` VALUES ('420607', '襄州区', '420600');
INSERT INTO `__PREFIX__district` VALUES ('420624', '南漳县', '420600');
INSERT INTO `__PREFIX__district` VALUES ('420625', '谷城县', '420600');
INSERT INTO `__PREFIX__district` VALUES ('420626', '保康县', '420600');
INSERT INTO `__PREFIX__district` VALUES ('420682', '老河口市', '420600');
INSERT INTO `__PREFIX__district` VALUES ('420683', '枣阳市', '420600');
INSERT INTO `__PREFIX__district` VALUES ('420684', '宜城市', '420600');
INSERT INTO `__PREFIX__district` VALUES ('420700', '鄂州市', '420000');
INSERT INTO `__PREFIX__district` VALUES ('420701', '市辖区', '420700');
INSERT INTO `__PREFIX__district` VALUES ('420702', '梁子湖区', '420700');
INSERT INTO `__PREFIX__district` VALUES ('420703', '华容区', '420700');
INSERT INTO `__PREFIX__district` VALUES ('420704', '鄂城区', '420700');
INSERT INTO `__PREFIX__district` VALUES ('420800', '荆门市', '420000');
INSERT INTO `__PREFIX__district` VALUES ('420801', '市辖区', '420800');
INSERT INTO `__PREFIX__district` VALUES ('420802', '东宝区', '420800');
INSERT INTO `__PREFIX__district` VALUES ('420804', '掇刀区', '420800');
INSERT INTO `__PREFIX__district` VALUES ('420821', '京山县', '420800');
INSERT INTO `__PREFIX__district` VALUES ('420822', '沙洋县', '420800');
INSERT INTO `__PREFIX__district` VALUES ('420881', '钟祥市', '420800');
INSERT INTO `__PREFIX__district` VALUES ('420900', '孝感市', '420000');
INSERT INTO `__PREFIX__district` VALUES ('420901', '市辖区', '420900');
INSERT INTO `__PREFIX__district` VALUES ('420902', '孝南区', '420900');
INSERT INTO `__PREFIX__district` VALUES ('420921', '孝昌县', '420900');
INSERT INTO `__PREFIX__district` VALUES ('420922', '大悟县', '420900');
INSERT INTO `__PREFIX__district` VALUES ('420923', '云梦县', '420900');
INSERT INTO `__PREFIX__district` VALUES ('420981', '应城市', '420900');
INSERT INTO `__PREFIX__district` VALUES ('420982', '安陆市', '420900');
INSERT INTO `__PREFIX__district` VALUES ('420984', '汉川市', '420900');
INSERT INTO `__PREFIX__district` VALUES ('421000', '荆州市', '420000');
INSERT INTO `__PREFIX__district` VALUES ('421001', '市辖区', '421000');
INSERT INTO `__PREFIX__district` VALUES ('421002', '沙市区', '421000');
INSERT INTO `__PREFIX__district` VALUES ('421003', '荆州区', '421000');
INSERT INTO `__PREFIX__district` VALUES ('421022', '公安县', '421000');
INSERT INTO `__PREFIX__district` VALUES ('421023', '监利县', '421000');
INSERT INTO `__PREFIX__district` VALUES ('421024', '江陵县', '421000');
INSERT INTO `__PREFIX__district` VALUES ('421081', '石首市', '421000');
INSERT INTO `__PREFIX__district` VALUES ('421083', '洪湖市', '421000');
INSERT INTO `__PREFIX__district` VALUES ('421087', '松滋市', '421000');
INSERT INTO `__PREFIX__district` VALUES ('421100', '黄冈市', '420000');
INSERT INTO `__PREFIX__district` VALUES ('421101', '市辖区', '421100');
INSERT INTO `__PREFIX__district` VALUES ('421102', '黄州区', '421100');
INSERT INTO `__PREFIX__district` VALUES ('421121', '团风县', '421100');
INSERT INTO `__PREFIX__district` VALUES ('421122', '红安县', '421100');
INSERT INTO `__PREFIX__district` VALUES ('421123', '罗田县', '421100');
INSERT INTO `__PREFIX__district` VALUES ('421124', '英山县', '421100');
INSERT INTO `__PREFIX__district` VALUES ('421125', '浠水县', '421100');
INSERT INTO `__PREFIX__district` VALUES ('421126', '蕲春县', '421100');
INSERT INTO `__PREFIX__district` VALUES ('421127', '黄梅县', '421100');
INSERT INTO `__PREFIX__district` VALUES ('421181', '麻城市', '421100');
INSERT INTO `__PREFIX__district` VALUES ('421182', '武穴市', '421100');
INSERT INTO `__PREFIX__district` VALUES ('421200', '咸宁市', '420000');
INSERT INTO `__PREFIX__district` VALUES ('421201', '市辖区', '421200');
INSERT INTO `__PREFIX__district` VALUES ('421202', '咸安区', '421200');
INSERT INTO `__PREFIX__district` VALUES ('421221', '嘉鱼县', '421200');
INSERT INTO `__PREFIX__district` VALUES ('421222', '通城县', '421200');
INSERT INTO `__PREFIX__district` VALUES ('421223', '崇阳县', '421200');
INSERT INTO `__PREFIX__district` VALUES ('421224', '通山县', '421200');
INSERT INTO `__PREFIX__district` VALUES ('421281', '赤壁市', '421200');
INSERT INTO `__PREFIX__district` VALUES ('421300', '随州市', '420000');
INSERT INTO `__PREFIX__district` VALUES ('421301', '市辖区', '421300');
INSERT INTO `__PREFIX__district` VALUES ('421303', '曾都区', '421300');
INSERT INTO `__PREFIX__district` VALUES ('421321', '随县', '421300');
INSERT INTO `__PREFIX__district` VALUES ('421381', '广水市', '421300');
INSERT INTO `__PREFIX__district` VALUES ('422800', '恩施土家族苗族自治州', '420000');
INSERT INTO `__PREFIX__district` VALUES ('422801', '恩施市', '422800');
INSERT INTO `__PREFIX__district` VALUES ('422802', '利川市', '422800');
INSERT INTO `__PREFIX__district` VALUES ('422822', '建始县', '422800');
INSERT INTO `__PREFIX__district` VALUES ('422823', '巴东县', '422800');
INSERT INTO `__PREFIX__district` VALUES ('422825', '宣恩县', '422800');
INSERT INTO `__PREFIX__district` VALUES ('422826', '咸丰县', '422800');
INSERT INTO `__PREFIX__district` VALUES ('422827', '来凤县', '422800');
INSERT INTO `__PREFIX__district` VALUES ('422828', '鹤峰县', '422800');
INSERT INTO `__PREFIX__district` VALUES ('429004', '仙桃市', '420000');
INSERT INTO `__PREFIX__district` VALUES ('429005', '潜江市', '420000');
INSERT INTO `__PREFIX__district` VALUES ('429006', '天门市', '420000');
INSERT INTO `__PREFIX__district` VALUES ('429021', '神农架林区', '420000');
INSERT INTO `__PREFIX__district` VALUES ('430000', '湖南省', '0');
INSERT INTO `__PREFIX__district` VALUES ('430100', '长沙市', '430000');
INSERT INTO `__PREFIX__district` VALUES ('430101', '市辖区', '430100');
INSERT INTO `__PREFIX__district` VALUES ('430102', '芙蓉区', '430100');
INSERT INTO `__PREFIX__district` VALUES ('430103', '天心区', '430100');
INSERT INTO `__PREFIX__district` VALUES ('430104', '岳麓区', '430100');
INSERT INTO `__PREFIX__district` VALUES ('430105', '开福区', '430100');
INSERT INTO `__PREFIX__district` VALUES ('430111', '雨花区', '430100');
INSERT INTO `__PREFIX__district` VALUES ('430112', '望城区', '430100');
INSERT INTO `__PREFIX__district` VALUES ('430121', '长沙县', '430100');
INSERT INTO `__PREFIX__district` VALUES ('430124', '宁乡县', '430100');
INSERT INTO `__PREFIX__district` VALUES ('430181', '浏阳市', '430100');
INSERT INTO `__PREFIX__district` VALUES ('430200', '株洲市', '430000');
INSERT INTO `__PREFIX__district` VALUES ('430201', '市辖区', '430200');
INSERT INTO `__PREFIX__district` VALUES ('430202', '荷塘区', '430200');
INSERT INTO `__PREFIX__district` VALUES ('430203', '芦淞区', '430200');
INSERT INTO `__PREFIX__district` VALUES ('430204', '石峰区', '430200');
INSERT INTO `__PREFIX__district` VALUES ('430211', '天元区', '430200');
INSERT INTO `__PREFIX__district` VALUES ('430221', '株洲县', '430200');
INSERT INTO `__PREFIX__district` VALUES ('430223', '攸县', '430200');
INSERT INTO `__PREFIX__district` VALUES ('430224', '茶陵县', '430200');
INSERT INTO `__PREFIX__district` VALUES ('430225', '炎陵县', '430200');
INSERT INTO `__PREFIX__district` VALUES ('430281', '醴陵市', '430200');
INSERT INTO `__PREFIX__district` VALUES ('430300', '湘潭市', '430000');
INSERT INTO `__PREFIX__district` VALUES ('430301', '市辖区', '430300');
INSERT INTO `__PREFIX__district` VALUES ('430302', '雨湖区', '430300');
INSERT INTO `__PREFIX__district` VALUES ('430304', '岳塘区', '430300');
INSERT INTO `__PREFIX__district` VALUES ('430321', '湘潭县', '430300');
INSERT INTO `__PREFIX__district` VALUES ('430381', '湘乡市', '430300');
INSERT INTO `__PREFIX__district` VALUES ('430382', '韶山市', '430300');
INSERT INTO `__PREFIX__district` VALUES ('430400', '衡阳市', '430000');
INSERT INTO `__PREFIX__district` VALUES ('430401', '市辖区', '430400');
INSERT INTO `__PREFIX__district` VALUES ('430405', '珠晖区', '430400');
INSERT INTO `__PREFIX__district` VALUES ('430406', '雁峰区', '430400');
INSERT INTO `__PREFIX__district` VALUES ('430407', '石鼓区', '430400');
INSERT INTO `__PREFIX__district` VALUES ('430408', '蒸湘区', '430400');
INSERT INTO `__PREFIX__district` VALUES ('430412', '南岳区', '430400');
INSERT INTO `__PREFIX__district` VALUES ('430421', '衡阳县', '430400');
INSERT INTO `__PREFIX__district` VALUES ('430422', '衡南县', '430400');
INSERT INTO `__PREFIX__district` VALUES ('430423', '衡山县', '430400');
INSERT INTO `__PREFIX__district` VALUES ('430424', '衡东县', '430400');
INSERT INTO `__PREFIX__district` VALUES ('430426', '祁东县', '430400');
INSERT INTO `__PREFIX__district` VALUES ('430481', '耒阳市', '430400');
INSERT INTO `__PREFIX__district` VALUES ('430482', '常宁市', '430400');
INSERT INTO `__PREFIX__district` VALUES ('430500', '邵阳市', '430000');
INSERT INTO `__PREFIX__district` VALUES ('430501', '市辖区', '430500');
INSERT INTO `__PREFIX__district` VALUES ('430502', '双清区', '430500');
INSERT INTO `__PREFIX__district` VALUES ('430503', '大祥区', '430500');
INSERT INTO `__PREFIX__district` VALUES ('430511', '北塔区', '430500');
INSERT INTO `__PREFIX__district` VALUES ('430521', '邵东县', '430500');
INSERT INTO `__PREFIX__district` VALUES ('430522', '新邵县', '430500');
INSERT INTO `__PREFIX__district` VALUES ('430523', '邵阳县', '430500');
INSERT INTO `__PREFIX__district` VALUES ('430524', '隆回县', '430500');
INSERT INTO `__PREFIX__district` VALUES ('430525', '洞口县', '430500');
INSERT INTO `__PREFIX__district` VALUES ('430527', '绥宁县', '430500');
INSERT INTO `__PREFIX__district` VALUES ('430528', '新宁县', '430500');
INSERT INTO `__PREFIX__district` VALUES ('430529', '城步苗族自治县', '430500');
INSERT INTO `__PREFIX__district` VALUES ('430581', '武冈市', '430500');
INSERT INTO `__PREFIX__district` VALUES ('430600', '岳阳市', '430000');
INSERT INTO `__PREFIX__district` VALUES ('430601', '市辖区', '430600');
INSERT INTO `__PREFIX__district` VALUES ('430602', '岳阳楼区', '430600');
INSERT INTO `__PREFIX__district` VALUES ('430603', '云溪区', '430600');
INSERT INTO `__PREFIX__district` VALUES ('430611', '君山区', '430600');
INSERT INTO `__PREFIX__district` VALUES ('430621', '岳阳县', '430600');
INSERT INTO `__PREFIX__district` VALUES ('430623', '华容县', '430600');
INSERT INTO `__PREFIX__district` VALUES ('430624', '湘阴县', '430600');
INSERT INTO `__PREFIX__district` VALUES ('430626', '平江县', '430600');
INSERT INTO `__PREFIX__district` VALUES ('430681', '汨罗市', '430600');
INSERT INTO `__PREFIX__district` VALUES ('430682', '临湘市', '430600');
INSERT INTO `__PREFIX__district` VALUES ('430700', '常德市', '430000');
INSERT INTO `__PREFIX__district` VALUES ('430701', '市辖区', '430700');
INSERT INTO `__PREFIX__district` VALUES ('430702', '武陵区', '430700');
INSERT INTO `__PREFIX__district` VALUES ('430703', '鼎城区', '430700');
INSERT INTO `__PREFIX__district` VALUES ('430721', '安乡县', '430700');
INSERT INTO `__PREFIX__district` VALUES ('430722', '汉寿县', '430700');
INSERT INTO `__PREFIX__district` VALUES ('430723', '澧县', '430700');
INSERT INTO `__PREFIX__district` VALUES ('430724', '临澧县', '430700');
INSERT INTO `__PREFIX__district` VALUES ('430725', '桃源县', '430700');
INSERT INTO `__PREFIX__district` VALUES ('430726', '石门县', '430700');
INSERT INTO `__PREFIX__district` VALUES ('430781', '津市市', '430700');
INSERT INTO `__PREFIX__district` VALUES ('430800', '张家界市', '430000');
INSERT INTO `__PREFIX__district` VALUES ('430801', '市辖区', '430800');
INSERT INTO `__PREFIX__district` VALUES ('430802', '永定区', '430800');
INSERT INTO `__PREFIX__district` VALUES ('430811', '武陵源区', '430800');
INSERT INTO `__PREFIX__district` VALUES ('430821', '慈利县', '430800');
INSERT INTO `__PREFIX__district` VALUES ('430822', '桑植县', '430800');
INSERT INTO `__PREFIX__district` VALUES ('430900', '益阳市', '430000');
INSERT INTO `__PREFIX__district` VALUES ('430901', '市辖区', '430900');
INSERT INTO `__PREFIX__district` VALUES ('430902', '资阳区', '430900');
INSERT INTO `__PREFIX__district` VALUES ('430903', '赫山区', '430900');
INSERT INTO `__PREFIX__district` VALUES ('430921', '南县', '430900');
INSERT INTO `__PREFIX__district` VALUES ('430922', '桃江县', '430900');
INSERT INTO `__PREFIX__district` VALUES ('430923', '安化县', '430900');
INSERT INTO `__PREFIX__district` VALUES ('430981', '沅江市', '430900');
INSERT INTO `__PREFIX__district` VALUES ('431000', '郴州市', '430000');
INSERT INTO `__PREFIX__district` VALUES ('431001', '市辖区', '431000');
INSERT INTO `__PREFIX__district` VALUES ('431002', '北湖区', '431000');
INSERT INTO `__PREFIX__district` VALUES ('431003', '苏仙区', '431000');
INSERT INTO `__PREFIX__district` VALUES ('431021', '桂阳县', '431000');
INSERT INTO `__PREFIX__district` VALUES ('431022', '宜章县', '431000');
INSERT INTO `__PREFIX__district` VALUES ('431023', '永兴县', '431000');
INSERT INTO `__PREFIX__district` VALUES ('431024', '嘉禾县', '431000');
INSERT INTO `__PREFIX__district` VALUES ('431025', '临武县', '431000');
INSERT INTO `__PREFIX__district` VALUES ('431026', '汝城县', '431000');
INSERT INTO `__PREFIX__district` VALUES ('431027', '桂东县', '431000');
INSERT INTO `__PREFIX__district` VALUES ('431028', '安仁县', '431000');
INSERT INTO `__PREFIX__district` VALUES ('431081', '资兴市', '431000');
INSERT INTO `__PREFIX__district` VALUES ('431100', '永州市', '430000');
INSERT INTO `__PREFIX__district` VALUES ('431101', '市辖区', '431100');
INSERT INTO `__PREFIX__district` VALUES ('431102', '零陵区', '431100');
INSERT INTO `__PREFIX__district` VALUES ('431103', '冷水滩区', '431100');
INSERT INTO `__PREFIX__district` VALUES ('431121', '祁阳县', '431100');
INSERT INTO `__PREFIX__district` VALUES ('431122', '东安县', '431100');
INSERT INTO `__PREFIX__district` VALUES ('431123', '双牌县', '431100');
INSERT INTO `__PREFIX__district` VALUES ('431124', '道县', '431100');
INSERT INTO `__PREFIX__district` VALUES ('431125', '江永县', '431100');
INSERT INTO `__PREFIX__district` VALUES ('431126', '宁远县', '431100');
INSERT INTO `__PREFIX__district` VALUES ('431127', '蓝山县', '431100');
INSERT INTO `__PREFIX__district` VALUES ('431128', '新田县', '431100');
INSERT INTO `__PREFIX__district` VALUES ('431129', '江华瑶族自治县', '431100');
INSERT INTO `__PREFIX__district` VALUES ('431200', '怀化市', '430000');
INSERT INTO `__PREFIX__district` VALUES ('431201', '市辖区', '431200');
INSERT INTO `__PREFIX__district` VALUES ('431202', '鹤城区', '431200');
INSERT INTO `__PREFIX__district` VALUES ('431221', '中方县', '431200');
INSERT INTO `__PREFIX__district` VALUES ('431222', '沅陵县', '431200');
INSERT INTO `__PREFIX__district` VALUES ('431223', '辰溪县', '431200');
INSERT INTO `__PREFIX__district` VALUES ('431224', '溆浦县', '431200');
INSERT INTO `__PREFIX__district` VALUES ('431225', '会同县', '431200');
INSERT INTO `__PREFIX__district` VALUES ('431226', '麻阳苗族自治县', '431200');
INSERT INTO `__PREFIX__district` VALUES ('431227', '新晃侗族自治县', '431200');
INSERT INTO `__PREFIX__district` VALUES ('431228', '芷江侗族自治县', '431200');
INSERT INTO `__PREFIX__district` VALUES ('431229', '靖州苗族侗族自治县', '431200');
INSERT INTO `__PREFIX__district` VALUES ('431230', '通道侗族自治县', '431200');
INSERT INTO `__PREFIX__district` VALUES ('431281', '洪江市', '431200');
INSERT INTO `__PREFIX__district` VALUES ('431300', '娄底市', '430000');
INSERT INTO `__PREFIX__district` VALUES ('431301', '市辖区', '431300');
INSERT INTO `__PREFIX__district` VALUES ('431302', '娄星区', '431300');
INSERT INTO `__PREFIX__district` VALUES ('431321', '双峰县', '431300');
INSERT INTO `__PREFIX__district` VALUES ('431322', '新化县', '431300');
INSERT INTO `__PREFIX__district` VALUES ('431381', '冷水江市', '431300');
INSERT INTO `__PREFIX__district` VALUES ('431382', '涟源市', '431300');
INSERT INTO `__PREFIX__district` VALUES ('433100', '湘西土家族苗族自治州', '430000');
INSERT INTO `__PREFIX__district` VALUES ('433101', '吉首市', '433100');
INSERT INTO `__PREFIX__district` VALUES ('433122', '泸溪县', '433100');
INSERT INTO `__PREFIX__district` VALUES ('433123', '凤凰县', '433100');
INSERT INTO `__PREFIX__district` VALUES ('433124', '花垣县', '433100');
INSERT INTO `__PREFIX__district` VALUES ('433125', '保靖县', '433100');
INSERT INTO `__PREFIX__district` VALUES ('433126', '古丈县', '433100');
INSERT INTO `__PREFIX__district` VALUES ('433127', '永顺县', '433100');
INSERT INTO `__PREFIX__district` VALUES ('433130', '龙山县', '433100');
INSERT INTO `__PREFIX__district` VALUES ('440000', '广东省', '0');
INSERT INTO `__PREFIX__district` VALUES ('440100', '广州市', '440000');
INSERT INTO `__PREFIX__district` VALUES ('440101', '市辖区', '440100');
INSERT INTO `__PREFIX__district` VALUES ('440103', '荔湾区', '440100');
INSERT INTO `__PREFIX__district` VALUES ('440104', '越秀区', '440100');
INSERT INTO `__PREFIX__district` VALUES ('440105', '海珠区', '440100');
INSERT INTO `__PREFIX__district` VALUES ('440106', '天河区', '440100');
INSERT INTO `__PREFIX__district` VALUES ('440111', '白云区', '440100');
INSERT INTO `__PREFIX__district` VALUES ('440112', '黄埔区', '440100');
INSERT INTO `__PREFIX__district` VALUES ('440113', '番禺区', '440100');
INSERT INTO `__PREFIX__district` VALUES ('440114', '花都区', '440100');
INSERT INTO `__PREFIX__district` VALUES ('440115', '南沙区', '440100');
INSERT INTO `__PREFIX__district` VALUES ('440117', '从化区', '440100');
INSERT INTO `__PREFIX__district` VALUES ('440118', '增城区', '440100');
INSERT INTO `__PREFIX__district` VALUES ('440200', '韶关市', '440000');
INSERT INTO `__PREFIX__district` VALUES ('440201', '市辖区', '440200');
INSERT INTO `__PREFIX__district` VALUES ('440203', '武江区', '440200');
INSERT INTO `__PREFIX__district` VALUES ('440204', '浈江区', '440200');
INSERT INTO `__PREFIX__district` VALUES ('440205', '曲江区', '440200');
INSERT INTO `__PREFIX__district` VALUES ('440222', '始兴县', '440200');
INSERT INTO `__PREFIX__district` VALUES ('440224', '仁化县', '440200');
INSERT INTO `__PREFIX__district` VALUES ('440229', '翁源县', '440200');
INSERT INTO `__PREFIX__district` VALUES ('440232', '乳源瑶族自治县', '440200');
INSERT INTO `__PREFIX__district` VALUES ('440233', '新丰县', '440200');
INSERT INTO `__PREFIX__district` VALUES ('440281', '乐昌市', '440200');
INSERT INTO `__PREFIX__district` VALUES ('440282', '南雄市', '440200');
INSERT INTO `__PREFIX__district` VALUES ('440300', '深圳市', '440000');
INSERT INTO `__PREFIX__district` VALUES ('440301', '市辖区', '440300');
INSERT INTO `__PREFIX__district` VALUES ('440303', '罗湖区', '440300');
INSERT INTO `__PREFIX__district` VALUES ('440304', '福田区', '440300');
INSERT INTO `__PREFIX__district` VALUES ('440305', '南山区', '440300');
INSERT INTO `__PREFIX__district` VALUES ('440306', '宝安区', '440300');
INSERT INTO `__PREFIX__district` VALUES ('440307', '龙岗区', '440300');
INSERT INTO `__PREFIX__district` VALUES ('440308', '盐田区', '440300');
INSERT INTO `__PREFIX__district` VALUES ('440400', '珠海市', '440000');
INSERT INTO `__PREFIX__district` VALUES ('440401', '市辖区', '440400');
INSERT INTO `__PREFIX__district` VALUES ('440402', '香洲区', '440400');
INSERT INTO `__PREFIX__district` VALUES ('440403', '斗门区', '440400');
INSERT INTO `__PREFIX__district` VALUES ('440404', '金湾区', '440400');
INSERT INTO `__PREFIX__district` VALUES ('440500', '汕头市', '440000');
INSERT INTO `__PREFIX__district` VALUES ('440501', '市辖区', '440500');
INSERT INTO `__PREFIX__district` VALUES ('440507', '龙湖区', '440500');
INSERT INTO `__PREFIX__district` VALUES ('440511', '金平区', '440500');
INSERT INTO `__PREFIX__district` VALUES ('440512', '濠江区', '440500');
INSERT INTO `__PREFIX__district` VALUES ('440513', '潮阳区', '440500');
INSERT INTO `__PREFIX__district` VALUES ('440514', '潮南区', '440500');
INSERT INTO `__PREFIX__district` VALUES ('440515', '澄海区', '440500');
INSERT INTO `__PREFIX__district` VALUES ('440523', '南澳县', '440500');
INSERT INTO `__PREFIX__district` VALUES ('440600', '佛山市', '440000');
INSERT INTO `__PREFIX__district` VALUES ('440601', '市辖区', '440600');
INSERT INTO `__PREFIX__district` VALUES ('440604', '禅城区', '440600');
INSERT INTO `__PREFIX__district` VALUES ('440605', '南海区', '440600');
INSERT INTO `__PREFIX__district` VALUES ('440606', '顺德区', '440600');
INSERT INTO `__PREFIX__district` VALUES ('440607', '三水区', '440600');
INSERT INTO `__PREFIX__district` VALUES ('440608', '高明区', '440600');
INSERT INTO `__PREFIX__district` VALUES ('440700', '江门市', '440000');
INSERT INTO `__PREFIX__district` VALUES ('440701', '市辖区', '440700');
INSERT INTO `__PREFIX__district` VALUES ('440703', '蓬江区', '440700');
INSERT INTO `__PREFIX__district` VALUES ('440704', '江海区', '440700');
INSERT INTO `__PREFIX__district` VALUES ('440705', '新会区', '440700');
INSERT INTO `__PREFIX__district` VALUES ('440781', '台山市', '440700');
INSERT INTO `__PREFIX__district` VALUES ('440783', '开平市', '440700');
INSERT INTO `__PREFIX__district` VALUES ('440784', '鹤山市', '440700');
INSERT INTO `__PREFIX__district` VALUES ('440785', '恩平市', '440700');
INSERT INTO `__PREFIX__district` VALUES ('440800', '湛江市', '440000');
INSERT INTO `__PREFIX__district` VALUES ('440801', '市辖区', '440800');
INSERT INTO `__PREFIX__district` VALUES ('440802', '赤坎区', '440800');
INSERT INTO `__PREFIX__district` VALUES ('440803', '霞山区', '440800');
INSERT INTO `__PREFIX__district` VALUES ('440804', '坡头区', '440800');
INSERT INTO `__PREFIX__district` VALUES ('440811', '麻章区', '440800');
INSERT INTO `__PREFIX__district` VALUES ('440823', '遂溪县', '440800');
INSERT INTO `__PREFIX__district` VALUES ('440825', '徐闻县', '440800');
INSERT INTO `__PREFIX__district` VALUES ('440881', '廉江市', '440800');
INSERT INTO `__PREFIX__district` VALUES ('440882', '雷州市', '440800');
INSERT INTO `__PREFIX__district` VALUES ('440883', '吴川市', '440800');
INSERT INTO `__PREFIX__district` VALUES ('440900', '茂名市', '440000');
INSERT INTO `__PREFIX__district` VALUES ('440901', '市辖区', '440900');
INSERT INTO `__PREFIX__district` VALUES ('440902', '茂南区', '440900');
INSERT INTO `__PREFIX__district` VALUES ('440904', '电白区', '440900');
INSERT INTO `__PREFIX__district` VALUES ('440981', '高州市', '440900');
INSERT INTO `__PREFIX__district` VALUES ('440982', '化州市', '440900');
INSERT INTO `__PREFIX__district` VALUES ('440983', '信宜市', '440900');
INSERT INTO `__PREFIX__district` VALUES ('441200', '肇庆市', '440000');
INSERT INTO `__PREFIX__district` VALUES ('441201', '市辖区', '441200');
INSERT INTO `__PREFIX__district` VALUES ('441202', '端州区', '441200');
INSERT INTO `__PREFIX__district` VALUES ('441203', '鼎湖区', '441200');
INSERT INTO `__PREFIX__district` VALUES ('441223', '广宁县', '441200');
INSERT INTO `__PREFIX__district` VALUES ('441224', '怀集县', '441200');
INSERT INTO `__PREFIX__district` VALUES ('441225', '封开县', '441200');
INSERT INTO `__PREFIX__district` VALUES ('441226', '德庆县', '441200');
INSERT INTO `__PREFIX__district` VALUES ('441283', '高要市', '441200');
INSERT INTO `__PREFIX__district` VALUES ('441284', '四会市', '441200');
INSERT INTO `__PREFIX__district` VALUES ('441300', '惠州市', '440000');
INSERT INTO `__PREFIX__district` VALUES ('441301', '市辖区', '441300');
INSERT INTO `__PREFIX__district` VALUES ('441302', '惠城区', '441300');
INSERT INTO `__PREFIX__district` VALUES ('441303', '惠阳区', '441300');
INSERT INTO `__PREFIX__district` VALUES ('441322', '博罗县', '441300');
INSERT INTO `__PREFIX__district` VALUES ('441323', '惠东县', '441300');
INSERT INTO `__PREFIX__district` VALUES ('441324', '龙门县', '441300');
INSERT INTO `__PREFIX__district` VALUES ('441400', '梅州市', '440000');
INSERT INTO `__PREFIX__district` VALUES ('441401', '市辖区', '441400');
INSERT INTO `__PREFIX__district` VALUES ('441402', '梅江区', '441400');
INSERT INTO `__PREFIX__district` VALUES ('441403', '梅县区', '441400');
INSERT INTO `__PREFIX__district` VALUES ('441422', '大埔县', '441400');
INSERT INTO `__PREFIX__district` VALUES ('441423', '丰顺县', '441400');
INSERT INTO `__PREFIX__district` VALUES ('441424', '五华县', '441400');
INSERT INTO `__PREFIX__district` VALUES ('441426', '平远县', '441400');
INSERT INTO `__PREFIX__district` VALUES ('441427', '蕉岭县', '441400');
INSERT INTO `__PREFIX__district` VALUES ('441481', '兴宁市', '441400');
INSERT INTO `__PREFIX__district` VALUES ('441500', '汕尾市', '440000');
INSERT INTO `__PREFIX__district` VALUES ('441501', '市辖区', '441500');
INSERT INTO `__PREFIX__district` VALUES ('441502', '城区', '441500');
INSERT INTO `__PREFIX__district` VALUES ('441521', '海丰县', '441500');
INSERT INTO `__PREFIX__district` VALUES ('441523', '陆河县', '441500');
INSERT INTO `__PREFIX__district` VALUES ('441581', '陆丰市', '441500');
INSERT INTO `__PREFIX__district` VALUES ('441600', '河源市', '440000');
INSERT INTO `__PREFIX__district` VALUES ('441601', '市辖区', '441600');
INSERT INTO `__PREFIX__district` VALUES ('441602', '源城区', '441600');
INSERT INTO `__PREFIX__district` VALUES ('441621', '紫金县', '441600');
INSERT INTO `__PREFIX__district` VALUES ('441622', '龙川县', '441600');
INSERT INTO `__PREFIX__district` VALUES ('441623', '连平县', '441600');
INSERT INTO `__PREFIX__district` VALUES ('441624', '和平县', '441600');
INSERT INTO `__PREFIX__district` VALUES ('441625', '东源县', '441600');
INSERT INTO `__PREFIX__district` VALUES ('441700', '阳江市', '440000');
INSERT INTO `__PREFIX__district` VALUES ('441701', '市辖区', '441700');
INSERT INTO `__PREFIX__district` VALUES ('441702', '江城区', '441700');
INSERT INTO `__PREFIX__district` VALUES ('441704', '阳东区', '441700');
INSERT INTO `__PREFIX__district` VALUES ('441721', '阳西县', '441700');
INSERT INTO `__PREFIX__district` VALUES ('441781', '阳春市', '441700');
INSERT INTO `__PREFIX__district` VALUES ('441800', '清远市', '440000');
INSERT INTO `__PREFIX__district` VALUES ('441801', '市辖区', '441800');
INSERT INTO `__PREFIX__district` VALUES ('441802', '清城区', '441800');
INSERT INTO `__PREFIX__district` VALUES ('441803', '清新区', '441800');
INSERT INTO `__PREFIX__district` VALUES ('441821', '佛冈县', '441800');
INSERT INTO `__PREFIX__district` VALUES ('441823', '阳山县', '441800');
INSERT INTO `__PREFIX__district` VALUES ('441825', '连山壮族瑶族自治县', '441800');
INSERT INTO `__PREFIX__district` VALUES ('441826', '连南瑶族自治县', '441800');
INSERT INTO `__PREFIX__district` VALUES ('441881', '英德市', '441800');
INSERT INTO `__PREFIX__district` VALUES ('441882', '连州市', '441800');
INSERT INTO `__PREFIX__district` VALUES ('441900', '东莞市', '440000');
INSERT INTO `__PREFIX__district` VALUES ('442000', '中山市', '440000');
INSERT INTO `__PREFIX__district` VALUES ('445100', '潮州市', '440000');
INSERT INTO `__PREFIX__district` VALUES ('445101', '市辖区', '445100');
INSERT INTO `__PREFIX__district` VALUES ('445102', '湘桥区', '445100');
INSERT INTO `__PREFIX__district` VALUES ('445103', '潮安区', '445100');
INSERT INTO `__PREFIX__district` VALUES ('445122', '饶平县', '445100');
INSERT INTO `__PREFIX__district` VALUES ('445200', '揭阳市', '440000');
INSERT INTO `__PREFIX__district` VALUES ('445201', '市辖区', '445200');
INSERT INTO `__PREFIX__district` VALUES ('445202', '榕城区', '445200');
INSERT INTO `__PREFIX__district` VALUES ('445203', '揭东区', '445200');
INSERT INTO `__PREFIX__district` VALUES ('445222', '揭西县', '445200');
INSERT INTO `__PREFIX__district` VALUES ('445224', '惠来县', '445200');
INSERT INTO `__PREFIX__district` VALUES ('445281', '普宁市', '445200');
INSERT INTO `__PREFIX__district` VALUES ('445300', '云浮市', '440000');
INSERT INTO `__PREFIX__district` VALUES ('445301', '市辖区', '445300');
INSERT INTO `__PREFIX__district` VALUES ('445302', '云城区', '445300');
INSERT INTO `__PREFIX__district` VALUES ('445303', '云安区', '445300');
INSERT INTO `__PREFIX__district` VALUES ('445321', '新兴县', '445300');
INSERT INTO `__PREFIX__district` VALUES ('445322', '郁南县', '445300');
INSERT INTO `__PREFIX__district` VALUES ('445381', '罗定市', '445300');
INSERT INTO `__PREFIX__district` VALUES ('450000', '广西壮族自治区', '0');
INSERT INTO `__PREFIX__district` VALUES ('450100', '南宁市', '450000');
INSERT INTO `__PREFIX__district` VALUES ('450101', '市辖区', '450100');
INSERT INTO `__PREFIX__district` VALUES ('450102', '兴宁区', '450100');
INSERT INTO `__PREFIX__district` VALUES ('450103', '青秀区', '450100');
INSERT INTO `__PREFIX__district` VALUES ('450105', '江南区', '450100');
INSERT INTO `__PREFIX__district` VALUES ('450107', '西乡塘区', '450100');
INSERT INTO `__PREFIX__district` VALUES ('450108', '良庆区', '450100');
INSERT INTO `__PREFIX__district` VALUES ('450109', '邕宁区', '450100');
INSERT INTO `__PREFIX__district` VALUES ('450122', '武鸣县', '450100');
INSERT INTO `__PREFIX__district` VALUES ('450123', '隆安县', '450100');
INSERT INTO `__PREFIX__district` VALUES ('450124', '马山县', '450100');
INSERT INTO `__PREFIX__district` VALUES ('450125', '上林县', '450100');
INSERT INTO `__PREFIX__district` VALUES ('450126', '宾阳县', '450100');
INSERT INTO `__PREFIX__district` VALUES ('450127', '横县', '450100');
INSERT INTO `__PREFIX__district` VALUES ('450200', '柳州市', '450000');
INSERT INTO `__PREFIX__district` VALUES ('450201', '市辖区', '450200');
INSERT INTO `__PREFIX__district` VALUES ('450202', '城中区', '450200');
INSERT INTO `__PREFIX__district` VALUES ('450203', '鱼峰区', '450200');
INSERT INTO `__PREFIX__district` VALUES ('450204', '柳南区', '450200');
INSERT INTO `__PREFIX__district` VALUES ('450205', '柳北区', '450200');
INSERT INTO `__PREFIX__district` VALUES ('450221', '柳江县', '450200');
INSERT INTO `__PREFIX__district` VALUES ('450222', '柳城县', '450200');
INSERT INTO `__PREFIX__district` VALUES ('450223', '鹿寨县', '450200');
INSERT INTO `__PREFIX__district` VALUES ('450224', '融安县', '450200');
INSERT INTO `__PREFIX__district` VALUES ('450225', '融水苗族自治县', '450200');
INSERT INTO `__PREFIX__district` VALUES ('450226', '三江侗族自治县', '450200');
INSERT INTO `__PREFIX__district` VALUES ('450300', '桂林市', '450000');
INSERT INTO `__PREFIX__district` VALUES ('450301', '市辖区', '450300');
INSERT INTO `__PREFIX__district` VALUES ('450302', '秀峰区', '450300');
INSERT INTO `__PREFIX__district` VALUES ('450303', '叠彩区', '450300');
INSERT INTO `__PREFIX__district` VALUES ('450304', '象山区', '450300');
INSERT INTO `__PREFIX__district` VALUES ('450305', '七星区', '450300');
INSERT INTO `__PREFIX__district` VALUES ('450311', '雁山区', '450300');
INSERT INTO `__PREFIX__district` VALUES ('450312', '临桂区', '450300');
INSERT INTO `__PREFIX__district` VALUES ('450321', '阳朔县', '450300');
INSERT INTO `__PREFIX__district` VALUES ('450323', '灵川县', '450300');
INSERT INTO `__PREFIX__district` VALUES ('450324', '全州县', '450300');
INSERT INTO `__PREFIX__district` VALUES ('450325', '兴安县', '450300');
INSERT INTO `__PREFIX__district` VALUES ('450326', '永福县', '450300');
INSERT INTO `__PREFIX__district` VALUES ('450327', '灌阳县', '450300');
INSERT INTO `__PREFIX__district` VALUES ('450328', '龙胜各族自治县', '450300');
INSERT INTO `__PREFIX__district` VALUES ('450329', '资源县', '450300');
INSERT INTO `__PREFIX__district` VALUES ('450330', '平乐县', '450300');
INSERT INTO `__PREFIX__district` VALUES ('450331', '荔浦县', '450300');
INSERT INTO `__PREFIX__district` VALUES ('450332', '恭城瑶族自治县', '450300');
INSERT INTO `__PREFIX__district` VALUES ('450400', '梧州市', '450000');
INSERT INTO `__PREFIX__district` VALUES ('450401', '市辖区', '450400');
INSERT INTO `__PREFIX__district` VALUES ('450403', '万秀区', '450400');
INSERT INTO `__PREFIX__district` VALUES ('450405', '长洲区', '450400');
INSERT INTO `__PREFIX__district` VALUES ('450406', '龙圩区', '450400');
INSERT INTO `__PREFIX__district` VALUES ('450421', '苍梧县', '450400');
INSERT INTO `__PREFIX__district` VALUES ('450422', '藤县', '450400');
INSERT INTO `__PREFIX__district` VALUES ('450423', '蒙山县', '450400');
INSERT INTO `__PREFIX__district` VALUES ('450481', '岑溪市', '450400');
INSERT INTO `__PREFIX__district` VALUES ('450500', '北海市', '450000');
INSERT INTO `__PREFIX__district` VALUES ('450501', '市辖区', '450500');
INSERT INTO `__PREFIX__district` VALUES ('450502', '海城区', '450500');
INSERT INTO `__PREFIX__district` VALUES ('450503', '银海区', '450500');
INSERT INTO `__PREFIX__district` VALUES ('450512', '铁山港区', '450500');
INSERT INTO `__PREFIX__district` VALUES ('450521', '合浦县', '450500');
INSERT INTO `__PREFIX__district` VALUES ('450600', '防城港市', '450000');
INSERT INTO `__PREFIX__district` VALUES ('450601', '市辖区', '450600');
INSERT INTO `__PREFIX__district` VALUES ('450602', '港口区', '450600');
INSERT INTO `__PREFIX__district` VALUES ('450603', '防城区', '450600');
INSERT INTO `__PREFIX__district` VALUES ('450621', '上思县', '450600');
INSERT INTO `__PREFIX__district` VALUES ('450681', '东兴市', '450600');
INSERT INTO `__PREFIX__district` VALUES ('450700', '钦州市', '450000');
INSERT INTO `__PREFIX__district` VALUES ('450701', '市辖区', '450700');
INSERT INTO `__PREFIX__district` VALUES ('450702', '钦南区', '450700');
INSERT INTO `__PREFIX__district` VALUES ('450703', '钦北区', '450700');
INSERT INTO `__PREFIX__district` VALUES ('450721', '灵山县', '450700');
INSERT INTO `__PREFIX__district` VALUES ('450722', '浦北县', '450700');
INSERT INTO `__PREFIX__district` VALUES ('450800', '贵港市', '450000');
INSERT INTO `__PREFIX__district` VALUES ('450801', '市辖区', '450800');
INSERT INTO `__PREFIX__district` VALUES ('450802', '港北区', '450800');
INSERT INTO `__PREFIX__district` VALUES ('450803', '港南区', '450800');
INSERT INTO `__PREFIX__district` VALUES ('450804', '覃塘区', '450800');
INSERT INTO `__PREFIX__district` VALUES ('450821', '平南县', '450800');
INSERT INTO `__PREFIX__district` VALUES ('450881', '桂平市', '450800');
INSERT INTO `__PREFIX__district` VALUES ('450900', '玉林市', '450000');
INSERT INTO `__PREFIX__district` VALUES ('450901', '市辖区', '450900');
INSERT INTO `__PREFIX__district` VALUES ('450902', '玉州区', '450900');
INSERT INTO `__PREFIX__district` VALUES ('450903', '福绵区', '450900');
INSERT INTO `__PREFIX__district` VALUES ('450921', '容县', '450900');
INSERT INTO `__PREFIX__district` VALUES ('450922', '陆川县', '450900');
INSERT INTO `__PREFIX__district` VALUES ('450923', '博白县', '450900');
INSERT INTO `__PREFIX__district` VALUES ('450924', '兴业县', '450900');
INSERT INTO `__PREFIX__district` VALUES ('450981', '北流市', '450900');
INSERT INTO `__PREFIX__district` VALUES ('451000', '百色市', '450000');
INSERT INTO `__PREFIX__district` VALUES ('451001', '市辖区', '451000');
INSERT INTO `__PREFIX__district` VALUES ('451002', '右江区', '451000');
INSERT INTO `__PREFIX__district` VALUES ('451021', '田阳县', '451000');
INSERT INTO `__PREFIX__district` VALUES ('451022', '田东县', '451000');
INSERT INTO `__PREFIX__district` VALUES ('451023', '平果县', '451000');
INSERT INTO `__PREFIX__district` VALUES ('451024', '德保县', '451000');
INSERT INTO `__PREFIX__district` VALUES ('451025', '靖西县', '451000');
INSERT INTO `__PREFIX__district` VALUES ('451026', '那坡县', '451000');
INSERT INTO `__PREFIX__district` VALUES ('451027', '凌云县', '451000');
INSERT INTO `__PREFIX__district` VALUES ('451028', '乐业县', '451000');
INSERT INTO `__PREFIX__district` VALUES ('451029', '田林县', '451000');
INSERT INTO `__PREFIX__district` VALUES ('451030', '西林县', '451000');
INSERT INTO `__PREFIX__district` VALUES ('451031', '隆林各族自治县', '451000');
INSERT INTO `__PREFIX__district` VALUES ('451100', '贺州市', '450000');
INSERT INTO `__PREFIX__district` VALUES ('451101', '市辖区', '451100');
INSERT INTO `__PREFIX__district` VALUES ('451102', '八步区', '451100');
INSERT INTO `__PREFIX__district` VALUES ('451119', '平桂管理区', '451100');
INSERT INTO `__PREFIX__district` VALUES ('451121', '昭平县', '451100');
INSERT INTO `__PREFIX__district` VALUES ('451122', '钟山县', '451100');
INSERT INTO `__PREFIX__district` VALUES ('451123', '富川瑶族自治县', '451100');
INSERT INTO `__PREFIX__district` VALUES ('451200', '河池市', '450000');
INSERT INTO `__PREFIX__district` VALUES ('451201', '市辖区', '451200');
INSERT INTO `__PREFIX__district` VALUES ('451202', '金城江区', '451200');
INSERT INTO `__PREFIX__district` VALUES ('451221', '南丹县', '451200');
INSERT INTO `__PREFIX__district` VALUES ('451222', '天峨县', '451200');
INSERT INTO `__PREFIX__district` VALUES ('451223', '凤山县', '451200');
INSERT INTO `__PREFIX__district` VALUES ('451224', '东兰县', '451200');
INSERT INTO `__PREFIX__district` VALUES ('451225', '罗城仫佬族自治县', '451200');
INSERT INTO `__PREFIX__district` VALUES ('451226', '环江毛南族自治县', '451200');
INSERT INTO `__PREFIX__district` VALUES ('451227', '巴马瑶族自治县', '451200');
INSERT INTO `__PREFIX__district` VALUES ('451228', '都安瑶族自治县', '451200');
INSERT INTO `__PREFIX__district` VALUES ('451229', '大化瑶族自治县', '451200');
INSERT INTO `__PREFIX__district` VALUES ('451281', '宜州市', '451200');
INSERT INTO `__PREFIX__district` VALUES ('451300', '来宾市', '450000');
INSERT INTO `__PREFIX__district` VALUES ('451301', '市辖区', '451300');
INSERT INTO `__PREFIX__district` VALUES ('451302', '兴宾区', '451300');
INSERT INTO `__PREFIX__district` VALUES ('451321', '忻城县', '451300');
INSERT INTO `__PREFIX__district` VALUES ('451322', '象州县', '451300');
INSERT INTO `__PREFIX__district` VALUES ('451323', '武宣县', '451300');
INSERT INTO `__PREFIX__district` VALUES ('451324', '金秀瑶族自治县', '451300');
INSERT INTO `__PREFIX__district` VALUES ('451381', '合山市', '451300');
INSERT INTO `__PREFIX__district` VALUES ('451400', '崇左市', '450000');
INSERT INTO `__PREFIX__district` VALUES ('451401', '市辖区', '451400');
INSERT INTO `__PREFIX__district` VALUES ('451402', '江州区', '451400');
INSERT INTO `__PREFIX__district` VALUES ('451421', '扶绥县', '451400');
INSERT INTO `__PREFIX__district` VALUES ('451422', '宁明县', '451400');
INSERT INTO `__PREFIX__district` VALUES ('451423', '龙州县', '451400');
INSERT INTO `__PREFIX__district` VALUES ('451424', '大新县', '451400');
INSERT INTO `__PREFIX__district` VALUES ('451425', '天等县', '451400');
INSERT INTO `__PREFIX__district` VALUES ('451481', '凭祥市', '451400');
INSERT INTO `__PREFIX__district` VALUES ('460000', '海南省', '0');
INSERT INTO `__PREFIX__district` VALUES ('460100', '海口市', '460000');
INSERT INTO `__PREFIX__district` VALUES ('460101', '市辖区', '460100');
INSERT INTO `__PREFIX__district` VALUES ('460105', '秀英区', '460100');
INSERT INTO `__PREFIX__district` VALUES ('460106', '龙华区', '460100');
INSERT INTO `__PREFIX__district` VALUES ('460107', '琼山区', '460100');
INSERT INTO `__PREFIX__district` VALUES ('460108', '美兰区', '460100');
INSERT INTO `__PREFIX__district` VALUES ('460200', '三亚市', '460000');
INSERT INTO `__PREFIX__district` VALUES ('460201', '市辖区', '460200');
INSERT INTO `__PREFIX__district` VALUES ('460202', '海棠区', '460200');
INSERT INTO `__PREFIX__district` VALUES ('460203', '吉阳区', '460200');
INSERT INTO `__PREFIX__district` VALUES ('460204', '天涯区', '460200');
INSERT INTO `__PREFIX__district` VALUES ('460205', '崖州区', '460200');
INSERT INTO `__PREFIX__district` VALUES ('460300', '三沙市', '460000');
INSERT INTO `__PREFIX__district` VALUES ('460321', '西沙群岛', '460300');
INSERT INTO `__PREFIX__district` VALUES ('460322', '南沙群岛', '460300');
INSERT INTO `__PREFIX__district` VALUES ('460323', '中沙群岛的岛礁及其海域', '460300');
INSERT INTO `__PREFIX__district` VALUES ('469001', '五指山市', '460000');
INSERT INTO `__PREFIX__district` VALUES ('469002', '琼海市', '460000');
INSERT INTO `__PREFIX__district` VALUES ('469003', '儋州市', '460000');
INSERT INTO `__PREFIX__district` VALUES ('469005', '文昌市', '460000');
INSERT INTO `__PREFIX__district` VALUES ('469006', '万宁市', '460000');
INSERT INTO `__PREFIX__district` VALUES ('469007', '东方市', '460000');
INSERT INTO `__PREFIX__district` VALUES ('469021', '定安县', '460000');
INSERT INTO `__PREFIX__district` VALUES ('469022', '屯昌县', '460000');
INSERT INTO `__PREFIX__district` VALUES ('469023', '澄迈县', '460000');
INSERT INTO `__PREFIX__district` VALUES ('469024', '临高县', '460000');
INSERT INTO `__PREFIX__district` VALUES ('469025', '白沙黎族自治县', '460000');
INSERT INTO `__PREFIX__district` VALUES ('469026', '昌江黎族自治县', '460000');
INSERT INTO `__PREFIX__district` VALUES ('469027', '乐东黎族自治县', '460000');
INSERT INTO `__PREFIX__district` VALUES ('469028', '陵水黎族自治县', '460000');
INSERT INTO `__PREFIX__district` VALUES ('469029', '保亭黎族苗族自治县', '460000');
INSERT INTO `__PREFIX__district` VALUES ('469030', '琼中黎族苗族自治县', '460000');
INSERT INTO `__PREFIX__district` VALUES ('500000', '重庆', '0');
INSERT INTO `__PREFIX__district` VALUES ('500100', '重庆市', '500000');
INSERT INTO `__PREFIX__district` VALUES ('500101', '万州区', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500102', '涪陵区', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500103', '渝中区', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500104', '大渡口区', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500105', '江北区', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500106', '沙坪坝区', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500107', '九龙坡区', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500108', '南岸区', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500109', '北碚区', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500110', '綦江区', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500111', '大足区', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500112', '渝北区', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500113', '巴南区', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500114', '黔江区', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500115', '长寿区', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500116', '江津区', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500117', '合川区', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500118', '永川区', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500119', '南川区', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500120', '璧山区', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500151', '铜梁区', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500223', '潼南县', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500226', '荣昌县', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500228', '梁平县', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500229', '城口县', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500230', '丰都县', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500231', '垫江县', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500232', '武隆县', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500233', '忠县', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500234', '开县', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500235', '云阳县', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500236', '奉节县', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500237', '巫山县', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500238', '巫溪县', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500240', '石柱土家族自治县', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500241', '秀山土家族苗族自治县', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500242', '酉阳土家族苗族自治县', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500243', '彭水苗族土家族自治县', '500100');
INSERT INTO `__PREFIX__district` VALUES ('500300', '两江新区', '500100');
INSERT INTO `__PREFIX__district` VALUES ('510000', '四川省', '0');
INSERT INTO `__PREFIX__district` VALUES ('510100', '成都市', '510000');
INSERT INTO `__PREFIX__district` VALUES ('510101', '市辖区', '510100');
INSERT INTO `__PREFIX__district` VALUES ('510104', '锦江区', '510100');
INSERT INTO `__PREFIX__district` VALUES ('510105', '青羊区', '510100');
INSERT INTO `__PREFIX__district` VALUES ('510106', '金牛区', '510100');
INSERT INTO `__PREFIX__district` VALUES ('510107', '武侯区', '510100');
INSERT INTO `__PREFIX__district` VALUES ('510108', '成华区', '510100');
INSERT INTO `__PREFIX__district` VALUES ('510112', '龙泉驿区', '510100');
INSERT INTO `__PREFIX__district` VALUES ('510113', '青白江区', '510100');
INSERT INTO `__PREFIX__district` VALUES ('510114', '新都区', '510100');
INSERT INTO `__PREFIX__district` VALUES ('510115', '温江区', '510100');
INSERT INTO `__PREFIX__district` VALUES ('510121', '金堂县', '510100');
INSERT INTO `__PREFIX__district` VALUES ('510122', '双流县', '510100');
INSERT INTO `__PREFIX__district` VALUES ('510124', '郫县', '510100');
INSERT INTO `__PREFIX__district` VALUES ('510129', '大邑县', '510100');
INSERT INTO `__PREFIX__district` VALUES ('510131', '蒲江县', '510100');
INSERT INTO `__PREFIX__district` VALUES ('510132', '新津县', '510100');
INSERT INTO `__PREFIX__district` VALUES ('510181', '都江堰市', '510100');
INSERT INTO `__PREFIX__district` VALUES ('510182', '彭州市', '510100');
INSERT INTO `__PREFIX__district` VALUES ('510183', '邛崃市', '510100');
INSERT INTO `__PREFIX__district` VALUES ('510184', '崇州市', '510100');
INSERT INTO `__PREFIX__district` VALUES ('510300', '自贡市', '510000');
INSERT INTO `__PREFIX__district` VALUES ('510301', '市辖区', '510300');
INSERT INTO `__PREFIX__district` VALUES ('510302', '自流井区', '510300');
INSERT INTO `__PREFIX__district` VALUES ('510303', '贡井区', '510300');
INSERT INTO `__PREFIX__district` VALUES ('510304', '大安区', '510300');
INSERT INTO `__PREFIX__district` VALUES ('510311', '沿滩区', '510300');
INSERT INTO `__PREFIX__district` VALUES ('510321', '荣县', '510300');
INSERT INTO `__PREFIX__district` VALUES ('510322', '富顺县', '510300');
INSERT INTO `__PREFIX__district` VALUES ('510400', '攀枝花市', '510000');
INSERT INTO `__PREFIX__district` VALUES ('510401', '市辖区', '510400');
INSERT INTO `__PREFIX__district` VALUES ('510402', '东区', '510400');
INSERT INTO `__PREFIX__district` VALUES ('510403', '西区', '510400');
INSERT INTO `__PREFIX__district` VALUES ('510411', '仁和区', '510400');
INSERT INTO `__PREFIX__district` VALUES ('510421', '米易县', '510400');
INSERT INTO `__PREFIX__district` VALUES ('510422', '盐边县', '510400');
INSERT INTO `__PREFIX__district` VALUES ('510500', '泸州市', '510000');
INSERT INTO `__PREFIX__district` VALUES ('510501', '市辖区', '510500');
INSERT INTO `__PREFIX__district` VALUES ('510502', '江阳区', '510500');
INSERT INTO `__PREFIX__district` VALUES ('510503', '纳溪区', '510500');
INSERT INTO `__PREFIX__district` VALUES ('510504', '龙马潭区', '510500');
INSERT INTO `__PREFIX__district` VALUES ('510521', '泸县', '510500');
INSERT INTO `__PREFIX__district` VALUES ('510522', '合江县', '510500');
INSERT INTO `__PREFIX__district` VALUES ('510524', '叙永县', '510500');
INSERT INTO `__PREFIX__district` VALUES ('510525', '古蔺县', '510500');
INSERT INTO `__PREFIX__district` VALUES ('510600', '德阳市', '510000');
INSERT INTO `__PREFIX__district` VALUES ('510601', '市辖区', '510600');
INSERT INTO `__PREFIX__district` VALUES ('510603', '旌阳区', '510600');
INSERT INTO `__PREFIX__district` VALUES ('510623', '中江县', '510600');
INSERT INTO `__PREFIX__district` VALUES ('510626', '罗江县', '510600');
INSERT INTO `__PREFIX__district` VALUES ('510681', '广汉市', '510600');
INSERT INTO `__PREFIX__district` VALUES ('510682', '什邡市', '510600');
INSERT INTO `__PREFIX__district` VALUES ('510683', '绵竹市', '510600');
INSERT INTO `__PREFIX__district` VALUES ('510700', '绵阳市', '510000');
INSERT INTO `__PREFIX__district` VALUES ('510701', '市辖区', '510700');
INSERT INTO `__PREFIX__district` VALUES ('510703', '涪城区', '510700');
INSERT INTO `__PREFIX__district` VALUES ('510704', '游仙区', '510700');
INSERT INTO `__PREFIX__district` VALUES ('510722', '三台县', '510700');
INSERT INTO `__PREFIX__district` VALUES ('510723', '盐亭县', '510700');
INSERT INTO `__PREFIX__district` VALUES ('510724', '安县', '510700');
INSERT INTO `__PREFIX__district` VALUES ('510725', '梓潼县', '510700');
INSERT INTO `__PREFIX__district` VALUES ('510726', '北川羌族自治县', '510700');
INSERT INTO `__PREFIX__district` VALUES ('510727', '平武县', '510700');
INSERT INTO `__PREFIX__district` VALUES ('510781', '江油市', '510700');
INSERT INTO `__PREFIX__district` VALUES ('510800', '广元市', '510000');
INSERT INTO `__PREFIX__district` VALUES ('510801', '市辖区', '510800');
INSERT INTO `__PREFIX__district` VALUES ('510802', '利州区', '510800');
INSERT INTO `__PREFIX__district` VALUES ('510811', '昭化区', '510800');
INSERT INTO `__PREFIX__district` VALUES ('510812', '朝天区', '510800');
INSERT INTO `__PREFIX__district` VALUES ('510821', '旺苍县', '510800');
INSERT INTO `__PREFIX__district` VALUES ('510822', '青川县', '510800');
INSERT INTO `__PREFIX__district` VALUES ('510823', '剑阁县', '510800');
INSERT INTO `__PREFIX__district` VALUES ('510824', '苍溪县', '510800');
INSERT INTO `__PREFIX__district` VALUES ('510900', '遂宁市', '510000');
INSERT INTO `__PREFIX__district` VALUES ('510901', '市辖区', '510900');
INSERT INTO `__PREFIX__district` VALUES ('510903', '船山区', '510900');
INSERT INTO `__PREFIX__district` VALUES ('510904', '安居区', '510900');
INSERT INTO `__PREFIX__district` VALUES ('510921', '蓬溪县', '510900');
INSERT INTO `__PREFIX__district` VALUES ('510922', '射洪县', '510900');
INSERT INTO `__PREFIX__district` VALUES ('510923', '大英县', '510900');
INSERT INTO `__PREFIX__district` VALUES ('511000', '内江市', '510000');
INSERT INTO `__PREFIX__district` VALUES ('511001', '市辖区', '511000');
INSERT INTO `__PREFIX__district` VALUES ('511002', '市中区', '511000');
INSERT INTO `__PREFIX__district` VALUES ('511011', '东兴区', '511000');
INSERT INTO `__PREFIX__district` VALUES ('511024', '威远县', '511000');
INSERT INTO `__PREFIX__district` VALUES ('511025', '资中县', '511000');
INSERT INTO `__PREFIX__district` VALUES ('511028', '隆昌县', '511000');
INSERT INTO `__PREFIX__district` VALUES ('511100', '乐山市', '510000');
INSERT INTO `__PREFIX__district` VALUES ('511101', '市辖区', '511100');
INSERT INTO `__PREFIX__district` VALUES ('511102', '市中区', '511100');
INSERT INTO `__PREFIX__district` VALUES ('511111', '沙湾区', '511100');
INSERT INTO `__PREFIX__district` VALUES ('511112', '五通桥区', '511100');
INSERT INTO `__PREFIX__district` VALUES ('511113', '金口河区', '511100');
INSERT INTO `__PREFIX__district` VALUES ('511123', '犍为县', '511100');
INSERT INTO `__PREFIX__district` VALUES ('511124', '井研县', '511100');
INSERT INTO `__PREFIX__district` VALUES ('511126', '夹江县', '511100');
INSERT INTO `__PREFIX__district` VALUES ('511129', '沐川县', '511100');
INSERT INTO `__PREFIX__district` VALUES ('511132', '峨边彝族自治县', '511100');
INSERT INTO `__PREFIX__district` VALUES ('511133', '马边彝族自治县', '511100');
INSERT INTO `__PREFIX__district` VALUES ('511181', '峨眉山市', '511100');
INSERT INTO `__PREFIX__district` VALUES ('511300', '南充市', '510000');
INSERT INTO `__PREFIX__district` VALUES ('511301', '市辖区', '511300');
INSERT INTO `__PREFIX__district` VALUES ('511302', '顺庆区', '511300');
INSERT INTO `__PREFIX__district` VALUES ('511303', '高坪区', '511300');
INSERT INTO `__PREFIX__district` VALUES ('511304', '嘉陵区', '511300');
INSERT INTO `__PREFIX__district` VALUES ('511321', '南部县', '511300');
INSERT INTO `__PREFIX__district` VALUES ('511322', '营山县', '511300');
INSERT INTO `__PREFIX__district` VALUES ('511323', '蓬安县', '511300');
INSERT INTO `__PREFIX__district` VALUES ('511324', '仪陇县', '511300');
INSERT INTO `__PREFIX__district` VALUES ('511325', '西充县', '511300');
INSERT INTO `__PREFIX__district` VALUES ('511381', '阆中市', '511300');
INSERT INTO `__PREFIX__district` VALUES ('511400', '眉山市', '510000');
INSERT INTO `__PREFIX__district` VALUES ('511401', '市辖区', '511400');
INSERT INTO `__PREFIX__district` VALUES ('511402', '东坡区', '511400');
INSERT INTO `__PREFIX__district` VALUES ('511403', '彭山区', '511400');
INSERT INTO `__PREFIX__district` VALUES ('511421', '仁寿县', '511400');
INSERT INTO `__PREFIX__district` VALUES ('511423', '洪雅县', '511400');
INSERT INTO `__PREFIX__district` VALUES ('511424', '丹棱县', '511400');
INSERT INTO `__PREFIX__district` VALUES ('511425', '青神县', '511400');
INSERT INTO `__PREFIX__district` VALUES ('511500', '宜宾市', '510000');
INSERT INTO `__PREFIX__district` VALUES ('511501', '市辖区', '511500');
INSERT INTO `__PREFIX__district` VALUES ('511502', '翠屏区', '511500');
INSERT INTO `__PREFIX__district` VALUES ('511503', '南溪区', '511500');
INSERT INTO `__PREFIX__district` VALUES ('511521', '宜宾县', '511500');
INSERT INTO `__PREFIX__district` VALUES ('511523', '江安县', '511500');
INSERT INTO `__PREFIX__district` VALUES ('511524', '长宁县', '511500');
INSERT INTO `__PREFIX__district` VALUES ('511525', '高县', '511500');
INSERT INTO `__PREFIX__district` VALUES ('511526', '珙县', '511500');
INSERT INTO `__PREFIX__district` VALUES ('511527', '筠连县', '511500');
INSERT INTO `__PREFIX__district` VALUES ('511528', '兴文县', '511500');
INSERT INTO `__PREFIX__district` VALUES ('511529', '屏山县', '511500');
INSERT INTO `__PREFIX__district` VALUES ('511600', '广安市', '510000');
INSERT INTO `__PREFIX__district` VALUES ('511601', '市辖区', '511600');
INSERT INTO `__PREFIX__district` VALUES ('511602', '广安区', '511600');
INSERT INTO `__PREFIX__district` VALUES ('511603', '前锋区', '511600');
INSERT INTO `__PREFIX__district` VALUES ('511621', '岳池县', '511600');
INSERT INTO `__PREFIX__district` VALUES ('511622', '武胜县', '511600');
INSERT INTO `__PREFIX__district` VALUES ('511623', '邻水县', '511600');
INSERT INTO `__PREFIX__district` VALUES ('511681', '华蓥市', '511600');
INSERT INTO `__PREFIX__district` VALUES ('511700', '达州市', '510000');
INSERT INTO `__PREFIX__district` VALUES ('511701', '市辖区', '511700');
INSERT INTO `__PREFIX__district` VALUES ('511702', '通川区', '511700');
INSERT INTO `__PREFIX__district` VALUES ('511703', '达川区', '511700');
INSERT INTO `__PREFIX__district` VALUES ('511722', '宣汉县', '511700');
INSERT INTO `__PREFIX__district` VALUES ('511723', '开江县', '511700');
INSERT INTO `__PREFIX__district` VALUES ('511724', '大竹县', '511700');
INSERT INTO `__PREFIX__district` VALUES ('511725', '渠县', '511700');
INSERT INTO `__PREFIX__district` VALUES ('511781', '万源市', '511700');
INSERT INTO `__PREFIX__district` VALUES ('511800', '雅安市', '510000');
INSERT INTO `__PREFIX__district` VALUES ('511801', '市辖区', '511800');
INSERT INTO `__PREFIX__district` VALUES ('511802', '雨城区', '511800');
INSERT INTO `__PREFIX__district` VALUES ('511803', '名山区', '511800');
INSERT INTO `__PREFIX__district` VALUES ('511822', '荥经县', '511800');
INSERT INTO `__PREFIX__district` VALUES ('511823', '汉源县', '511800');
INSERT INTO `__PREFIX__district` VALUES ('511824', '石棉县', '511800');
INSERT INTO `__PREFIX__district` VALUES ('511825', '天全县', '511800');
INSERT INTO `__PREFIX__district` VALUES ('511826', '芦山县', '511800');
INSERT INTO `__PREFIX__district` VALUES ('511827', '宝兴县', '511800');
INSERT INTO `__PREFIX__district` VALUES ('511900', '巴中市', '510000');
INSERT INTO `__PREFIX__district` VALUES ('511901', '市辖区', '511900');
INSERT INTO `__PREFIX__district` VALUES ('511902', '巴州区', '511900');
INSERT INTO `__PREFIX__district` VALUES ('511903', '恩阳区', '511900');
INSERT INTO `__PREFIX__district` VALUES ('511921', '通江县', '511900');
INSERT INTO `__PREFIX__district` VALUES ('511922', '南江县', '511900');
INSERT INTO `__PREFIX__district` VALUES ('511923', '平昌县', '511900');
INSERT INTO `__PREFIX__district` VALUES ('512000', '资阳市', '510000');
INSERT INTO `__PREFIX__district` VALUES ('512001', '市辖区', '512000');
INSERT INTO `__PREFIX__district` VALUES ('512002', '雁江区', '512000');
INSERT INTO `__PREFIX__district` VALUES ('512021', '安岳县', '512000');
INSERT INTO `__PREFIX__district` VALUES ('512022', '乐至县', '512000');
INSERT INTO `__PREFIX__district` VALUES ('512081', '简阳市', '512000');
INSERT INTO `__PREFIX__district` VALUES ('513200', '阿坝藏族羌族自治州', '510000');
INSERT INTO `__PREFIX__district` VALUES ('513221', '汶川县', '513200');
INSERT INTO `__PREFIX__district` VALUES ('513222', '理县', '513200');
INSERT INTO `__PREFIX__district` VALUES ('513223', '茂县', '513200');
INSERT INTO `__PREFIX__district` VALUES ('513224', '松潘县', '513200');
INSERT INTO `__PREFIX__district` VALUES ('513225', '九寨沟县', '513200');
INSERT INTO `__PREFIX__district` VALUES ('513226', '金川县', '513200');
INSERT INTO `__PREFIX__district` VALUES ('513227', '小金县', '513200');
INSERT INTO `__PREFIX__district` VALUES ('513228', '黑水县', '513200');
INSERT INTO `__PREFIX__district` VALUES ('513229', '马尔康县', '513200');
INSERT INTO `__PREFIX__district` VALUES ('513230', '壤塘县', '513200');
INSERT INTO `__PREFIX__district` VALUES ('513231', '阿坝县', '513200');
INSERT INTO `__PREFIX__district` VALUES ('513232', '若尔盖县', '513200');
INSERT INTO `__PREFIX__district` VALUES ('513233', '红原县', '513200');
INSERT INTO `__PREFIX__district` VALUES ('513300', '甘孜藏族自治州', '510000');
INSERT INTO `__PREFIX__district` VALUES ('513321', '康定县', '513300');
INSERT INTO `__PREFIX__district` VALUES ('513322', '泸定县', '513300');
INSERT INTO `__PREFIX__district` VALUES ('513323', '丹巴县', '513300');
INSERT INTO `__PREFIX__district` VALUES ('513324', '九龙县', '513300');
INSERT INTO `__PREFIX__district` VALUES ('513325', '雅江县', '513300');
INSERT INTO `__PREFIX__district` VALUES ('513326', '道孚县', '513300');
INSERT INTO `__PREFIX__district` VALUES ('513327', '炉霍县', '513300');
INSERT INTO `__PREFIX__district` VALUES ('513328', '甘孜县', '513300');
INSERT INTO `__PREFIX__district` VALUES ('513329', '新龙县', '513300');
INSERT INTO `__PREFIX__district` VALUES ('513330', '德格县', '513300');
INSERT INTO `__PREFIX__district` VALUES ('513331', '白玉县', '513300');
INSERT INTO `__PREFIX__district` VALUES ('513332', '石渠县', '513300');
INSERT INTO `__PREFIX__district` VALUES ('513333', '色达县', '513300');
INSERT INTO `__PREFIX__district` VALUES ('513334', '理塘县', '513300');
INSERT INTO `__PREFIX__district` VALUES ('513335', '巴塘县', '513300');
INSERT INTO `__PREFIX__district` VALUES ('513336', '乡城县', '513300');
INSERT INTO `__PREFIX__district` VALUES ('513337', '稻城县', '513300');
INSERT INTO `__PREFIX__district` VALUES ('513338', '得荣县', '513300');
INSERT INTO `__PREFIX__district` VALUES ('513400', '凉山彝族自治州', '510000');
INSERT INTO `__PREFIX__district` VALUES ('513401', '西昌市', '513400');
INSERT INTO `__PREFIX__district` VALUES ('513422', '木里藏族自治县', '513400');
INSERT INTO `__PREFIX__district` VALUES ('513423', '盐源县', '513400');
INSERT INTO `__PREFIX__district` VALUES ('513424', '德昌县', '513400');
INSERT INTO `__PREFIX__district` VALUES ('513425', '会理县', '513400');
INSERT INTO `__PREFIX__district` VALUES ('513426', '会东县', '513400');
INSERT INTO `__PREFIX__district` VALUES ('513427', '宁南县', '513400');
INSERT INTO `__PREFIX__district` VALUES ('513428', '普格县', '513400');
INSERT INTO `__PREFIX__district` VALUES ('513429', '布拖县', '513400');
INSERT INTO `__PREFIX__district` VALUES ('513430', '金阳县', '513400');
INSERT INTO `__PREFIX__district` VALUES ('513431', '昭觉县', '513400');
INSERT INTO `__PREFIX__district` VALUES ('513432', '喜德县', '513400');
INSERT INTO `__PREFIX__district` VALUES ('513433', '冕宁县', '513400');
INSERT INTO `__PREFIX__district` VALUES ('513434', '越西县', '513400');
INSERT INTO `__PREFIX__district` VALUES ('513435', '甘洛县', '513400');
INSERT INTO `__PREFIX__district` VALUES ('513436', '美姑县', '513400');
INSERT INTO `__PREFIX__district` VALUES ('513437', '雷波县', '513400');
INSERT INTO `__PREFIX__district` VALUES ('520000', '贵州省', '0');
INSERT INTO `__PREFIX__district` VALUES ('520100', '贵阳市', '520000');
INSERT INTO `__PREFIX__district` VALUES ('520101', '市辖区', '520100');
INSERT INTO `__PREFIX__district` VALUES ('520102', '南明区', '520100');
INSERT INTO `__PREFIX__district` VALUES ('520103', '云岩区', '520100');
INSERT INTO `__PREFIX__district` VALUES ('520111', '花溪区', '520100');
INSERT INTO `__PREFIX__district` VALUES ('520112', '乌当区', '520100');
INSERT INTO `__PREFIX__district` VALUES ('520113', '白云区', '520100');
INSERT INTO `__PREFIX__district` VALUES ('520115', '观山湖区', '520100');
INSERT INTO `__PREFIX__district` VALUES ('520121', '开阳县', '520100');
INSERT INTO `__PREFIX__district` VALUES ('520122', '息烽县', '520100');
INSERT INTO `__PREFIX__district` VALUES ('520123', '修文县', '520100');
INSERT INTO `__PREFIX__district` VALUES ('520181', '清镇市', '520100');
INSERT INTO `__PREFIX__district` VALUES ('520200', '六盘水市', '520000');
INSERT INTO `__PREFIX__district` VALUES ('520201', '钟山区', '520200');
INSERT INTO `__PREFIX__district` VALUES ('520203', '六枝特区', '520200');
INSERT INTO `__PREFIX__district` VALUES ('520221', '水城县', '520200');
INSERT INTO `__PREFIX__district` VALUES ('520222', '盘县', '520200');
INSERT INTO `__PREFIX__district` VALUES ('520300', '遵义市', '520000');
INSERT INTO `__PREFIX__district` VALUES ('520301', '市辖区', '520300');
INSERT INTO `__PREFIX__district` VALUES ('520302', '红花岗区', '520300');
INSERT INTO `__PREFIX__district` VALUES ('520303', '汇川区', '520300');
INSERT INTO `__PREFIX__district` VALUES ('520321', '播州区', '520300');
INSERT INTO `__PREFIX__district` VALUES ('520322', '桐梓县', '520300');
INSERT INTO `__PREFIX__district` VALUES ('520323', '绥阳县', '520300');
INSERT INTO `__PREFIX__district` VALUES ('520324', '正安县', '520300');
INSERT INTO `__PREFIX__district` VALUES ('520325', '道真仡佬族苗族自治县', '520300');
INSERT INTO `__PREFIX__district` VALUES ('520326', '务川仡佬族苗族自治县', '520300');
INSERT INTO `__PREFIX__district` VALUES ('520327', '凤冈县', '520300');
INSERT INTO `__PREFIX__district` VALUES ('520328', '湄潭县', '520300');
INSERT INTO `__PREFIX__district` VALUES ('520329', '余庆县', '520300');
INSERT INTO `__PREFIX__district` VALUES ('520330', '习水县', '520300');
INSERT INTO `__PREFIX__district` VALUES ('520381', '赤水市', '520300');
INSERT INTO `__PREFIX__district` VALUES ('520382', '仁怀市', '520300');
INSERT INTO `__PREFIX__district` VALUES ('520400', '安顺市', '520000');
INSERT INTO `__PREFIX__district` VALUES ('520401', '市辖区', '520400');
INSERT INTO `__PREFIX__district` VALUES ('520402', '西秀区', '520400');
INSERT INTO `__PREFIX__district` VALUES ('520403', '平坝区', '520400');
INSERT INTO `__PREFIX__district` VALUES ('520422', '普定县', '520400');
INSERT INTO `__PREFIX__district` VALUES ('520423', '镇宁布依族苗族自治县', '520400');
INSERT INTO `__PREFIX__district` VALUES ('520424', '关岭布依族苗族自治县', '520400');
INSERT INTO `__PREFIX__district` VALUES ('520425', '紫云苗族布依族自治县', '520400');
INSERT INTO `__PREFIX__district` VALUES ('520500', '毕节市', '520000');
INSERT INTO `__PREFIX__district` VALUES ('520501', '市辖区', '520500');
INSERT INTO `__PREFIX__district` VALUES ('520502', '七星关区', '520500');
INSERT INTO `__PREFIX__district` VALUES ('520521', '大方县', '520500');
INSERT INTO `__PREFIX__district` VALUES ('520522', '黔西县', '520500');
INSERT INTO `__PREFIX__district` VALUES ('520523', '金沙县', '520500');
INSERT INTO `__PREFIX__district` VALUES ('520524', '织金县', '520500');
INSERT INTO `__PREFIX__district` VALUES ('520525', '纳雍县', '520500');
INSERT INTO `__PREFIX__district` VALUES ('520526', '威宁彝族回族苗族自治县', '520500');
INSERT INTO `__PREFIX__district` VALUES ('520527', '赫章县', '520500');
INSERT INTO `__PREFIX__district` VALUES ('520600', '铜仁市', '520000');
INSERT INTO `__PREFIX__district` VALUES ('520601', '市辖区', '520600');
INSERT INTO `__PREFIX__district` VALUES ('520602', '碧江区', '520600');
INSERT INTO `__PREFIX__district` VALUES ('520603', '万山区', '520600');
INSERT INTO `__PREFIX__district` VALUES ('520621', '江口县', '520600');
INSERT INTO `__PREFIX__district` VALUES ('520622', '玉屏侗族自治县', '520600');
INSERT INTO `__PREFIX__district` VALUES ('520623', '石阡县', '520600');
INSERT INTO `__PREFIX__district` VALUES ('520624', '思南县', '520600');
INSERT INTO `__PREFIX__district` VALUES ('520625', '印江土家族苗族自治县', '520600');
INSERT INTO `__PREFIX__district` VALUES ('520626', '德江县', '520600');
INSERT INTO `__PREFIX__district` VALUES ('520627', '沿河土家族自治县', '520600');
INSERT INTO `__PREFIX__district` VALUES ('520628', '松桃苗族自治县', '520600');
INSERT INTO `__PREFIX__district` VALUES ('522300', '黔西南布依族苗族自治州', '520000');
INSERT INTO `__PREFIX__district` VALUES ('522301', '兴义市', '522300');
INSERT INTO `__PREFIX__district` VALUES ('522322', '兴仁县', '522300');
INSERT INTO `__PREFIX__district` VALUES ('522323', '普安县', '522300');
INSERT INTO `__PREFIX__district` VALUES ('522324', '晴隆县', '522300');
INSERT INTO `__PREFIX__district` VALUES ('522325', '贞丰县', '522300');
INSERT INTO `__PREFIX__district` VALUES ('522326', '望谟县', '522300');
INSERT INTO `__PREFIX__district` VALUES ('522327', '册亨县', '522300');
INSERT INTO `__PREFIX__district` VALUES ('522328', '安龙县', '522300');
INSERT INTO `__PREFIX__district` VALUES ('522600', '黔东南苗族侗族自治州', '520000');
INSERT INTO `__PREFIX__district` VALUES ('522601', '凯里市', '522600');
INSERT INTO `__PREFIX__district` VALUES ('522622', '黄平县', '522600');
INSERT INTO `__PREFIX__district` VALUES ('522623', '施秉县', '522600');
INSERT INTO `__PREFIX__district` VALUES ('522624', '三穗县', '522600');
INSERT INTO `__PREFIX__district` VALUES ('522625', '镇远县', '522600');
INSERT INTO `__PREFIX__district` VALUES ('522626', '岑巩县', '522600');
INSERT INTO `__PREFIX__district` VALUES ('522627', '天柱县', '522600');
INSERT INTO `__PREFIX__district` VALUES ('522628', '锦屏县', '522600');
INSERT INTO `__PREFIX__district` VALUES ('522629', '剑河县', '522600');
INSERT INTO `__PREFIX__district` VALUES ('522630', '台江县', '522600');
INSERT INTO `__PREFIX__district` VALUES ('522631', '黎平县', '522600');
INSERT INTO `__PREFIX__district` VALUES ('522632', '榕江县', '522600');
INSERT INTO `__PREFIX__district` VALUES ('522633', '从江县', '522600');
INSERT INTO `__PREFIX__district` VALUES ('522634', '雷山县', '522600');
INSERT INTO `__PREFIX__district` VALUES ('522635', '麻江县', '522600');
INSERT INTO `__PREFIX__district` VALUES ('522636', '丹寨县', '522600');
INSERT INTO `__PREFIX__district` VALUES ('522700', '黔南布依族苗族自治州', '520000');
INSERT INTO `__PREFIX__district` VALUES ('522701', '都匀市', '522700');
INSERT INTO `__PREFIX__district` VALUES ('522702', '福泉市', '522700');
INSERT INTO `__PREFIX__district` VALUES ('522722', '荔波县', '522700');
INSERT INTO `__PREFIX__district` VALUES ('522723', '贵定县', '522700');
INSERT INTO `__PREFIX__district` VALUES ('522725', '瓮安县', '522700');
INSERT INTO `__PREFIX__district` VALUES ('522726', '独山县', '522700');
INSERT INTO `__PREFIX__district` VALUES ('522727', '平塘县', '522700');
INSERT INTO `__PREFIX__district` VALUES ('522728', '罗甸县', '522700');
INSERT INTO `__PREFIX__district` VALUES ('522729', '长顺县', '522700');
INSERT INTO `__PREFIX__district` VALUES ('522730', '龙里县', '522700');
INSERT INTO `__PREFIX__district` VALUES ('522731', '惠水县', '522700');
INSERT INTO `__PREFIX__district` VALUES ('522732', '三都水族自治县', '522700');
INSERT INTO `__PREFIX__district` VALUES ('530000', '云南省', '0');
INSERT INTO `__PREFIX__district` VALUES ('530100', '昆明市', '530000');
INSERT INTO `__PREFIX__district` VALUES ('530101', '市辖区', '530100');
INSERT INTO `__PREFIX__district` VALUES ('530102', '五华区', '530100');
INSERT INTO `__PREFIX__district` VALUES ('530103', '盘龙区', '530100');
INSERT INTO `__PREFIX__district` VALUES ('530111', '官渡区', '530100');
INSERT INTO `__PREFIX__district` VALUES ('530112', '西山区', '530100');
INSERT INTO `__PREFIX__district` VALUES ('530113', '东川区', '530100');
INSERT INTO `__PREFIX__district` VALUES ('530114', '呈贡区', '530100');
INSERT INTO `__PREFIX__district` VALUES ('530122', '晋宁县', '530100');
INSERT INTO `__PREFIX__district` VALUES ('530124', '富民县', '530100');
INSERT INTO `__PREFIX__district` VALUES ('530125', '宜良县', '530100');
INSERT INTO `__PREFIX__district` VALUES ('530126', '石林彝族自治县', '530100');
INSERT INTO `__PREFIX__district` VALUES ('530127', '嵩明县', '530100');
INSERT INTO `__PREFIX__district` VALUES ('530128', '禄劝彝族苗族自治县', '530100');
INSERT INTO `__PREFIX__district` VALUES ('530129', '寻甸回族彝族自治县', '530100');
INSERT INTO `__PREFIX__district` VALUES ('530181', '安宁市', '530100');
INSERT INTO `__PREFIX__district` VALUES ('530300', '曲靖市', '530000');
INSERT INTO `__PREFIX__district` VALUES ('530301', '市辖区', '530300');
INSERT INTO `__PREFIX__district` VALUES ('530302', '麒麟区', '530300');
INSERT INTO `__PREFIX__district` VALUES ('530321', '马龙县', '530300');
INSERT INTO `__PREFIX__district` VALUES ('530322', '陆良县', '530300');
INSERT INTO `__PREFIX__district` VALUES ('530323', '师宗县', '530300');
INSERT INTO `__PREFIX__district` VALUES ('530324', '罗平县', '530300');
INSERT INTO `__PREFIX__district` VALUES ('530325', '富源县', '530300');
INSERT INTO `__PREFIX__district` VALUES ('530326', '会泽县', '530300');
INSERT INTO `__PREFIX__district` VALUES ('530328', '沾益县', '530300');
INSERT INTO `__PREFIX__district` VALUES ('530381', '宣威市', '530300');
INSERT INTO `__PREFIX__district` VALUES ('530400', '玉溪市', '530000');
INSERT INTO `__PREFIX__district` VALUES ('530401', '市辖区', '530400');
INSERT INTO `__PREFIX__district` VALUES ('530402', '红塔区', '530400');
INSERT INTO `__PREFIX__district` VALUES ('530421', '江川县', '530400');
INSERT INTO `__PREFIX__district` VALUES ('530422', '澄江县', '530400');
INSERT INTO `__PREFIX__district` VALUES ('530423', '通海县', '530400');
INSERT INTO `__PREFIX__district` VALUES ('530424', '华宁县', '530400');
INSERT INTO `__PREFIX__district` VALUES ('530425', '易门县', '530400');
INSERT INTO `__PREFIX__district` VALUES ('530426', '峨山彝族自治县', '530400');
INSERT INTO `__PREFIX__district` VALUES ('530427', '新平彝族傣族自治县', '530400');
INSERT INTO `__PREFIX__district` VALUES ('530428', '元江哈尼族彝族傣族自治县', '530400');
INSERT INTO `__PREFIX__district` VALUES ('530500', '保山市', '530000');
INSERT INTO `__PREFIX__district` VALUES ('530501', '市辖区', '530500');
INSERT INTO `__PREFIX__district` VALUES ('530502', '隆阳区', '530500');
INSERT INTO `__PREFIX__district` VALUES ('530521', '施甸县', '530500');
INSERT INTO `__PREFIX__district` VALUES ('530522', '腾冲县', '530500');
INSERT INTO `__PREFIX__district` VALUES ('530523', '龙陵县', '530500');
INSERT INTO `__PREFIX__district` VALUES ('530524', '昌宁县', '530500');
INSERT INTO `__PREFIX__district` VALUES ('530600', '昭通市', '530000');
INSERT INTO `__PREFIX__district` VALUES ('530601', '市辖区', '530600');
INSERT INTO `__PREFIX__district` VALUES ('530602', '昭阳区', '530600');
INSERT INTO `__PREFIX__district` VALUES ('530621', '鲁甸县', '530600');
INSERT INTO `__PREFIX__district` VALUES ('530622', '巧家县', '530600');
INSERT INTO `__PREFIX__district` VALUES ('530623', '盐津县', '530600');
INSERT INTO `__PREFIX__district` VALUES ('530624', '大关县', '530600');
INSERT INTO `__PREFIX__district` VALUES ('530625', '永善县', '530600');
INSERT INTO `__PREFIX__district` VALUES ('530626', '绥江县', '530600');
INSERT INTO `__PREFIX__district` VALUES ('530627', '镇雄县', '530600');
INSERT INTO `__PREFIX__district` VALUES ('530628', '彝良县', '530600');
INSERT INTO `__PREFIX__district` VALUES ('530629', '威信县', '530600');
INSERT INTO `__PREFIX__district` VALUES ('530630', '水富县', '530600');
INSERT INTO `__PREFIX__district` VALUES ('530700', '丽江市', '530000');
INSERT INTO `__PREFIX__district` VALUES ('530701', '市辖区', '530700');
INSERT INTO `__PREFIX__district` VALUES ('530702', '古城区', '530700');
INSERT INTO `__PREFIX__district` VALUES ('530721', '玉龙纳西族自治县', '530700');
INSERT INTO `__PREFIX__district` VALUES ('530722', '永胜县', '530700');
INSERT INTO `__PREFIX__district` VALUES ('530723', '华坪县', '530700');
INSERT INTO `__PREFIX__district` VALUES ('530724', '宁蒗彝族自治县', '530700');
INSERT INTO `__PREFIX__district` VALUES ('530800', '普洱市', '530000');
INSERT INTO `__PREFIX__district` VALUES ('530801', '市辖区', '530800');
INSERT INTO `__PREFIX__district` VALUES ('530802', '思茅区', '530800');
INSERT INTO `__PREFIX__district` VALUES ('530821', '宁洱哈尼族彝族自治县', '530800');
INSERT INTO `__PREFIX__district` VALUES ('530822', '墨江哈尼族自治县', '530800');
INSERT INTO `__PREFIX__district` VALUES ('530823', '景东彝族自治县', '530800');
INSERT INTO `__PREFIX__district` VALUES ('530824', '景谷傣族彝族自治县', '530800');
INSERT INTO `__PREFIX__district` VALUES ('530825', '镇沅彝族哈尼族拉祜族自治县', '530800');
INSERT INTO `__PREFIX__district` VALUES ('530826', '江城哈尼族彝族自治县', '530800');
INSERT INTO `__PREFIX__district` VALUES ('530827', '孟连傣族拉祜族佤族自治县', '530800');
INSERT INTO `__PREFIX__district` VALUES ('530828', '澜沧拉祜族自治县', '530800');
INSERT INTO `__PREFIX__district` VALUES ('530829', '西盟佤族自治县', '530800');
INSERT INTO `__PREFIX__district` VALUES ('530900', '临沧市', '530000');
INSERT INTO `__PREFIX__district` VALUES ('530901', '市辖区', '530900');
INSERT INTO `__PREFIX__district` VALUES ('530902', '临翔区', '530900');
INSERT INTO `__PREFIX__district` VALUES ('530921', '凤庆县', '530900');
INSERT INTO `__PREFIX__district` VALUES ('530922', '云县', '530900');
INSERT INTO `__PREFIX__district` VALUES ('530923', '永德县', '530900');
INSERT INTO `__PREFIX__district` VALUES ('530924', '镇康县', '530900');
INSERT INTO `__PREFIX__district` VALUES ('530925', '双江拉祜族佤族布朗族傣族自治县', '530900');
INSERT INTO `__PREFIX__district` VALUES ('530926', '耿马傣族佤族自治县', '530900');
INSERT INTO `__PREFIX__district` VALUES ('530927', '沧源佤族自治县', '530900');
INSERT INTO `__PREFIX__district` VALUES ('532300', '楚雄彝族自治州', '530000');
INSERT INTO `__PREFIX__district` VALUES ('532301', '楚雄市', '532300');
INSERT INTO `__PREFIX__district` VALUES ('532322', '双柏县', '532300');
INSERT INTO `__PREFIX__district` VALUES ('532323', '牟定县', '532300');
INSERT INTO `__PREFIX__district` VALUES ('532324', '南华县', '532300');
INSERT INTO `__PREFIX__district` VALUES ('532325', '姚安县', '532300');
INSERT INTO `__PREFIX__district` VALUES ('532326', '大姚县', '532300');
INSERT INTO `__PREFIX__district` VALUES ('532327', '永仁县', '532300');
INSERT INTO `__PREFIX__district` VALUES ('532328', '元谋县', '532300');
INSERT INTO `__PREFIX__district` VALUES ('532329', '武定县', '532300');
INSERT INTO `__PREFIX__district` VALUES ('532331', '禄丰县', '532300');
INSERT INTO `__PREFIX__district` VALUES ('532500', '红河哈尼族彝族自治州', '530000');
INSERT INTO `__PREFIX__district` VALUES ('532501', '个旧市', '532500');
INSERT INTO `__PREFIX__district` VALUES ('532502', '开远市', '532500');
INSERT INTO `__PREFIX__district` VALUES ('532503', '蒙自市', '532500');
INSERT INTO `__PREFIX__district` VALUES ('532504', '弥勒市', '532500');
INSERT INTO `__PREFIX__district` VALUES ('532523', '屏边苗族自治县', '532500');
INSERT INTO `__PREFIX__district` VALUES ('532524', '建水县', '532500');
INSERT INTO `__PREFIX__district` VALUES ('532525', '石屏县', '532500');
INSERT INTO `__PREFIX__district` VALUES ('532527', '泸西县', '532500');
INSERT INTO `__PREFIX__district` VALUES ('532528', '元阳县', '532500');
INSERT INTO `__PREFIX__district` VALUES ('532529', '红河县', '532500');
INSERT INTO `__PREFIX__district` VALUES ('532530', '金平苗族瑶族傣族自治县', '532500');
INSERT INTO `__PREFIX__district` VALUES ('532531', '绿春县', '532500');
INSERT INTO `__PREFIX__district` VALUES ('532532', '河口瑶族自治县', '532500');
INSERT INTO `__PREFIX__district` VALUES ('532600', '文山壮族苗族自治州', '530000');
INSERT INTO `__PREFIX__district` VALUES ('532601', '文山市', '532600');
INSERT INTO `__PREFIX__district` VALUES ('532622', '砚山县', '532600');
INSERT INTO `__PREFIX__district` VALUES ('532623', '西畴县', '532600');
INSERT INTO `__PREFIX__district` VALUES ('532624', '麻栗坡县', '532600');
INSERT INTO `__PREFIX__district` VALUES ('532625', '马关县', '532600');
INSERT INTO `__PREFIX__district` VALUES ('532626', '丘北县', '532600');
INSERT INTO `__PREFIX__district` VALUES ('532627', '广南县', '532600');
INSERT INTO `__PREFIX__district` VALUES ('532628', '富宁县', '532600');
INSERT INTO `__PREFIX__district` VALUES ('532800', '西双版纳傣族自治州', '530000');
INSERT INTO `__PREFIX__district` VALUES ('532801', '景洪市', '532800');
INSERT INTO `__PREFIX__district` VALUES ('532822', '勐海县', '532800');
INSERT INTO `__PREFIX__district` VALUES ('532823', '勐腊县', '532800');
INSERT INTO `__PREFIX__district` VALUES ('532900', '大理白族自治州', '530000');
INSERT INTO `__PREFIX__district` VALUES ('532901', '大理市', '532900');
INSERT INTO `__PREFIX__district` VALUES ('532922', '漾濞彝族自治县', '532900');
INSERT INTO `__PREFIX__district` VALUES ('532923', '祥云县', '532900');
INSERT INTO `__PREFIX__district` VALUES ('532924', '宾川县', '532900');
INSERT INTO `__PREFIX__district` VALUES ('532925', '弥渡县', '532900');
INSERT INTO `__PREFIX__district` VALUES ('532926', '南涧彝族自治县', '532900');
INSERT INTO `__PREFIX__district` VALUES ('532927', '巍山彝族回族自治县', '532900');
INSERT INTO `__PREFIX__district` VALUES ('532928', '永平县', '532900');
INSERT INTO `__PREFIX__district` VALUES ('532929', '云龙县', '532900');
INSERT INTO `__PREFIX__district` VALUES ('532930', '洱源县', '532900');
INSERT INTO `__PREFIX__district` VALUES ('532931', '剑川县', '532900');
INSERT INTO `__PREFIX__district` VALUES ('532932', '鹤庆县', '532900');
INSERT INTO `__PREFIX__district` VALUES ('533100', '德宏傣族景颇族自治州', '530000');
INSERT INTO `__PREFIX__district` VALUES ('533102', '瑞丽市', '533100');
INSERT INTO `__PREFIX__district` VALUES ('533103', '芒市', '533100');
INSERT INTO `__PREFIX__district` VALUES ('533122', '梁河县', '533100');
INSERT INTO `__PREFIX__district` VALUES ('533123', '盈江县', '533100');
INSERT INTO `__PREFIX__district` VALUES ('533124', '陇川县', '533100');
INSERT INTO `__PREFIX__district` VALUES ('533300', '怒江傈僳族自治州', '530000');
INSERT INTO `__PREFIX__district` VALUES ('533321', '泸水县', '533300');
INSERT INTO `__PREFIX__district` VALUES ('533323', '福贡县', '533300');
INSERT INTO `__PREFIX__district` VALUES ('533324', '贡山独龙族怒族自治县', '533300');
INSERT INTO `__PREFIX__district` VALUES ('533325', '兰坪白族普米族自治县', '533300');
INSERT INTO `__PREFIX__district` VALUES ('533400', '迪庆藏族自治州', '530000');
INSERT INTO `__PREFIX__district` VALUES ('533401', '香格里拉市', '533400');
INSERT INTO `__PREFIX__district` VALUES ('533422', '德钦县', '533400');
INSERT INTO `__PREFIX__district` VALUES ('533423', '维西傈僳族自治县', '533400');
INSERT INTO `__PREFIX__district` VALUES ('540000', '西藏自治区', '0');
INSERT INTO `__PREFIX__district` VALUES ('540100', '拉萨市', '540000');
INSERT INTO `__PREFIX__district` VALUES ('540101', '市辖区', '540100');
INSERT INTO `__PREFIX__district` VALUES ('540102', '城关区', '540100');
INSERT INTO `__PREFIX__district` VALUES ('540121', '林周县', '540100');
INSERT INTO `__PREFIX__district` VALUES ('540122', '当雄县', '540100');
INSERT INTO `__PREFIX__district` VALUES ('540123', '尼木县', '540100');
INSERT INTO `__PREFIX__district` VALUES ('540124', '曲水县', '540100');
INSERT INTO `__PREFIX__district` VALUES ('540125', '堆龙德庆县', '540100');
INSERT INTO `__PREFIX__district` VALUES ('540126', '达孜县', '540100');
INSERT INTO `__PREFIX__district` VALUES ('540127', '墨竹工卡县', '540100');
INSERT INTO `__PREFIX__district` VALUES ('540200', '日喀则市', '540000');
INSERT INTO `__PREFIX__district` VALUES ('540201', '市辖区', '540200');
INSERT INTO `__PREFIX__district` VALUES ('540202', '桑珠孜区', '540200');
INSERT INTO `__PREFIX__district` VALUES ('540221', '南木林县', '540200');
INSERT INTO `__PREFIX__district` VALUES ('540222', '江孜县', '540200');
INSERT INTO `__PREFIX__district` VALUES ('540223', '定日县', '540200');
INSERT INTO `__PREFIX__district` VALUES ('540224', '萨迦县', '540200');
INSERT INTO `__PREFIX__district` VALUES ('540225', '拉孜县', '540200');
INSERT INTO `__PREFIX__district` VALUES ('540226', '昂仁县', '540200');
INSERT INTO `__PREFIX__district` VALUES ('540227', '谢通门县', '540200');
INSERT INTO `__PREFIX__district` VALUES ('540228', '白朗县', '540200');
INSERT INTO `__PREFIX__district` VALUES ('540229', '仁布县', '540200');
INSERT INTO `__PREFIX__district` VALUES ('540230', '康马县', '540200');
INSERT INTO `__PREFIX__district` VALUES ('540231', '定结县', '540200');
INSERT INTO `__PREFIX__district` VALUES ('540232', '仲巴县', '540200');
INSERT INTO `__PREFIX__district` VALUES ('540233', '亚东县', '540200');
INSERT INTO `__PREFIX__district` VALUES ('540234', '吉隆县', '540200');
INSERT INTO `__PREFIX__district` VALUES ('540235', '聂拉木县', '540200');
INSERT INTO `__PREFIX__district` VALUES ('540236', '萨嘎县', '540200');
INSERT INTO `__PREFIX__district` VALUES ('540237', '岗巴县', '540200');
INSERT INTO `__PREFIX__district` VALUES ('540300', '昌都市', '540000');
INSERT INTO `__PREFIX__district` VALUES ('540301', '市辖区', '540300');
INSERT INTO `__PREFIX__district` VALUES ('540302', '卡若区', '540300');
INSERT INTO `__PREFIX__district` VALUES ('540321', '江达县', '540300');
INSERT INTO `__PREFIX__district` VALUES ('540322', '贡觉县', '540300');
INSERT INTO `__PREFIX__district` VALUES ('540323', '类乌齐县', '540300');
INSERT INTO `__PREFIX__district` VALUES ('540324', '丁青县', '540300');
INSERT INTO `__PREFIX__district` VALUES ('540325', '察雅县', '540300');
INSERT INTO `__PREFIX__district` VALUES ('540326', '八宿县', '540300');
INSERT INTO `__PREFIX__district` VALUES ('540327', '左贡县', '540300');
INSERT INTO `__PREFIX__district` VALUES ('540328', '芒康县', '540300');
INSERT INTO `__PREFIX__district` VALUES ('540329', '洛隆县', '540300');
INSERT INTO `__PREFIX__district` VALUES ('540330', '边坝县', '540300');
INSERT INTO `__PREFIX__district` VALUES ('542200', '山南地区', '540000');
INSERT INTO `__PREFIX__district` VALUES ('542221', '乃东县', '542200');
INSERT INTO `__PREFIX__district` VALUES ('542222', '扎囊县', '542200');
INSERT INTO `__PREFIX__district` VALUES ('542223', '贡嘎县', '542200');
INSERT INTO `__PREFIX__district` VALUES ('542224', '桑日县', '542200');
INSERT INTO `__PREFIX__district` VALUES ('542225', '琼结县', '542200');
INSERT INTO `__PREFIX__district` VALUES ('542226', '曲松县', '542200');
INSERT INTO `__PREFIX__district` VALUES ('542227', '措美县', '542200');
INSERT INTO `__PREFIX__district` VALUES ('542228', '洛扎县', '542200');
INSERT INTO `__PREFIX__district` VALUES ('542229', '加查县', '542200');
INSERT INTO `__PREFIX__district` VALUES ('542231', '隆子县', '542200');
INSERT INTO `__PREFIX__district` VALUES ('542232', '错那县', '542200');
INSERT INTO `__PREFIX__district` VALUES ('542233', '浪卡子县', '542200');
INSERT INTO `__PREFIX__district` VALUES ('542400', '那曲地区', '540000');
INSERT INTO `__PREFIX__district` VALUES ('542421', '那曲县', '542400');
INSERT INTO `__PREFIX__district` VALUES ('542422', '嘉黎县', '542400');
INSERT INTO `__PREFIX__district` VALUES ('542423', '比如县', '542400');
INSERT INTO `__PREFIX__district` VALUES ('542424', '聂荣县', '542400');
INSERT INTO `__PREFIX__district` VALUES ('542425', '安多县', '542400');
INSERT INTO `__PREFIX__district` VALUES ('542426', '申扎县', '542400');
INSERT INTO `__PREFIX__district` VALUES ('542427', '索县', '542400');
INSERT INTO `__PREFIX__district` VALUES ('542428', '班戈县', '542400');
INSERT INTO `__PREFIX__district` VALUES ('542429', '巴青县', '542400');
INSERT INTO `__PREFIX__district` VALUES ('542430', '尼玛县', '542400');
INSERT INTO `__PREFIX__district` VALUES ('542431', '双湖县', '542400');
INSERT INTO `__PREFIX__district` VALUES ('542500', '阿里地区', '540000');
INSERT INTO `__PREFIX__district` VALUES ('542521', '普兰县', '542500');
INSERT INTO `__PREFIX__district` VALUES ('542522', '札达县', '542500');
INSERT INTO `__PREFIX__district` VALUES ('542523', '噶尔县', '542500');
INSERT INTO `__PREFIX__district` VALUES ('542524', '日土县', '542500');
INSERT INTO `__PREFIX__district` VALUES ('542525', '革吉县', '542500');
INSERT INTO `__PREFIX__district` VALUES ('542526', '改则县', '542500');
INSERT INTO `__PREFIX__district` VALUES ('542527', '措勤县', '542500');
INSERT INTO `__PREFIX__district` VALUES ('542600', '林芝地区', '540000');
INSERT INTO `__PREFIX__district` VALUES ('542621', '林芝县', '542600');
INSERT INTO `__PREFIX__district` VALUES ('542622', '工布江达县', '542600');
INSERT INTO `__PREFIX__district` VALUES ('542623', '米林县', '542600');
INSERT INTO `__PREFIX__district` VALUES ('542624', '墨脱县', '542600');
INSERT INTO `__PREFIX__district` VALUES ('542625', '波密县', '542600');
INSERT INTO `__PREFIX__district` VALUES ('542626', '察隅县', '542600');
INSERT INTO `__PREFIX__district` VALUES ('542627', '朗县', '542600');
INSERT INTO `__PREFIX__district` VALUES ('610000', '陕西省', '0');
INSERT INTO `__PREFIX__district` VALUES ('610100', '西安市', '610000');
INSERT INTO `__PREFIX__district` VALUES ('610101', '市辖区', '610100');
INSERT INTO `__PREFIX__district` VALUES ('610102', '新城区', '610100');
INSERT INTO `__PREFIX__district` VALUES ('610103', '碑林区', '610100');
INSERT INTO `__PREFIX__district` VALUES ('610104', '莲湖区', '610100');
INSERT INTO `__PREFIX__district` VALUES ('610111', '灞桥区', '610100');
INSERT INTO `__PREFIX__district` VALUES ('610112', '未央区', '610100');
INSERT INTO `__PREFIX__district` VALUES ('610113', '雁塔区', '610100');
INSERT INTO `__PREFIX__district` VALUES ('610114', '阎良区', '610100');
INSERT INTO `__PREFIX__district` VALUES ('610115', '临潼区', '610100');
INSERT INTO `__PREFIX__district` VALUES ('610116', '长安区', '610100');
INSERT INTO `__PREFIX__district` VALUES ('610117', '高陵区', '610100');
INSERT INTO `__PREFIX__district` VALUES ('610122', '蓝田县', '610100');
INSERT INTO `__PREFIX__district` VALUES ('610124', '周至县', '610100');
INSERT INTO `__PREFIX__district` VALUES ('610125', '户县', '610100');
INSERT INTO `__PREFIX__district` VALUES ('610200', '铜川市', '610000');
INSERT INTO `__PREFIX__district` VALUES ('610201', '市辖区', '610200');
INSERT INTO `__PREFIX__district` VALUES ('610202', '王益区', '610200');
INSERT INTO `__PREFIX__district` VALUES ('610203', '印台区', '610200');
INSERT INTO `__PREFIX__district` VALUES ('610204', '耀州区', '610200');
INSERT INTO `__PREFIX__district` VALUES ('610222', '宜君县', '610200');
INSERT INTO `__PREFIX__district` VALUES ('610300', '宝鸡市', '610000');
INSERT INTO `__PREFIX__district` VALUES ('610301', '市辖区', '610300');
INSERT INTO `__PREFIX__district` VALUES ('610302', '渭滨区', '610300');
INSERT INTO `__PREFIX__district` VALUES ('610303', '金台区', '610300');
INSERT INTO `__PREFIX__district` VALUES ('610304', '陈仓区', '610300');
INSERT INTO `__PREFIX__district` VALUES ('610322', '凤翔县', '610300');
INSERT INTO `__PREFIX__district` VALUES ('610323', '岐山县', '610300');
INSERT INTO `__PREFIX__district` VALUES ('610324', '扶风县', '610300');
INSERT INTO `__PREFIX__district` VALUES ('610326', '眉县', '610300');
INSERT INTO `__PREFIX__district` VALUES ('610327', '陇县', '610300');
INSERT INTO `__PREFIX__district` VALUES ('610328', '千阳县', '610300');
INSERT INTO `__PREFIX__district` VALUES ('610329', '麟游县', '610300');
INSERT INTO `__PREFIX__district` VALUES ('610330', '凤县', '610300');
INSERT INTO `__PREFIX__district` VALUES ('610331', '太白县', '610300');
INSERT INTO `__PREFIX__district` VALUES ('610400', '咸阳市', '610000');
INSERT INTO `__PREFIX__district` VALUES ('610401', '市辖区', '610400');
INSERT INTO `__PREFIX__district` VALUES ('610402', '秦都区', '610400');
INSERT INTO `__PREFIX__district` VALUES ('610403', '杨陵区', '610400');
INSERT INTO `__PREFIX__district` VALUES ('610404', '渭城区', '610400');
INSERT INTO `__PREFIX__district` VALUES ('610422', '三原县', '610400');
INSERT INTO `__PREFIX__district` VALUES ('610423', '泾阳县', '610400');
INSERT INTO `__PREFIX__district` VALUES ('610424', '乾县', '610400');
INSERT INTO `__PREFIX__district` VALUES ('610425', '礼泉县', '610400');
INSERT INTO `__PREFIX__district` VALUES ('610426', '永寿县', '610400');
INSERT INTO `__PREFIX__district` VALUES ('610427', '彬县', '610400');
INSERT INTO `__PREFIX__district` VALUES ('610428', '长武县', '610400');
INSERT INTO `__PREFIX__district` VALUES ('610429', '旬邑县', '610400');
INSERT INTO `__PREFIX__district` VALUES ('610430', '淳化县', '610400');
INSERT INTO `__PREFIX__district` VALUES ('610431', '武功县', '610400');
INSERT INTO `__PREFIX__district` VALUES ('610481', '兴平市', '610400');
INSERT INTO `__PREFIX__district` VALUES ('610500', '渭南市', '610000');
INSERT INTO `__PREFIX__district` VALUES ('610501', '市辖区', '610500');
INSERT INTO `__PREFIX__district` VALUES ('610502', '临渭区', '610500');
INSERT INTO `__PREFIX__district` VALUES ('610521', '华县', '610500');
INSERT INTO `__PREFIX__district` VALUES ('610522', '潼关县', '610500');
INSERT INTO `__PREFIX__district` VALUES ('610523', '大荔县', '610500');
INSERT INTO `__PREFIX__district` VALUES ('610524', '合阳县', '610500');
INSERT INTO `__PREFIX__district` VALUES ('610525', '澄城县', '610500');
INSERT INTO `__PREFIX__district` VALUES ('610526', '蒲城县', '610500');
INSERT INTO `__PREFIX__district` VALUES ('610527', '白水县', '610500');
INSERT INTO `__PREFIX__district` VALUES ('610528', '富平县', '610500');
INSERT INTO `__PREFIX__district` VALUES ('610581', '韩城市', '610500');
INSERT INTO `__PREFIX__district` VALUES ('610582', '华阴市', '610500');
INSERT INTO `__PREFIX__district` VALUES ('610600', '延安市', '610000');
INSERT INTO `__PREFIX__district` VALUES ('610601', '市辖区', '610600');
INSERT INTO `__PREFIX__district` VALUES ('610602', '宝塔区', '610600');
INSERT INTO `__PREFIX__district` VALUES ('610621', '延长县', '610600');
INSERT INTO `__PREFIX__district` VALUES ('610622', '延川县', '610600');
INSERT INTO `__PREFIX__district` VALUES ('610623', '子长县', '610600');
INSERT INTO `__PREFIX__district` VALUES ('610624', '安塞县', '610600');
INSERT INTO `__PREFIX__district` VALUES ('610625', '志丹县', '610600');
INSERT INTO `__PREFIX__district` VALUES ('610626', '吴起县', '610600');
INSERT INTO `__PREFIX__district` VALUES ('610627', '甘泉县', '610600');
INSERT INTO `__PREFIX__district` VALUES ('610628', '富县', '610600');
INSERT INTO `__PREFIX__district` VALUES ('610629', '洛川县', '610600');
INSERT INTO `__PREFIX__district` VALUES ('610630', '宜川县', '610600');
INSERT INTO `__PREFIX__district` VALUES ('610631', '黄龙县', '610600');
INSERT INTO `__PREFIX__district` VALUES ('610632', '黄陵县', '610600');
INSERT INTO `__PREFIX__district` VALUES ('610700', '汉中市', '610000');
INSERT INTO `__PREFIX__district` VALUES ('610701', '市辖区', '610700');
INSERT INTO `__PREFIX__district` VALUES ('610702', '汉台区', '610700');
INSERT INTO `__PREFIX__district` VALUES ('610721', '南郑县', '610700');
INSERT INTO `__PREFIX__district` VALUES ('610722', '城固县', '610700');
INSERT INTO `__PREFIX__district` VALUES ('610723', '洋县', '610700');
INSERT INTO `__PREFIX__district` VALUES ('610724', '西乡县', '610700');
INSERT INTO `__PREFIX__district` VALUES ('610725', '勉县', '610700');
INSERT INTO `__PREFIX__district` VALUES ('610726', '宁强县', '610700');
INSERT INTO `__PREFIX__district` VALUES ('610727', '略阳县', '610700');
INSERT INTO `__PREFIX__district` VALUES ('610728', '镇巴县', '610700');
INSERT INTO `__PREFIX__district` VALUES ('610729', '留坝县', '610700');
INSERT INTO `__PREFIX__district` VALUES ('610730', '佛坪县', '610700');
INSERT INTO `__PREFIX__district` VALUES ('610800', '榆林市', '610000');
INSERT INTO `__PREFIX__district` VALUES ('610801', '市辖区', '610800');
INSERT INTO `__PREFIX__district` VALUES ('610802', '榆阳区', '610800');
INSERT INTO `__PREFIX__district` VALUES ('610803', '高新区', '610800');
INSERT INTO `__PREFIX__district` VALUES ('610821', '神木县', '610800');
INSERT INTO `__PREFIX__district` VALUES ('610822', '府谷县', '610800');
INSERT INTO `__PREFIX__district` VALUES ('610823', '横山县', '610800');
INSERT INTO `__PREFIX__district` VALUES ('610824', '靖边县', '610800');
INSERT INTO `__PREFIX__district` VALUES ('610825', '定边县', '610800');
INSERT INTO `__PREFIX__district` VALUES ('610826', '绥德县', '610800');
INSERT INTO `__PREFIX__district` VALUES ('610827', '米脂县', '610800');
INSERT INTO `__PREFIX__district` VALUES ('610828', '佳县', '610800');
INSERT INTO `__PREFIX__district` VALUES ('610829', '吴堡县', '610800');
INSERT INTO `__PREFIX__district` VALUES ('610830', '清涧县', '610800');
INSERT INTO `__PREFIX__district` VALUES ('610831', '子洲县', '610800');
INSERT INTO `__PREFIX__district` VALUES ('610900', '安康市', '610000');
INSERT INTO `__PREFIX__district` VALUES ('610901', '市辖区', '610900');
INSERT INTO `__PREFIX__district` VALUES ('610921', '汉阴县', '610900');
INSERT INTO `__PREFIX__district` VALUES ('610922', '石泉县', '610900');
INSERT INTO `__PREFIX__district` VALUES ('610923', '宁陕县', '610900');
INSERT INTO `__PREFIX__district` VALUES ('610924', '紫阳县', '610900');
INSERT INTO `__PREFIX__district` VALUES ('610925', '岚皋县', '610900');
INSERT INTO `__PREFIX__district` VALUES ('610926', '平利县', '610900');
INSERT INTO `__PREFIX__district` VALUES ('610927', '镇坪县', '610900');
INSERT INTO `__PREFIX__district` VALUES ('610928', '旬阳县', '610900');
INSERT INTO `__PREFIX__district` VALUES ('610929', '白河县', '610900');
INSERT INTO `__PREFIX__district` VALUES ('611000', '商洛市', '610000');
INSERT INTO `__PREFIX__district` VALUES ('611001', '市辖区', '611000');
INSERT INTO `__PREFIX__district` VALUES ('611002', '商州区', '611000');
INSERT INTO `__PREFIX__district` VALUES ('611021', '洛南县', '611000');
INSERT INTO `__PREFIX__district` VALUES ('611022', '丹凤县', '611000');
INSERT INTO `__PREFIX__district` VALUES ('611023', '商南县', '611000');
INSERT INTO `__PREFIX__district` VALUES ('611024', '山阳县', '611000');
INSERT INTO `__PREFIX__district` VALUES ('611025', '镇安县', '611000');
INSERT INTO `__PREFIX__district` VALUES ('611026', '柞水县', '611000');
INSERT INTO `__PREFIX__district` VALUES ('620000', '甘肃省', '0');
INSERT INTO `__PREFIX__district` VALUES ('620100', '兰州市', '620000');
INSERT INTO `__PREFIX__district` VALUES ('620101', '市辖区', '620100');
INSERT INTO `__PREFIX__district` VALUES ('620102', '城关区', '620100');
INSERT INTO `__PREFIX__district` VALUES ('620103', '七里河区', '620100');
INSERT INTO `__PREFIX__district` VALUES ('620104', '西固区', '620100');
INSERT INTO `__PREFIX__district` VALUES ('620105', '安宁区', '620100');
INSERT INTO `__PREFIX__district` VALUES ('620111', '红古区', '620100');
INSERT INTO `__PREFIX__district` VALUES ('620121', '永登县', '620100');
INSERT INTO `__PREFIX__district` VALUES ('620122', '皋兰县', '620100');
INSERT INTO `__PREFIX__district` VALUES ('620123', '榆中县', '620100');
INSERT INTO `__PREFIX__district` VALUES ('620200', '嘉峪关市', '620000');
INSERT INTO `__PREFIX__district` VALUES ('620201', '市辖区', '620200');
INSERT INTO `__PREFIX__district` VALUES ('620300', '金昌市', '620000');
INSERT INTO `__PREFIX__district` VALUES ('620301', '市辖区', '620300');
INSERT INTO `__PREFIX__district` VALUES ('620302', '金川区', '620300');
INSERT INTO `__PREFIX__district` VALUES ('620321', '永昌县', '620300');
INSERT INTO `__PREFIX__district` VALUES ('620400', '白银市', '620000');
INSERT INTO `__PREFIX__district` VALUES ('620401', '市辖区', '620400');
INSERT INTO `__PREFIX__district` VALUES ('620402', '白银区', '620400');
INSERT INTO `__PREFIX__district` VALUES ('620403', '平川区', '620400');
INSERT INTO `__PREFIX__district` VALUES ('620421', '靖远县', '620400');
INSERT INTO `__PREFIX__district` VALUES ('620422', '会宁县', '620400');
INSERT INTO `__PREFIX__district` VALUES ('620423', '景泰县', '620400');
INSERT INTO `__PREFIX__district` VALUES ('620500', '天水市', '620000');
INSERT INTO `__PREFIX__district` VALUES ('620501', '市辖区', '620500');
INSERT INTO `__PREFIX__district` VALUES ('620502', '秦州区', '620500');
INSERT INTO `__PREFIX__district` VALUES ('620503', '麦积区', '620500');
INSERT INTO `__PREFIX__district` VALUES ('620521', '清水县', '620500');
INSERT INTO `__PREFIX__district` VALUES ('620522', '秦安县', '620500');
INSERT INTO `__PREFIX__district` VALUES ('620523', '甘谷县', '620500');
INSERT INTO `__PREFIX__district` VALUES ('620524', '武山县', '620500');
INSERT INTO `__PREFIX__district` VALUES ('620525', '张家川回族自治县', '620500');
INSERT INTO `__PREFIX__district` VALUES ('620600', '武威市', '620000');
INSERT INTO `__PREFIX__district` VALUES ('620601', '市辖区', '620600');
INSERT INTO `__PREFIX__district` VALUES ('620602', '凉州区', '620600');
INSERT INTO `__PREFIX__district` VALUES ('620621', '民勤县', '620600');
INSERT INTO `__PREFIX__district` VALUES ('620622', '古浪县', '620600');
INSERT INTO `__PREFIX__district` VALUES ('620623', '天祝藏族自治县', '620600');
INSERT INTO `__PREFIX__district` VALUES ('620700', '张掖市', '620000');
INSERT INTO `__PREFIX__district` VALUES ('620701', '市辖区', '620700');
INSERT INTO `__PREFIX__district` VALUES ('620702', '甘州区', '620700');
INSERT INTO `__PREFIX__district` VALUES ('620721', '肃南裕固族自治县', '620700');
INSERT INTO `__PREFIX__district` VALUES ('620722', '民乐县', '620700');
INSERT INTO `__PREFIX__district` VALUES ('620723', '临泽县', '620700');
INSERT INTO `__PREFIX__district` VALUES ('620724', '高台县', '620700');
INSERT INTO `__PREFIX__district` VALUES ('620725', '山丹县', '620700');
INSERT INTO `__PREFIX__district` VALUES ('620800', '平凉市', '620000');
INSERT INTO `__PREFIX__district` VALUES ('620801', '市辖区', '620800');
INSERT INTO `__PREFIX__district` VALUES ('620802', '崆峒区', '620800');
INSERT INTO `__PREFIX__district` VALUES ('620821', '泾川县', '620800');
INSERT INTO `__PREFIX__district` VALUES ('620822', '灵台县', '620800');
INSERT INTO `__PREFIX__district` VALUES ('620823', '崇信县', '620800');
INSERT INTO `__PREFIX__district` VALUES ('620824', '华亭县', '620800');
INSERT INTO `__PREFIX__district` VALUES ('620825', '庄浪县', '620800');
INSERT INTO `__PREFIX__district` VALUES ('620826', '静宁县', '620800');
INSERT INTO `__PREFIX__district` VALUES ('620900', '酒泉市', '620000');
INSERT INTO `__PREFIX__district` VALUES ('620901', '市辖区', '620900');
INSERT INTO `__PREFIX__district` VALUES ('620902', '肃州区', '620900');
INSERT INTO `__PREFIX__district` VALUES ('620921', '金塔县', '620900');
INSERT INTO `__PREFIX__district` VALUES ('620922', '瓜州县', '620900');
INSERT INTO `__PREFIX__district` VALUES ('620923', '肃北蒙古族自治县', '620900');
INSERT INTO `__PREFIX__district` VALUES ('620924', '阿克塞哈萨克族自治县', '620900');
INSERT INTO `__PREFIX__district` VALUES ('620981', '玉门市', '620900');
INSERT INTO `__PREFIX__district` VALUES ('620982', '敦煌市', '620900');
INSERT INTO `__PREFIX__district` VALUES ('621000', '庆阳市', '620000');
INSERT INTO `__PREFIX__district` VALUES ('621001', '市辖区', '621000');
INSERT INTO `__PREFIX__district` VALUES ('621002', '西峰区', '621000');
INSERT INTO `__PREFIX__district` VALUES ('621021', '庆城县', '621000');
INSERT INTO `__PREFIX__district` VALUES ('621022', '环县', '621000');
INSERT INTO `__PREFIX__district` VALUES ('621023', '华池县', '621000');
INSERT INTO `__PREFIX__district` VALUES ('621024', '合水县', '621000');
INSERT INTO `__PREFIX__district` VALUES ('621025', '正宁县', '621000');
INSERT INTO `__PREFIX__district` VALUES ('621026', '宁县', '621000');
INSERT INTO `__PREFIX__district` VALUES ('621027', '镇原县', '621000');
INSERT INTO `__PREFIX__district` VALUES ('621100', '定西市', '620000');
INSERT INTO `__PREFIX__district` VALUES ('621101', '市辖区', '621100');
INSERT INTO `__PREFIX__district` VALUES ('621102', '安定区', '621100');
INSERT INTO `__PREFIX__district` VALUES ('621121', '通渭县', '621100');
INSERT INTO `__PREFIX__district` VALUES ('621122', '陇西县', '621100');
INSERT INTO `__PREFIX__district` VALUES ('621123', '渭源县', '621100');
INSERT INTO `__PREFIX__district` VALUES ('621124', '临洮县', '621100');
INSERT INTO `__PREFIX__district` VALUES ('621125', '漳县', '621100');
INSERT INTO `__PREFIX__district` VALUES ('621126', '岷县', '621100');
INSERT INTO `__PREFIX__district` VALUES ('621200', '陇南市', '620000');
INSERT INTO `__PREFIX__district` VALUES ('621201', '市辖区', '621200');
INSERT INTO `__PREFIX__district` VALUES ('621202', '武都区', '621200');
INSERT INTO `__PREFIX__district` VALUES ('621221', '成县', '621200');
INSERT INTO `__PREFIX__district` VALUES ('621222', '文县', '621200');
INSERT INTO `__PREFIX__district` VALUES ('621223', '宕昌县', '621200');
INSERT INTO `__PREFIX__district` VALUES ('621224', '康县', '621200');
INSERT INTO `__PREFIX__district` VALUES ('621225', '西和县', '621200');
INSERT INTO `__PREFIX__district` VALUES ('621226', '礼县', '621200');
INSERT INTO `__PREFIX__district` VALUES ('621227', '徽县', '621200');
INSERT INTO `__PREFIX__district` VALUES ('621228', '两当县', '621200');
INSERT INTO `__PREFIX__district` VALUES ('622900', '临夏回族自治州', '620000');
INSERT INTO `__PREFIX__district` VALUES ('622901', '临夏市', '622900');
INSERT INTO `__PREFIX__district` VALUES ('622921', '临夏县', '622900');
INSERT INTO `__PREFIX__district` VALUES ('622922', '康乐县', '622900');
INSERT INTO `__PREFIX__district` VALUES ('622923', '永靖县', '622900');
INSERT INTO `__PREFIX__district` VALUES ('622924', '广河县', '622900');
INSERT INTO `__PREFIX__district` VALUES ('622925', '和政县', '622900');
INSERT INTO `__PREFIX__district` VALUES ('622926', '东乡族自治县', '622900');
INSERT INTO `__PREFIX__district` VALUES ('622927', '积石山保安族东乡族撒拉族自治县', '622900');
INSERT INTO `__PREFIX__district` VALUES ('623000', '甘南藏族自治州', '620000');
INSERT INTO `__PREFIX__district` VALUES ('623001', '合作市', '623000');
INSERT INTO `__PREFIX__district` VALUES ('623021', '临潭县', '623000');
INSERT INTO `__PREFIX__district` VALUES ('623022', '卓尼县', '623000');
INSERT INTO `__PREFIX__district` VALUES ('623023', '舟曲县', '623000');
INSERT INTO `__PREFIX__district` VALUES ('623024', '迭部县', '623000');
INSERT INTO `__PREFIX__district` VALUES ('623025', '玛曲县', '623000');
INSERT INTO `__PREFIX__district` VALUES ('623026', '碌曲县', '623000');
INSERT INTO `__PREFIX__district` VALUES ('623027', '夏河县', '623000');
INSERT INTO `__PREFIX__district` VALUES ('630000', '青海省', '0');
INSERT INTO `__PREFIX__district` VALUES ('630100', '西宁市', '630000');
INSERT INTO `__PREFIX__district` VALUES ('630101', '市辖区', '630100');
INSERT INTO `__PREFIX__district` VALUES ('630102', '城东区', '630100');
INSERT INTO `__PREFIX__district` VALUES ('630103', '城中区', '630100');
INSERT INTO `__PREFIX__district` VALUES ('630104', '城西区', '630100');
INSERT INTO `__PREFIX__district` VALUES ('630105', '城北区', '630100');
INSERT INTO `__PREFIX__district` VALUES ('630121', '大通回族土族自治县', '630100');
INSERT INTO `__PREFIX__district` VALUES ('630122', '湟中县', '630100');
INSERT INTO `__PREFIX__district` VALUES ('630123', '湟源县', '630100');
INSERT INTO `__PREFIX__district` VALUES ('630200', '海东市', '630000');
INSERT INTO `__PREFIX__district` VALUES ('630201', '市辖区', '630200');
INSERT INTO `__PREFIX__district` VALUES ('630202', '乐都区', '630200');
INSERT INTO `__PREFIX__district` VALUES ('630221', '平安县', '630200');
INSERT INTO `__PREFIX__district` VALUES ('630222', '民和回族土族自治县', '630200');
INSERT INTO `__PREFIX__district` VALUES ('630223', '互助土族自治县', '630200');
INSERT INTO `__PREFIX__district` VALUES ('630224', '化隆回族自治县', '630200');
INSERT INTO `__PREFIX__district` VALUES ('630225', '循化撒拉族自治县', '630200');
INSERT INTO `__PREFIX__district` VALUES ('632200', '海北藏族自治州', '630000');
INSERT INTO `__PREFIX__district` VALUES ('632221', '门源回族自治县', '632200');
INSERT INTO `__PREFIX__district` VALUES ('632222', '祁连县', '632200');
INSERT INTO `__PREFIX__district` VALUES ('632223', '海晏县', '632200');
INSERT INTO `__PREFIX__district` VALUES ('632224', '刚察县', '632200');
INSERT INTO `__PREFIX__district` VALUES ('632300', '黄南藏族自治州', '630000');
INSERT INTO `__PREFIX__district` VALUES ('632321', '同仁县', '632300');
INSERT INTO `__PREFIX__district` VALUES ('632322', '尖扎县', '632300');
INSERT INTO `__PREFIX__district` VALUES ('632323', '泽库县', '632300');
INSERT INTO `__PREFIX__district` VALUES ('632324', '河南蒙古族自治县', '632300');
INSERT INTO `__PREFIX__district` VALUES ('632500', '海南藏族自治州', '630000');
INSERT INTO `__PREFIX__district` VALUES ('632521', '共和县', '632500');
INSERT INTO `__PREFIX__district` VALUES ('632522', '同德县', '632500');
INSERT INTO `__PREFIX__district` VALUES ('632523', '贵德县', '632500');
INSERT INTO `__PREFIX__district` VALUES ('632524', '兴海县', '632500');
INSERT INTO `__PREFIX__district` VALUES ('632525', '贵南县', '632500');
INSERT INTO `__PREFIX__district` VALUES ('632600', '果洛藏族自治州', '630000');
INSERT INTO `__PREFIX__district` VALUES ('632621', '玛沁县', '632600');
INSERT INTO `__PREFIX__district` VALUES ('632622', '班玛县', '632600');
INSERT INTO `__PREFIX__district` VALUES ('632623', '甘德县', '632600');
INSERT INTO `__PREFIX__district` VALUES ('632624', '达日县', '632600');
INSERT INTO `__PREFIX__district` VALUES ('632625', '久治县', '632600');
INSERT INTO `__PREFIX__district` VALUES ('632626', '玛多县', '632600');
INSERT INTO `__PREFIX__district` VALUES ('632700', '玉树藏族自治州', '630000');
INSERT INTO `__PREFIX__district` VALUES ('632701', '玉树市', '632700');
INSERT INTO `__PREFIX__district` VALUES ('632722', '杂多县', '632700');
INSERT INTO `__PREFIX__district` VALUES ('632723', '称多县', '632700');
INSERT INTO `__PREFIX__district` VALUES ('632724', '治多县', '632700');
INSERT INTO `__PREFIX__district` VALUES ('632725', '囊谦县', '632700');
INSERT INTO `__PREFIX__district` VALUES ('632726', '曲麻莱县', '632700');
INSERT INTO `__PREFIX__district` VALUES ('632800', '海西蒙古族藏族自治州', '630000');
INSERT INTO `__PREFIX__district` VALUES ('632801', '格尔木市', '632800');
INSERT INTO `__PREFIX__district` VALUES ('632802', '德令哈市', '632800');
INSERT INTO `__PREFIX__district` VALUES ('632821', '乌兰县', '632800');
INSERT INTO `__PREFIX__district` VALUES ('632822', '都兰县', '632800');
INSERT INTO `__PREFIX__district` VALUES ('632823', '天峻县', '632800');
INSERT INTO `__PREFIX__district` VALUES ('640000', '宁夏回族自治区', '0');
INSERT INTO `__PREFIX__district` VALUES ('640100', '银川市', '640000');
INSERT INTO `__PREFIX__district` VALUES ('640101', '市辖区', '640100');
INSERT INTO `__PREFIX__district` VALUES ('640104', '兴庆区', '640100');
INSERT INTO `__PREFIX__district` VALUES ('640105', '西夏区', '640100');
INSERT INTO `__PREFIX__district` VALUES ('640106', '金凤区', '640100');
INSERT INTO `__PREFIX__district` VALUES ('640121', '永宁县', '640100');
INSERT INTO `__PREFIX__district` VALUES ('640122', '贺兰县', '640100');
INSERT INTO `__PREFIX__district` VALUES ('640181', '灵武市', '640100');
INSERT INTO `__PREFIX__district` VALUES ('640200', '石嘴山市', '640000');
INSERT INTO `__PREFIX__district` VALUES ('640201', '市辖区', '640200');
INSERT INTO `__PREFIX__district` VALUES ('640202', '大武口区', '640200');
INSERT INTO `__PREFIX__district` VALUES ('640205', '惠农区', '640200');
INSERT INTO `__PREFIX__district` VALUES ('640221', '平罗县', '640200');
INSERT INTO `__PREFIX__district` VALUES ('640300', '吴忠市', '640000');
INSERT INTO `__PREFIX__district` VALUES ('640301', '市辖区', '640300');
INSERT INTO `__PREFIX__district` VALUES ('640302', '利通区', '640300');
INSERT INTO `__PREFIX__district` VALUES ('640303', '红寺堡区', '640300');
INSERT INTO `__PREFIX__district` VALUES ('640323', '盐池县', '640300');
INSERT INTO `__PREFIX__district` VALUES ('640324', '同心县', '640300');
INSERT INTO `__PREFIX__district` VALUES ('640381', '青铜峡市', '640300');
INSERT INTO `__PREFIX__district` VALUES ('640400', '固原市', '640000');
INSERT INTO `__PREFIX__district` VALUES ('640401', '市辖区', '640400');
INSERT INTO `__PREFIX__district` VALUES ('640402', '原州区', '640400');
INSERT INTO `__PREFIX__district` VALUES ('640422', '西吉县', '640400');
INSERT INTO `__PREFIX__district` VALUES ('640423', '隆德县', '640400');
INSERT INTO `__PREFIX__district` VALUES ('640424', '泾源县', '640400');
INSERT INTO `__PREFIX__district` VALUES ('640425', '彭阳县', '640400');
INSERT INTO `__PREFIX__district` VALUES ('640500', '中卫市', '640000');
INSERT INTO `__PREFIX__district` VALUES ('640501', '市辖区', '640500');
INSERT INTO `__PREFIX__district` VALUES ('640502', '沙坡头区', '640500');
INSERT INTO `__PREFIX__district` VALUES ('640521', '中宁县', '640500');
INSERT INTO `__PREFIX__district` VALUES ('640522', '海原县', '640500');
INSERT INTO `__PREFIX__district` VALUES ('650000', '新疆维吾尔自治区', '0');
INSERT INTO `__PREFIX__district` VALUES ('650100', '乌鲁木齐市', '650000');
INSERT INTO `__PREFIX__district` VALUES ('650101', '市辖区', '650100');
INSERT INTO `__PREFIX__district` VALUES ('650102', '天山区', '650100');
INSERT INTO `__PREFIX__district` VALUES ('650103', '沙依巴克区', '650100');
INSERT INTO `__PREFIX__district` VALUES ('650104', '新市区', '650100');
INSERT INTO `__PREFIX__district` VALUES ('650105', '水磨沟区', '650100');
INSERT INTO `__PREFIX__district` VALUES ('650106', '头屯河区', '650100');
INSERT INTO `__PREFIX__district` VALUES ('650107', '达坂城区', '650100');
INSERT INTO `__PREFIX__district` VALUES ('650109', '米东区', '650100');
INSERT INTO `__PREFIX__district` VALUES ('650121', '乌鲁木齐县', '650100');
INSERT INTO `__PREFIX__district` VALUES ('650200', '克拉玛依市', '650000');
INSERT INTO `__PREFIX__district` VALUES ('650201', '市辖区', '650200');
INSERT INTO `__PREFIX__district` VALUES ('650202', '独山子区', '650200');
INSERT INTO `__PREFIX__district` VALUES ('650203', '克拉玛依区', '650200');
INSERT INTO `__PREFIX__district` VALUES ('650204', '白碱滩区', '650200');
INSERT INTO `__PREFIX__district` VALUES ('650205', '乌尔禾区', '650200');
INSERT INTO `__PREFIX__district` VALUES ('652100', '吐鲁番地区', '650000');
INSERT INTO `__PREFIX__district` VALUES ('652101', '吐鲁番市', '652100');
INSERT INTO `__PREFIX__district` VALUES ('652122', '鄯善县', '652100');
INSERT INTO `__PREFIX__district` VALUES ('652123', '托克逊县', '652100');
INSERT INTO `__PREFIX__district` VALUES ('652200', '哈密地区', '650000');
INSERT INTO `__PREFIX__district` VALUES ('652201', '哈密市', '652200');
INSERT INTO `__PREFIX__district` VALUES ('652222', '巴里坤哈萨克自治县', '652200');
INSERT INTO `__PREFIX__district` VALUES ('652223', '伊吾县', '652200');
INSERT INTO `__PREFIX__district` VALUES ('652300', '昌吉回族自治州', '650000');
INSERT INTO `__PREFIX__district` VALUES ('652301', '昌吉市', '652300');
INSERT INTO `__PREFIX__district` VALUES ('652302', '阜康市', '652300');
INSERT INTO `__PREFIX__district` VALUES ('652323', '呼图壁县', '652300');
INSERT INTO `__PREFIX__district` VALUES ('652324', '玛纳斯县', '652300');
INSERT INTO `__PREFIX__district` VALUES ('652325', '奇台县', '652300');
INSERT INTO `__PREFIX__district` VALUES ('652327', '吉木萨尔县', '652300');
INSERT INTO `__PREFIX__district` VALUES ('652328', '木垒哈萨克自治县', '652300');
INSERT INTO `__PREFIX__district` VALUES ('652700', '博尔塔拉蒙古自治州', '650000');
INSERT INTO `__PREFIX__district` VALUES ('652701', '博乐市', '652700');
INSERT INTO `__PREFIX__district` VALUES ('652702', '阿拉山口市', '652700');
INSERT INTO `__PREFIX__district` VALUES ('652722', '精河县', '652700');
INSERT INTO `__PREFIX__district` VALUES ('652723', '温泉县', '652700');
INSERT INTO `__PREFIX__district` VALUES ('652800', '巴音郭楞蒙古自治州', '650000');
INSERT INTO `__PREFIX__district` VALUES ('652801', '库尔勒市', '652800');
INSERT INTO `__PREFIX__district` VALUES ('652822', '轮台县', '652800');
INSERT INTO `__PREFIX__district` VALUES ('652823', '尉犁县', '652800');
INSERT INTO `__PREFIX__district` VALUES ('652824', '若羌县', '652800');
INSERT INTO `__PREFIX__district` VALUES ('652825', '且末县', '652800');
INSERT INTO `__PREFIX__district` VALUES ('652826', '焉耆回族自治县', '652800');
INSERT INTO `__PREFIX__district` VALUES ('652827', '和静县', '652800');
INSERT INTO `__PREFIX__district` VALUES ('652828', '和硕县', '652800');
INSERT INTO `__PREFIX__district` VALUES ('652829', '博湖县', '652800');
INSERT INTO `__PREFIX__district` VALUES ('652900', '阿克苏地区', '650000');
INSERT INTO `__PREFIX__district` VALUES ('652901', '阿克苏市', '652900');
INSERT INTO `__PREFIX__district` VALUES ('652922', '温宿县', '652900');
INSERT INTO `__PREFIX__district` VALUES ('652923', '库车县', '652900');
INSERT INTO `__PREFIX__district` VALUES ('652924', '沙雅县', '652900');
INSERT INTO `__PREFIX__district` VALUES ('652925', '新和县', '652900');
INSERT INTO `__PREFIX__district` VALUES ('652926', '拜城县', '652900');
INSERT INTO `__PREFIX__district` VALUES ('652927', '乌什县', '652900');
INSERT INTO `__PREFIX__district` VALUES ('652928', '阿瓦提县', '652900');
INSERT INTO `__PREFIX__district` VALUES ('652929', '柯坪县', '652900');
INSERT INTO `__PREFIX__district` VALUES ('653000', '克孜勒苏柯尔克孜自治州', '650000');
INSERT INTO `__PREFIX__district` VALUES ('653001', '阿图什市', '653000');
INSERT INTO `__PREFIX__district` VALUES ('653022', '阿克陶县', '653000');
INSERT INTO `__PREFIX__district` VALUES ('653023', '阿合奇县', '653000');
INSERT INTO `__PREFIX__district` VALUES ('653024', '乌恰县', '653000');
INSERT INTO `__PREFIX__district` VALUES ('653100', '喀什地区', '650000');
INSERT INTO `__PREFIX__district` VALUES ('653101', '喀什市', '653100');
INSERT INTO `__PREFIX__district` VALUES ('653121', '疏附县', '653100');
INSERT INTO `__PREFIX__district` VALUES ('653122', '疏勒县', '653100');
INSERT INTO `__PREFIX__district` VALUES ('653123', '英吉沙县', '653100');
INSERT INTO `__PREFIX__district` VALUES ('653124', '泽普县', '653100');
INSERT INTO `__PREFIX__district` VALUES ('653125', '莎车县', '653100');
INSERT INTO `__PREFIX__district` VALUES ('653126', '叶城县', '653100');
INSERT INTO `__PREFIX__district` VALUES ('653127', '麦盖提县', '653100');
INSERT INTO `__PREFIX__district` VALUES ('653128', '岳普湖县', '653100');
INSERT INTO `__PREFIX__district` VALUES ('653129', '伽师县', '653100');
INSERT INTO `__PREFIX__district` VALUES ('653130', '巴楚县', '653100');
INSERT INTO `__PREFIX__district` VALUES ('653131', '塔什库尔干塔吉克自治县', '653100');
INSERT INTO `__PREFIX__district` VALUES ('653200', '和田地区', '650000');
INSERT INTO `__PREFIX__district` VALUES ('653201', '和田市', '653200');
INSERT INTO `__PREFIX__district` VALUES ('653221', '和田县', '653200');
INSERT INTO `__PREFIX__district` VALUES ('653222', '墨玉县', '653200');
INSERT INTO `__PREFIX__district` VALUES ('653223', '皮山县', '653200');
INSERT INTO `__PREFIX__district` VALUES ('653224', '洛浦县', '653200');
INSERT INTO `__PREFIX__district` VALUES ('653225', '策勒县', '653200');
INSERT INTO `__PREFIX__district` VALUES ('653226', '于田县', '653200');
INSERT INTO `__PREFIX__district` VALUES ('653227', '民丰县', '653200');
INSERT INTO `__PREFIX__district` VALUES ('654000', '伊犁哈萨克自治州', '650000');
INSERT INTO `__PREFIX__district` VALUES ('654002', '伊宁市', '654000');
INSERT INTO `__PREFIX__district` VALUES ('654003', '奎屯市', '654000');
INSERT INTO `__PREFIX__district` VALUES ('654004', '霍尔果斯市', '654000');
INSERT INTO `__PREFIX__district` VALUES ('654021', '伊宁县', '654000');
INSERT INTO `__PREFIX__district` VALUES ('654022', '察布查尔锡伯自治县', '654000');
INSERT INTO `__PREFIX__district` VALUES ('654023', '霍城县', '654000');
INSERT INTO `__PREFIX__district` VALUES ('654024', '巩留县', '654000');
INSERT INTO `__PREFIX__district` VALUES ('654025', '新源县', '654000');
INSERT INTO `__PREFIX__district` VALUES ('654026', '昭苏县', '654000');
INSERT INTO `__PREFIX__district` VALUES ('654027', '特克斯县', '654000');
INSERT INTO `__PREFIX__district` VALUES ('654028', '尼勒克县', '654000');
INSERT INTO `__PREFIX__district` VALUES ('654200', '塔城地区', '650000');
INSERT INTO `__PREFIX__district` VALUES ('654201', '塔城市', '654200');
INSERT INTO `__PREFIX__district` VALUES ('654202', '乌苏市', '654200');
INSERT INTO `__PREFIX__district` VALUES ('654221', '额敏县', '654200');
INSERT INTO `__PREFIX__district` VALUES ('654223', '沙湾县', '654200');
INSERT INTO `__PREFIX__district` VALUES ('654224', '托里县', '654200');
INSERT INTO `__PREFIX__district` VALUES ('654225', '裕民县', '654200');
INSERT INTO `__PREFIX__district` VALUES ('654226', '和布克赛尔蒙古自治县', '654200');
INSERT INTO `__PREFIX__district` VALUES ('654300', '阿勒泰地区', '654000');
INSERT INTO `__PREFIX__district` VALUES ('654301', '阿勒泰市', '654000');
INSERT INTO `__PREFIX__district` VALUES ('654321', '布尔津县', '654000');
INSERT INTO `__PREFIX__district` VALUES ('654322', '富蕴县', '654000');
INSERT INTO `__PREFIX__district` VALUES ('654323', '福海县', '654000');
INSERT INTO `__PREFIX__district` VALUES ('654324', '哈巴河县', '654000');
INSERT INTO `__PREFIX__district` VALUES ('654325', '青河县', '654000');
INSERT INTO `__PREFIX__district` VALUES ('654326', '吉木乃县', '654000');
INSERT INTO `__PREFIX__district` VALUES ('659000', '自治区直辖县级行政区划', '650000');
INSERT INTO `__PREFIX__district` VALUES ('659001', '石河子市', '659000');
INSERT INTO `__PREFIX__district` VALUES ('659002', '阿拉尔市', '659000');
INSERT INTO `__PREFIX__district` VALUES ('659003', '图木舒克市', '659000');
INSERT INTO `__PREFIX__district` VALUES ('659004', '五家渠市', '659000');
INSERT INTO `__PREFIX__district` VALUES ('659005', '北屯市', '659000');
INSERT INTO `__PREFIX__district` VALUES ('659006', '铁门关市', '659000');
INSERT INTO `__PREFIX__district` VALUES ('659007', '双河市', '659000');
INSERT INTO `__PREFIX__district` VALUES ('710000', '台湾省', '0');
INSERT INTO `__PREFIX__district` VALUES ('710100', '台北市', '710000');
INSERT INTO `__PREFIX__district` VALUES ('710101', '松山区', '710100');
INSERT INTO `__PREFIX__district` VALUES ('710102', '信义区', '710100');
INSERT INTO `__PREFIX__district` VALUES ('710103', '大安区', '710100');
INSERT INTO `__PREFIX__district` VALUES ('710104', '中山区', '710100');
INSERT INTO `__PREFIX__district` VALUES ('710105', '中正区', '710100');
INSERT INTO `__PREFIX__district` VALUES ('710106', '大同区', '710100');
INSERT INTO `__PREFIX__district` VALUES ('710107', '万华区', '710100');
INSERT INTO `__PREFIX__district` VALUES ('710108', '文山区', '710100');
INSERT INTO `__PREFIX__district` VALUES ('710109', '南港区', '710100');
INSERT INTO `__PREFIX__district` VALUES ('710110', '内湖区', '710100');
INSERT INTO `__PREFIX__district` VALUES ('710111', '士林区', '710100');
INSERT INTO `__PREFIX__district` VALUES ('710112', '北投区', '710100');
INSERT INTO `__PREFIX__district` VALUES ('710200', '高雄市', '710000');
INSERT INTO `__PREFIX__district` VALUES ('710201', '盐埕区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710202', '鼓山区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710203', '左营区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710204', '楠梓区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710205', '三民区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710206', '新兴区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710207', '前金区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710208', '苓雅区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710209', '前镇区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710210', '旗津区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710211', '小港区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710212', '凤山区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710213', '林园区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710214', '大寮区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710215', '大树区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710216', '大社区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710217', '仁武区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710218', '鸟松区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710219', '冈山区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710220', '桥头区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710221', '燕巢区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710222', '田寮区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710223', '阿莲区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710224', '路竹区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710225', '湖内区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710226', '茄萣区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710227', '永安区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710228', '弥陀区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710229', '梓官区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710230', '旗山区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710231', '美浓区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710232', '六龟区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710233', '甲仙区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710234', '杉林区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710235', '内门区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710236', '茂林区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710237', '桃源区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710238', '那玛夏区', '710200');
INSERT INTO `__PREFIX__district` VALUES ('710300', '基隆市', '710000');
INSERT INTO `__PREFIX__district` VALUES ('710301', '中正区', '710300');
INSERT INTO `__PREFIX__district` VALUES ('710302', '七堵区', '710300');
INSERT INTO `__PREFIX__district` VALUES ('710303', '暖暖区', '710300');
INSERT INTO `__PREFIX__district` VALUES ('710304', '仁爱区', '710300');
INSERT INTO `__PREFIX__district` VALUES ('710305', '中山区', '710300');
INSERT INTO `__PREFIX__district` VALUES ('710306', '安乐区', '710300');
INSERT INTO `__PREFIX__district` VALUES ('710307', '信义区', '710300');
INSERT INTO `__PREFIX__district` VALUES ('710400', '台中市', '710000');
INSERT INTO `__PREFIX__district` VALUES ('710401', '中区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710402', '东区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710403', '南区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710404', '西区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710405', '北区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710406', '西屯区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710407', '南屯区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710408', '北屯区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710409', '丰原区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710410', '东势区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710411', '大甲区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710412', '清水区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710413', '沙鹿区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710414', '梧栖区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710415', '后里区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710416', '神冈区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710417', '潭子区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710418', '大雅区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710419', '新社区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710420', '石冈区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710421', '外埔区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710422', '大安区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710423', '乌日区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710424', '大肚区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710425', '龙井区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710426', '雾峰区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710427', '太平区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710428', '大里区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710429', '和平区', '710400');
INSERT INTO `__PREFIX__district` VALUES ('710500', '台南市', '710000');
INSERT INTO `__PREFIX__district` VALUES ('710501', '东区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710502', '南区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710504', '北区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710506', '安南区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710507', '安平区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710508', '中西区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710509', '新营区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710510', '盐水区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710511', '白河区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710512', '柳营区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710513', '后壁区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710514', '东山区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710515', '麻豆区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710516', '下营区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710517', '六甲区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710518', '官田区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710519', '大内区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710520', '佳里区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710521', '学甲区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710522', '西港区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710523', '七股区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710524', '将军区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710525', '北门区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710526', '新化区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710527', '善化区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710528', '新市区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710529', '安定区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710530', '山上区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710531', '玉井区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710532', '楠西区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710533', '南化区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710534', '左镇区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710535', '仁德区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710536', '归仁区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710537', '关庙区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710538', '龙崎区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710539', '永康区', '710500');
INSERT INTO `__PREFIX__district` VALUES ('710600', '新竹市', '710000');
INSERT INTO `__PREFIX__district` VALUES ('710601', '东区', '710600');
INSERT INTO `__PREFIX__district` VALUES ('710602', '北区', '710600');
INSERT INTO `__PREFIX__district` VALUES ('710603', '香山区', '710600');
INSERT INTO `__PREFIX__district` VALUES ('710700', '嘉义市', '710000');
INSERT INTO `__PREFIX__district` VALUES ('710701', '东区', '710700');
INSERT INTO `__PREFIX__district` VALUES ('710702', '西区', '710700');
INSERT INTO `__PREFIX__district` VALUES ('710800', '新北市', '710000');
INSERT INTO `__PREFIX__district` VALUES ('710801', '板桥区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710802', '三重区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710803', '中和区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710804', '永和区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710805', '新庄区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710806', '新店区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710807', '树林区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710808', '莺歌区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710809', '三峡区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710810', '淡水区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710811', '汐止区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710812', '瑞芳区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710813', '土城区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710814', '芦洲区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710815', '五股区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710816', '泰山区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710817', '林口区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710818', '深坑区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710819', '石碇区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710820', '坪林区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710821', '三芝区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710822', '石门区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710823', '八里区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710824', '平溪区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710825', '双溪区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710826', '贡寮区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710827', '金山区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710828', '万里区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('710829', '乌来区', '710800');
INSERT INTO `__PREFIX__district` VALUES ('712200', '宜兰县', '710000');
INSERT INTO `__PREFIX__district` VALUES ('712201', '宜兰市', '712200');
INSERT INTO `__PREFIX__district` VALUES ('712221', '罗东镇', '712200');
INSERT INTO `__PREFIX__district` VALUES ('712222', '苏澳镇', '712200');
INSERT INTO `__PREFIX__district` VALUES ('712223', '头城镇', '712200');
INSERT INTO `__PREFIX__district` VALUES ('712224', '礁溪乡', '712200');
INSERT INTO `__PREFIX__district` VALUES ('712225', '壮围乡', '712200');
INSERT INTO `__PREFIX__district` VALUES ('712226', '员山乡', '712200');
INSERT INTO `__PREFIX__district` VALUES ('712227', '冬山乡', '712200');
INSERT INTO `__PREFIX__district` VALUES ('712228', '五结乡', '712200');
INSERT INTO `__PREFIX__district` VALUES ('712229', '三星乡', '712200');
INSERT INTO `__PREFIX__district` VALUES ('712230', '大同乡', '712200');
INSERT INTO `__PREFIX__district` VALUES ('712231', '南澳乡', '712200');
INSERT INTO `__PREFIX__district` VALUES ('712300', '桃园县', '710000');
INSERT INTO `__PREFIX__district` VALUES ('712301', '桃园市', '712300');
INSERT INTO `__PREFIX__district` VALUES ('712302', '中坜市', '712300');
INSERT INTO `__PREFIX__district` VALUES ('712303', '平镇市', '712300');
INSERT INTO `__PREFIX__district` VALUES ('712304', '八德市', '712300');
INSERT INTO `__PREFIX__district` VALUES ('712305', '杨梅市', '712300');
INSERT INTO `__PREFIX__district` VALUES ('712321', '大溪镇', '712300');
INSERT INTO `__PREFIX__district` VALUES ('712323', '芦竹乡', '712300');
INSERT INTO `__PREFIX__district` VALUES ('712324', '大园乡', '712300');
INSERT INTO `__PREFIX__district` VALUES ('712325', '龟山乡', '712300');
INSERT INTO `__PREFIX__district` VALUES ('712327', '龙潭乡', '712300');
INSERT INTO `__PREFIX__district` VALUES ('712329', '新屋乡', '712300');
INSERT INTO `__PREFIX__district` VALUES ('712330', '观音乡', '712300');
INSERT INTO `__PREFIX__district` VALUES ('712331', '复兴乡', '712300');
INSERT INTO `__PREFIX__district` VALUES ('712400', '新竹县', '710000');
INSERT INTO `__PREFIX__district` VALUES ('712401', '竹北市', '712400');
INSERT INTO `__PREFIX__district` VALUES ('712421', '竹东镇', '712400');
INSERT INTO `__PREFIX__district` VALUES ('712422', '新埔镇', '712400');
INSERT INTO `__PREFIX__district` VALUES ('712423', '关西镇', '712400');
INSERT INTO `__PREFIX__district` VALUES ('712424', '湖口乡', '712400');
INSERT INTO `__PREFIX__district` VALUES ('712425', '新丰乡', '712400');
INSERT INTO `__PREFIX__district` VALUES ('712426', '芎林乡', '712400');
INSERT INTO `__PREFIX__district` VALUES ('712427', '橫山乡', '712400');
INSERT INTO `__PREFIX__district` VALUES ('712428', '北埔乡', '712400');
INSERT INTO `__PREFIX__district` VALUES ('712429', '宝山乡', '712400');
INSERT INTO `__PREFIX__district` VALUES ('712430', '峨眉乡', '712400');
INSERT INTO `__PREFIX__district` VALUES ('712431', '尖石乡', '712400');
INSERT INTO `__PREFIX__district` VALUES ('712432', '五峰乡', '712400');
INSERT INTO `__PREFIX__district` VALUES ('712500', '苗栗县', '710000');
INSERT INTO `__PREFIX__district` VALUES ('712501', '苗栗市', '712500');
INSERT INTO `__PREFIX__district` VALUES ('712521', '苑里镇', '712500');
INSERT INTO `__PREFIX__district` VALUES ('712522', '通霄镇', '712500');
INSERT INTO `__PREFIX__district` VALUES ('712523', '竹南镇', '712500');
INSERT INTO `__PREFIX__district` VALUES ('712524', '头份镇', '712500');
INSERT INTO `__PREFIX__district` VALUES ('712525', '后龙镇', '712500');
INSERT INTO `__PREFIX__district` VALUES ('712526', '卓兰镇', '712500');
INSERT INTO `__PREFIX__district` VALUES ('712527', '大湖乡', '712500');
INSERT INTO `__PREFIX__district` VALUES ('712528', '公馆乡', '712500');
INSERT INTO `__PREFIX__district` VALUES ('712529', '铜锣乡', '712500');
INSERT INTO `__PREFIX__district` VALUES ('712530', '南庄乡', '712500');
INSERT INTO `__PREFIX__district` VALUES ('712531', '头屋乡', '712500');
INSERT INTO `__PREFIX__district` VALUES ('712532', '三义乡', '712500');
INSERT INTO `__PREFIX__district` VALUES ('712533', '西湖乡', '712500');
INSERT INTO `__PREFIX__district` VALUES ('712534', '造桥乡', '712500');
INSERT INTO `__PREFIX__district` VALUES ('712535', '三湾乡', '712500');
INSERT INTO `__PREFIX__district` VALUES ('712536', '狮潭乡', '712500');
INSERT INTO `__PREFIX__district` VALUES ('712537', '泰安乡', '712500');
INSERT INTO `__PREFIX__district` VALUES ('712700', '彰化县', '710000');
INSERT INTO `__PREFIX__district` VALUES ('712701', '彰化市', '712700');
INSERT INTO `__PREFIX__district` VALUES ('712721', '鹿港镇', '712700');
INSERT INTO `__PREFIX__district` VALUES ('712722', '和美镇', '712700');
INSERT INTO `__PREFIX__district` VALUES ('712723', '线西乡', '712700');
INSERT INTO `__PREFIX__district` VALUES ('712724', '伸港乡', '712700');
INSERT INTO `__PREFIX__district` VALUES ('712725', '福兴乡', '712700');
INSERT INTO `__PREFIX__district` VALUES ('712726', '秀水乡', '712700');
INSERT INTO `__PREFIX__district` VALUES ('712727', '花坛乡', '712700');
INSERT INTO `__PREFIX__district` VALUES ('712728', '芬园乡', '712700');
INSERT INTO `__PREFIX__district` VALUES ('712729', '员林镇', '712700');
INSERT INTO `__PREFIX__district` VALUES ('712730', '溪湖镇', '712700');
INSERT INTO `__PREFIX__district` VALUES ('712731', '田中镇', '712700');
INSERT INTO `__PREFIX__district` VALUES ('712732', '大村乡', '712700');
INSERT INTO `__PREFIX__district` VALUES ('712733', '埔盐乡', '712700');
INSERT INTO `__PREFIX__district` VALUES ('712734', '埔心乡', '712700');
INSERT INTO `__PREFIX__district` VALUES ('712735', '永靖乡', '712700');
INSERT INTO `__PREFIX__district` VALUES ('712736', '社头乡', '712700');
INSERT INTO `__PREFIX__district` VALUES ('712737', '二水乡', '712700');
INSERT INTO `__PREFIX__district` VALUES ('712738', '北斗镇', '712700');
INSERT INTO `__PREFIX__district` VALUES ('712739', '二林镇', '712700');
INSERT INTO `__PREFIX__district` VALUES ('712740', '田尾乡', '712700');
INSERT INTO `__PREFIX__district` VALUES ('712741', '埤头乡', '712700');
INSERT INTO `__PREFIX__district` VALUES ('712742', '芳苑乡', '712700');
INSERT INTO `__PREFIX__district` VALUES ('712743', '大城乡', '712700');
INSERT INTO `__PREFIX__district` VALUES ('712744', '竹塘乡', '712700');
INSERT INTO `__PREFIX__district` VALUES ('712745', '溪州乡', '712700');
INSERT INTO `__PREFIX__district` VALUES ('712800', '南投县', '710000');
INSERT INTO `__PREFIX__district` VALUES ('712801', '南投市', '712800');
INSERT INTO `__PREFIX__district` VALUES ('712821', '埔里镇', '712800');
INSERT INTO `__PREFIX__district` VALUES ('712822', '草屯镇', '712800');
INSERT INTO `__PREFIX__district` VALUES ('712823', '竹山镇', '712800');
INSERT INTO `__PREFIX__district` VALUES ('712824', '集集镇', '712800');
INSERT INTO `__PREFIX__district` VALUES ('712825', '名间乡', '712800');
INSERT INTO `__PREFIX__district` VALUES ('712826', '鹿谷乡', '712800');
INSERT INTO `__PREFIX__district` VALUES ('712827', '中寮乡', '712800');
INSERT INTO `__PREFIX__district` VALUES ('712828', '鱼池乡', '712800');
INSERT INTO `__PREFIX__district` VALUES ('712829', '国姓乡', '712800');
INSERT INTO `__PREFIX__district` VALUES ('712830', '水里乡', '712800');
INSERT INTO `__PREFIX__district` VALUES ('712831', '信义乡', '712800');
INSERT INTO `__PREFIX__district` VALUES ('712832', '仁爱乡', '712800');
INSERT INTO `__PREFIX__district` VALUES ('712900', '云林县', '710000');
INSERT INTO `__PREFIX__district` VALUES ('712901', '斗六市', '712900');
INSERT INTO `__PREFIX__district` VALUES ('712921', '斗南镇', '712900');
INSERT INTO `__PREFIX__district` VALUES ('712922', '虎尾镇', '712900');
INSERT INTO `__PREFIX__district` VALUES ('712923', '西螺镇', '712900');
INSERT INTO `__PREFIX__district` VALUES ('712924', '土库镇', '712900');
INSERT INTO `__PREFIX__district` VALUES ('712925', '北港镇', '712900');
INSERT INTO `__PREFIX__district` VALUES ('712926', '古坑乡', '712900');
INSERT INTO `__PREFIX__district` VALUES ('712927', '大埤乡', '712900');
INSERT INTO `__PREFIX__district` VALUES ('712928', '莿桐乡', '712900');
INSERT INTO `__PREFIX__district` VALUES ('712929', '林内乡', '712900');
INSERT INTO `__PREFIX__district` VALUES ('712930', '二仑乡', '712900');
INSERT INTO `__PREFIX__district` VALUES ('712931', '仑背乡', '712900');
INSERT INTO `__PREFIX__district` VALUES ('712932', '麦寮乡', '712900');
INSERT INTO `__PREFIX__district` VALUES ('712933', '东势乡', '712900');
INSERT INTO `__PREFIX__district` VALUES ('712934', '褒忠乡', '712900');
INSERT INTO `__PREFIX__district` VALUES ('712935', '台西乡', '712900');
INSERT INTO `__PREFIX__district` VALUES ('712936', '元长乡', '712900');
INSERT INTO `__PREFIX__district` VALUES ('712937', '四湖乡', '712900');
INSERT INTO `__PREFIX__district` VALUES ('712938', '口湖乡', '712900');
INSERT INTO `__PREFIX__district` VALUES ('712939', '水林乡', '712900');
INSERT INTO `__PREFIX__district` VALUES ('713000', '嘉义县', '710000');
INSERT INTO `__PREFIX__district` VALUES ('713001', '太保市', '713000');
INSERT INTO `__PREFIX__district` VALUES ('713002', '朴子市', '713000');
INSERT INTO `__PREFIX__district` VALUES ('713023', '布袋镇', '713000');
INSERT INTO `__PREFIX__district` VALUES ('713024', '大林镇', '713000');
INSERT INTO `__PREFIX__district` VALUES ('713025', '民雄乡', '713000');
INSERT INTO `__PREFIX__district` VALUES ('713026', '溪口乡', '713000');
INSERT INTO `__PREFIX__district` VALUES ('713027', '新港乡', '713000');
INSERT INTO `__PREFIX__district` VALUES ('713028', '六脚乡', '713000');
INSERT INTO `__PREFIX__district` VALUES ('713029', '东石乡', '713000');
INSERT INTO `__PREFIX__district` VALUES ('713030', '义竹乡', '713000');
INSERT INTO `__PREFIX__district` VALUES ('713031', '鹿草乡', '713000');
INSERT INTO `__PREFIX__district` VALUES ('713032', '水上乡', '713000');
INSERT INTO `__PREFIX__district` VALUES ('713033', '中埔乡', '713000');
INSERT INTO `__PREFIX__district` VALUES ('713034', '竹崎乡', '713000');
INSERT INTO `__PREFIX__district` VALUES ('713035', '梅山乡', '713000');
INSERT INTO `__PREFIX__district` VALUES ('713036', '番路乡', '713000');
INSERT INTO `__PREFIX__district` VALUES ('713037', '大埔乡', '713000');
INSERT INTO `__PREFIX__district` VALUES ('713038', '阿里山乡', '713000');
INSERT INTO `__PREFIX__district` VALUES ('713300', '屏东县', '710000');
INSERT INTO `__PREFIX__district` VALUES ('713301', '屏东市', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713321', '潮州镇', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713322', '东港镇', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713323', '恒春镇', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713324', '万丹乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713325', '长治乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713326', '麟洛乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713327', '九如乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713328', '里港乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713329', '盐埔乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713330', '高树乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713331', '万峦乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713332', '内埔乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713333', '竹田乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713334', '新埤乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713335', '枋寮乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713336', '新园乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713337', '崁顶乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713338', '林边乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713339', '南州乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713340', '佳冬乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713341', '琉球乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713342', '车城乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713343', '满州乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713344', '枋山乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713345', '三地门乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713346', '雾台乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713347', '玛家乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713348', '泰武乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713349', '来义乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713350', '春日乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713351', '狮子乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713352', '牡丹乡', '713300');
INSERT INTO `__PREFIX__district` VALUES ('713400', '台东县', '710000');
INSERT INTO `__PREFIX__district` VALUES ('713401', '台东市', '713400');
INSERT INTO `__PREFIX__district` VALUES ('713421', '成功镇', '713400');
INSERT INTO `__PREFIX__district` VALUES ('713422', '关山镇', '713400');
INSERT INTO `__PREFIX__district` VALUES ('713423', '卑南乡', '713400');
INSERT INTO `__PREFIX__district` VALUES ('713424', '鹿野乡', '713400');
INSERT INTO `__PREFIX__district` VALUES ('713425', '池上乡', '713400');
INSERT INTO `__PREFIX__district` VALUES ('713426', '东河乡', '713400');
INSERT INTO `__PREFIX__district` VALUES ('713427', '长滨乡', '713400');
INSERT INTO `__PREFIX__district` VALUES ('713428', '太麻里乡', '713400');
INSERT INTO `__PREFIX__district` VALUES ('713429', '大武乡', '713400');
INSERT INTO `__PREFIX__district` VALUES ('713430', '绿岛乡', '713400');
INSERT INTO `__PREFIX__district` VALUES ('713431', '海端乡', '713400');
INSERT INTO `__PREFIX__district` VALUES ('713432', '延平乡', '713400');
INSERT INTO `__PREFIX__district` VALUES ('713433', '金峰乡', '713400');
INSERT INTO `__PREFIX__district` VALUES ('713434', '达仁乡', '713400');
INSERT INTO `__PREFIX__district` VALUES ('713435', '兰屿乡', '713400');
INSERT INTO `__PREFIX__district` VALUES ('713500', '花莲县', '710000');
INSERT INTO `__PREFIX__district` VALUES ('713501', '花莲市', '713500');
INSERT INTO `__PREFIX__district` VALUES ('713521', '凤林镇', '713500');
INSERT INTO `__PREFIX__district` VALUES ('713522', '玉里镇', '713500');
INSERT INTO `__PREFIX__district` VALUES ('713523', '新城乡', '713500');
INSERT INTO `__PREFIX__district` VALUES ('713524', '吉安乡', '713500');
INSERT INTO `__PREFIX__district` VALUES ('713525', '寿丰乡', '713500');
INSERT INTO `__PREFIX__district` VALUES ('713526', '光复乡', '713500');
INSERT INTO `__PREFIX__district` VALUES ('713527', '丰滨乡', '713500');
INSERT INTO `__PREFIX__district` VALUES ('713528', '瑞穗乡', '713500');
INSERT INTO `__PREFIX__district` VALUES ('713529', '富里乡', '713500');
INSERT INTO `__PREFIX__district` VALUES ('713530', '秀林乡', '713500');
INSERT INTO `__PREFIX__district` VALUES ('713531', '万荣乡', '713500');
INSERT INTO `__PREFIX__district` VALUES ('713532', '卓溪乡', '713500');
INSERT INTO `__PREFIX__district` VALUES ('713600', '澎湖县', '710000');
INSERT INTO `__PREFIX__district` VALUES ('713601', '马公市', '713600');
INSERT INTO `__PREFIX__district` VALUES ('713621', '湖西乡', '713600');
INSERT INTO `__PREFIX__district` VALUES ('713622', '白沙乡', '713600');
INSERT INTO `__PREFIX__district` VALUES ('713623', '西屿乡', '713600');
INSERT INTO `__PREFIX__district` VALUES ('713624', '望安乡', '713600');
INSERT INTO `__PREFIX__district` VALUES ('713625', '七美乡', '713600');
INSERT INTO `__PREFIX__district` VALUES ('810000', '香港特别行政区', '0');
INSERT INTO `__PREFIX__district` VALUES ('810100', '香港岛', '810000');
INSERT INTO `__PREFIX__district` VALUES ('810101', '中西区', '810100');
INSERT INTO `__PREFIX__district` VALUES ('810102', '湾仔区', '810100');
INSERT INTO `__PREFIX__district` VALUES ('810103', '东区', '810100');
INSERT INTO `__PREFIX__district` VALUES ('810104', '南区', '810100');
INSERT INTO `__PREFIX__district` VALUES ('810200', '九龙', '810000');
INSERT INTO `__PREFIX__district` VALUES ('810201', '油尖旺区', '810200');
INSERT INTO `__PREFIX__district` VALUES ('810202', '深水埗区', '810200');
INSERT INTO `__PREFIX__district` VALUES ('810203', '九龙城区', '810200');
INSERT INTO `__PREFIX__district` VALUES ('810204', '黄大仙区', '810200');
INSERT INTO `__PREFIX__district` VALUES ('810205', '观塘区', '810200');
INSERT INTO `__PREFIX__district` VALUES ('810300', '新界', '810000');
INSERT INTO `__PREFIX__district` VALUES ('810301', '荃湾区', '810300');
INSERT INTO `__PREFIX__district` VALUES ('810302', '屯门区', '810300');
INSERT INTO `__PREFIX__district` VALUES ('810303', '元朗区', '810300');
INSERT INTO `__PREFIX__district` VALUES ('810304', '北区', '810300');
INSERT INTO `__PREFIX__district` VALUES ('810305', '大埔区', '810300');
INSERT INTO `__PREFIX__district` VALUES ('810306', '西贡区', '810300');
INSERT INTO `__PREFIX__district` VALUES ('810307', '沙田区', '810300');
INSERT INTO `__PREFIX__district` VALUES ('810308', '葵青区', '810300');
INSERT INTO `__PREFIX__district` VALUES ('810309', '离岛区', '810300');
INSERT INTO `__PREFIX__district` VALUES ('820000', '澳门特别行政区', '0');
INSERT INTO `__PREFIX__district` VALUES ('820100', '澳门半岛', '820000');
INSERT INTO `__PREFIX__district` VALUES ('820101', '花地玛堂区', '820100');
INSERT INTO `__PREFIX__district` VALUES ('820102', '圣安多尼堂区', '820100');
INSERT INTO `__PREFIX__district` VALUES ('820103', '大堂区', '820100');
INSERT INTO `__PREFIX__district` VALUES ('820104', '望德堂区', '820100');
INSERT INTO `__PREFIX__district` VALUES ('820105', '风顺堂区', '820100');
INSERT INTO `__PREFIX__district` VALUES ('820200', '氹仔岛', '820000');
INSERT INTO `__PREFIX__district` VALUES ('820201', '嘉模堂区', '820200');
INSERT INTO `__PREFIX__district` VALUES ('820300', '路环岛', '820000');
INSERT INTO `__PREFIX__district` VALUES ('820301', '圣方济各堂区', '820300');
INSERT INTO `__PREFIX__district` VALUES ('2102111', '高新园区', '210200');
INSERT INTO `__PREFIX__district` VALUES ('350902500', '东侨开发区', '350900');
INSERT INTO `__PREFIX__district` VALUES ('441900003', '东城街道', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900004', '南城街道', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900005', '万江街道', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900006', '莞城街道', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900101', '石碣镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900102', '石龙镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900103', '茶山镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900104', '石排镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900105', '企石镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900106', '横沥镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900107', '桥头镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900108', '谢岗镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900109', '东坑镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900110', '常平镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900111', '寮步镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900112', '樟木头镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900113', '大朗镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900114', '黄江镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900115', '清溪镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900116', '塘厦镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900117', '凤岗镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900118', '大岭山镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900119', '长安镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900121', '虎门镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900122', '厚街镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900123', '沙田镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900124', '道滘镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900125', '洪梅镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900126', '麻涌镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900127', '望牛墩镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900128', '中堂镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900129', '高埗镇', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900401', '松山湖管委会', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900402', '东莞港', '441900');
INSERT INTO `__PREFIX__district` VALUES ('441900403', '东莞生态园', '441900');
INSERT INTO `__PREFIX__district` VALUES ('442000001', '石岐区街道', '442000');
INSERT INTO `__PREFIX__district` VALUES ('442000002', '东区街道', '442000');
INSERT INTO `__PREFIX__district` VALUES ('442000003', '火炬开发区街道', '442000');
INSERT INTO `__PREFIX__district` VALUES ('442000004', '西区街道', '442000');
INSERT INTO `__PREFIX__district` VALUES ('442000005', '南区街道', '442000');
INSERT INTO `__PREFIX__district` VALUES ('442000006', '五桂山街道', '442000');
INSERT INTO `__PREFIX__district` VALUES ('442000100', '小榄镇', '442000');
INSERT INTO `__PREFIX__district` VALUES ('442000101', '黄圃镇', '442000');
INSERT INTO `__PREFIX__district` VALUES ('442000102', '民众镇', '442000');
INSERT INTO `__PREFIX__district` VALUES ('442000103', '东凤镇', '442000');
INSERT INTO `__PREFIX__district` VALUES ('442000104', '东升镇', '442000');
INSERT INTO `__PREFIX__district` VALUES ('442000105', '古镇镇', '442000');
INSERT INTO `__PREFIX__district` VALUES ('442000106', '沙溪镇', '442000');
INSERT INTO `__PREFIX__district` VALUES ('442000107', '坦洲镇', '442000');
INSERT INTO `__PREFIX__district` VALUES ('442000108', '港口镇', '442000');
INSERT INTO `__PREFIX__district` VALUES ('442000109', '三角镇', '442000');
INSERT INTO `__PREFIX__district` VALUES ('442000110', '横栏镇', '442000');
INSERT INTO `__PREFIX__district` VALUES ('442000111', '南头镇', '442000');
INSERT INTO `__PREFIX__district` VALUES ('442000112', '阜沙镇', '442000');
INSERT INTO `__PREFIX__district` VALUES ('442000113', '南朗镇', '442000');
INSERT INTO `__PREFIX__district` VALUES ('442000114', '三乡镇', '442000');
INSERT INTO `__PREFIX__district` VALUES ('442000115', '板芙镇', '442000');
INSERT INTO `__PREFIX__district` VALUES ('442000116', '大涌镇', '442000');
INSERT INTO `__PREFIX__district` VALUES ('442000117', '神湾镇', '442000');

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
  `wxids` text COLLATE utf8mb4_unicode_ci COMMENT '接收者wxids，逗号隔开',
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


#---v1.5.1---#
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

#---v2.0.2---#
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

#---v2.0.4---#
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

#---2.0.5---#
ALTER TABLE `__PREFIX__bot_member`
ADD COLUMN `last_chat_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后聊天时间' AFTER `internal`,
ADD COLUMN `sex` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1男 2女' AFTER `last_chat_time`,
ADD COLUMN `province` varchar(50) NOT NULL DEFAULT '' AFTER `sex`,
ADD COLUMN `city` varchar(50) NOT NULL DEFAULT '' AFTER `province`;

ALTER TABLE `__PREFIX__bot`
ADD COLUMN `username` varchar(20) NOT NULL DEFAULT '';

ALTER TABLE `__PREFIX__bot_groupmember`
ADD COLUMN `headimgurl` varchar(200) NOT NULL DEFAULT '';
