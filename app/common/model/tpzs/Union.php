<?php
/**
 * Created by PhpStorm.
 * Script Name: Jd.php
 * Create: 2022/3/29 10:51
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model\tpzs;


class Union extends Tpzs
{
    protected $table = "union";
    const JD = 'jd';
    const TB = 'tb';

    public static function types($id = null){
        $list = [
            self::JD => '京东联盟',
            //self::TB => '淘宝联盟',
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }
}