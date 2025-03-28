<?php
/**
 * Created by PhpStorm.
 * Script Name: BotMember.php
 * Create: 2025/3/27 17:21
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\service;


use app\admin\model\BotMember as MemberM;
use app\constants\Bot;

class BotMember
{
    static $model = null;

    static function model(){
        if(is_null(self::$model)){
            self::$model = new MemberM();
        }
        return self::$model;
    }

    /**
     * 获取 [wxid=>nickname, ...] 数据对
     * @param array $where
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public static function getWxidToNickName($where = []){
        return self::model()->getField('wxid,nickname', $where, true);
    }
}