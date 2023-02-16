<?php
/**
 * Created by PhpStorm.
 * Script Name: Admin.php
 * Create: 2020/9/6 下午11:12
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\model;

use app\common\model\Base;

class Admin extends Base
{
    /**
     * 是否站长
     * @param array $admin_info
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function isFounder($admin_info = []){
        return $admin_info['id'] == 1;
    }

    /**
     * 获取当前登陆账号的所属的商户ID
     * @param array $admin_info
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function getCompanyId($admin_info = []){
        return empty($admin_info['pid']) ? $admin_info['id'] : $admin_info['pid'];
    }

    /**
     * 是否商户创始人
     * @param array $admin_info
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function isLeader($admin_info = []){
        return empty($admin_info['pid']);
    }
}