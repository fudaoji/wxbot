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
    const ZDJR = 'zdjr';
    const KEFU = 'kefu';
    const BGF = 'bgf';

    public static function addons($id = null){
        $list = self::freeAddons();
        if($custom_addons = config('system.addon.addons')){
            $addons = explode("\r\n", $custom_addons);
            foreach ($addons as $addon){
                list($name, $title) = explode(':', $addon);
                $list[$name] = $title;
            }
        }
        return isset($list[$id]) ? $list[$id] : $list;
    }

    private static function freeAddons(){
        return [
            self::ZDJR => '自动加人',
            self::TPZS => '推品助手',
            //self::HANZI => '汉字助手',
        ];
    }
}