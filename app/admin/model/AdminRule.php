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
     * 跳转类型
     * @param null $type
     * @return array
     */
    public static function targets($type=null){
        $list = [
            '_self' => '站内',
            '_blank' => '站外'
        ];
        return isset($list[$type]) ? $list[$type] : $list;
    }

    /**
     * 类型
     * @param null $type
     * @return array
     */
    public static function types($type=null){
        $list = [
            1 => '菜单',
            2 => '权限'
        ];
        return isset($list[$type]) ? $list[$type] : $list;
    }

    /**
     * 获取某个角色的权限菜单
     * @param int $group_id
     * @param null $field
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException Author: fudaoji<fdj@kuryun.cn>
     */
    public function getGroupRules($group_id = 0, $field = null){
        $group = AdminGroup::find($group_id);
        $group_rules = explode(',', $group['rules']);
        if(is_null($field)){
            $res = $this->getAll([
                'where' => ['status' => 1, 'id' => ['in', $group_rules]],
                'order' => ['sort' => 'desc'],
                'field' => 'id, pid, title, href'
            ]);
        }else{
            $res = $this->getAll([
                'where' => ['status' => 1, 'id' => ['in', $group_rules]],
                'order' => ['sort' => 'desc'],
                'field' => $field
            ])->toArray();
        }
        return $res;
    }
}