<?php
/**
 * Created by PhpStorm.
 * Script Name: Config.php
 * Create: 2025/1/16 17:54
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\service;
use app\common\model\KeywordConfig as M;
use ky\Logger;

class KeywordConfig
{
    static $model = null;

    static function model(){
        if(is_null(self::$model)){
            self::$model = new M();
        }
        return self::$model;
    }

    /**
     * 全局设置
     * @param array $where
     * @param string $key
     * @param int $refresh
     * @return mixed
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public static function getConf($where = [], $key = '', $refresh = 0){
        $list = self::model()->getField(['key', 'value'], $where, $refresh);
        if(!empty($key)){
            return isset($list[$key]) ? $list[$key] : '';
        }
        return $list;
    }
}