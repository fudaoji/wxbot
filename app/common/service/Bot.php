<?php
/**
 * Created by PhpStorm.
 * Script Name: Bot.php
 * Create: 2024/2/20 16:12
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\service;


class Bot
{
    static $model = null;

    static function model(){
        if(is_null(self::$model)){
            self::$model = new \app\admin\model\Bot();
        }
        return self::$model;
    }

    static function getUinToTitle($where = []){
        //$where = array_merge(['status' => 1], $where);
        return self::model()->getField(['uin','title'], $where);
    }
}