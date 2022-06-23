<?php
/**
 * Created by PhpStorm.
 * Script Name: Addon.php
 * Create: 2022/6/22 11:22
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\constants;


class Addon
{
    const TPZS='tpzs';
    const HANZI='hanzi';
    const YHQ='yhq';

    public static function addons($id = null){
        $list = [
            self::TPZS => '推品助手',
            self::HANZI => '汉字助手',
            self::YHQ => '优惠券助手'
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }
}