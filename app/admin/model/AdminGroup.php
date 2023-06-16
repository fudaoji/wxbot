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
     * 判断是否有权限
     * @param string $node
     * @param array $admin_info
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function checkAuth($node = '', $admin_info = []){
        if(Admin::isFounder($admin_info) || ! $rule = AdminRule::where('href', 'like', '%'.$node)->find()){
            return true;
        }
        $group = $this->getOne($admin_info['group_id']);
        $group_rules = empty($group['rules']) ? [] : explode(',', $group['rules']);
        return in_array($rule['id'], $group_rules);
    }

    /**
     * 获取角色列表[id => title]
     * @param int $admin_id
     * @param array $where
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getGroupsIdToTitle($admin_id = 0, $where = []){
        $where = array_merge(['status' => 1, 'admin_id' => $admin_id], $where);
        return $this->getField('id,title', $where);
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