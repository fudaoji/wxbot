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
     * 获取当前登陆账号的团队字典
     * @param array $admin_info
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function getTeamIdToName($admin_info = []){
        return self::where('id|pid', $admin_info['id'])->column('username', 'id');
    }

    /**
     * 获取当前客户的{id:name}
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    static function getTenantIdToName(){
        $where = ['id' => ['<>', self::getFounderId()], 'pid' => 0];
        return (new self())->getField(['id','username'], $where);
    }

    /**
     * 站长id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function getFounderId(){
        return self::where('status', 1)
            ->order('id', 'asc')
            ->find()['id'];
    }

    /**
     * 是否站长
     * @param array $admin_info
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    static function isFounder($admin_info = []){
        return $admin_info['id'] == self::getFounderId();
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

    /**
     * 注册后动作
     * @param array $admin
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function afterReg($admin = []){

    }
}