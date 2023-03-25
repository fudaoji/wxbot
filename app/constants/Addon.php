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
    const AI = 'ai';
    const ZDJR = 'zdjr';
    const KEFU = 'kefu';

    public static function addons($id = null){
        $list = [
            self::TPZS => '推品助手',
            self::HANZI => '汉字助手',
            self::YHQ => '优惠券助手',
            self::AI => '智能对话',
            self::ZDJR => '自动加人',
            //self::KEFU => '微信多客服',
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }
}