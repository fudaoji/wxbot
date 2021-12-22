<?php
/**
 * Created by PhpStorm.
 * Script Name: AdminRule.php
 * Create: 2020/9/6 下午10:37
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\model;

use app\common\model\Base;

class AdminRule extends Base
{
    /**
     * 类型
     * @param null $type
     * @return array
     */
    public function types($type=null){
        $list = [
            1 => '菜单',
            2 => '权限'
        ];
        return isset($list[$type]) ? $list[$type] : $list;
    }

}