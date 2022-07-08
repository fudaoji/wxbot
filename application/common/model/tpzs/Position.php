<?php
/**
 * Created by PhpStorm.
 * Script Name: Jd.php
 * Create: 2022/3/29 10:51
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model\tpzs;


class Position extends Tpzs
{
    protected $table = "position";

    /**
     * 推广位类型
     * @param null $id
     * @return array|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public static function jdTypes($id = null){
        $list = [
            1 => '网站推广位',
            2 => 'APP推广位',
            3 => '社交媒体推广位',
            4 => '聊天工具推广位'
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }
}