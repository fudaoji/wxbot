<?php
/**
 * Created by PhpStorm.
 * Script Name: Admingroup.php
 * Create: 11:11 上午
 * Description:
 * Author: Jason<dcq@kuryun.cn>
 */

namespace app\admin\model;


use app\common\model\Base;

class AdminGroup extends Base
{
    /**
     * 获取角色列表[id => title]
     * @param int $admin_id
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getGroupsIdToTitle($admin_id = 0){
        return $this->getField('id,title', ['status' => 1, 'admin_id' => $admin_id]);
    }

    /**
     * 获取商户归属的角色
     * @param bool $field
     * @return AdminGroup|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException Author: fudaoji<fdj@kuryun.cn>
     */
    static function getTenantGroup($field = true){
        return self::where('tenant_group', 1)
            ->field($field)
            ->find();
    }
}