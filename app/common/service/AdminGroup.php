<?php
/**
 * Created by PhpStorm.
 * Script Name: AdminGroup.php
 * Create: 2023/6/16 13:33
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\service;

use app\admin\model\Admin as AdminM;
use app\admin\model\AdminGroup as GroupM;

class AdminGroup
{
    /**
     * @param null $admin_info
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function getGroupAppsWhere($admin_info = null){
        is_null($admin_info) && $admin_info = AdminM::find(session(SESSION_AID));
        if(AdminM::isFounder($admin_info)){
            return ['id' => ['>', 0]];
        }
        $group = (new GroupM())->getOne($admin_info['group_id']);
        $addons = empty($group['addons']) ? [0] : explode(',', $group['addons']);
        return ['id' => ['in', $addons]];
    }
}