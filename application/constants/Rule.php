<?php
/**
 * Created by PhpStorm.
 * Script Name: Rule.php
 * Create: 2022/4/26 14:04
 * Description: 群规则
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\constants;


class Rule
{
    const RM = 'rm';

    public static function rules($id = null){
        $list = [
            self::RM => '移出群'
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }
}