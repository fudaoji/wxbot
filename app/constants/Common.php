<?php
/**
 * Created by PhpStorm.
 * Script Name: Common.php
 * Create: 2021/12/21 13:43
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\constants;


class Common
{
    const YES = 1;
    const NO = 0;

    public static function yesOrNo($id = null){
        $list = [
            self::YES => '是',
            self::NO => '否'
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }
}