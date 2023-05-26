<?php
/**
 * Created by PhpStorm.
 * Script Name: Platform.php
 * Create: 2022/12/16 8:17
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\service;

class Platform
{
    const MP = 'mp';
    const MINI = 'mini';
    const WECHAT = 'wechat';
    const WORKWX = 'workwx';
    const APP = 'app';
    const PC = 'pc';
    const H5 = 'h5';

    /**
     * 类型
     * @param null $id
     * @return array|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public static function wechatTypes($id = null){
        $list = [
            self::WECHAT => '个微',
            self::MP => '微信公众号',
            self::MINI => '微信小程序',
            /*self::WORKWX => '企微',*/
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }

    /**
     * 类型
     * @param null $id
     * @return array|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public static function types($id = null){
        $list = [
            self::WECHAT => '个微',
            self::MP => '微信公众号',
            self::MINI => '微信小程序',
            self::WORKWX => '企微',
            self::PC => '网站',
            self::APP => 'APP',
            self::H5 => 'H5',
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }
}