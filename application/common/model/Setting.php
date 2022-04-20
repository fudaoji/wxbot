<?php
// +----------------------------------------------------------------------
// | [KyPHP System] Copyright (c) 2020 http://www.kuryun.com/
// +----------------------------------------------------------------------
// | [KyPHP] 并不是自由软件,你可免费使用,未经许可不能去掉KyPHP相关版权
// +----------------------------------------------------------------------
// | Author: fudaoji <fdj@kuryun.cn>
// +----------------------------------------------------------------------

/**
 * Created by PhpStorm.
 * Script Name: Setting.php
 * Create: 2020/3/2 下午8:56
 * Description:  配置
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model;
use ky\BaseModel;

class Setting extends BaseModel
{
    protected $cacheTag = 'setting';

    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->createTable();
    }

    /**
     * 自动创建表
     * @throws \think\db\exception\BindParamException
     * @throws \think\exception\PDOException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    private function createTable(){
        $table_name = $this->getTable();
        if (! $this->query("SHOW TABLES LIKE '{$table_name}'")) {//数据库中存在着表，
            $sql = <<<sql
CREATE TABLE `{$table_name}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '标识',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `value` text COMMENT '配置值',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='站点配置';

INSERT INTO `{$table_name}` VALUES ('1', 'site', '站点信息', '{\"company_title\":\"微精灵\",\"jd_appkey\":\"\",\"jd_appsecret\":\"\",\"jtt_appid\":\"\",\"jtt_appkey\":\"\"}', '1590290640', '1649899288'), ('2', 'upload', '附件设置', '{\"driver\":\"qiniu\",\"qiniu_ak\":\"\",\"qiniu_sk\":\"\",\"qiniu_bucket\":\"\",\"qiniu_domain\":\"\",\"image_size\":\"3148000\",\"image_ext\":\"jpg,gif,png,jpeg\",\"file_size\":\"53000000\",\"file_ext\":\"jpg,gif,png,jpeg,zip,rar,tar,gz,7z,doc,docx,txt,xml,mp3,mp4,xls,xlsx,pdf\",\"voice_size\":\"2048000\",\"voice_ext\":\"mp3,wma,wav,amr\",\"video_size\":\"50240000\",\"video_ext\":\"mp4,flv,mov\"}', '1590292316', '1646835370');
sql;
            execute_sql($sql);
        }
    }

    /**
     * 全局设置
     * @param int $refresh
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function settings($refresh = 0){
        $list = $this->getAll(['refresh' => $refresh]);
        $data = [];
        foreach ($list as $v){
            $data[$v['name']] = json_decode($v['value'], true);
        }
        config(['system' => array_merge(config('system.'), $data)]);
        return $data;
    }
}