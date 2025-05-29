<?php
/**
 * Created by PhpStorm.
 * Script Name: JsfTask.php
 * Create: 2025/5/29 下午10:12
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\service;


use app\common\model\JsfTask as JsfM;

class JsfTask
{

    const STATUS_WAIT = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_OVER = 2;

    static $model = null;

    static function model(){
        if(is_null(self::$model)){
            self::$model = new JsfM();
        }
        return self::$model;
    }

    static function statusList($id = null){
        $list = [
            self::STATUS_WAIT => '等待执行',
            self::STATUS_ACTIVE => '执行中',
            self::STATUS_OVER => '执行完毕'
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }
}