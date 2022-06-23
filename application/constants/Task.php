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

    public static function circles($id = null){
        $list = [
            self::CIRCLE_SINGLE => '单次发送',
            self::CIRCLE_DAILY => '每天发送'
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }
}