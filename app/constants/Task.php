<?php
/**
 * Created by PhpStorm.
 * Script Name: Task.php
 * Create: 2022/6/21 11:43
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\constants;


class Task
{
    const CIRCLE_SINGLE = 1; //单次发送
    const CIRCLE_DAILY = 2; //每日发送

    const USER_TYPE_ALL = 0;
    const USER_TYPE_FRIEND = 1;
    const USER_TYPE_GROUP = 2;

    public static function userTypes($id = null){
        $list = [
            self::USER_TYPE_ALL => '所有好友和群聊',
            self::USER_TYPE_FRIEND => '所有好友',
            self::USER_TYPE_GROUP => '所有群聊',
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }

    public static function circles($id = null){
        $list = [
            self::CIRCLE_SINGLE => '单次发送',
            self::CIRCLE_DAILY => '每天发送'
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }
}