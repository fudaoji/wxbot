<?php
/**
 * Created by PhpStorm.
 * Script Name: AdminAddon.php
 * Create: 2025/4/16 下午7:01
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\service;


use app\admin\model\Admin as AdminM;
use app\common\model\AdminAddon as TenantAppM;

class AdminAddon
{

    static $model = null;

    static function model(){
        if(is_null(self::$model)){
            self::$model = new TenantAppM();
        }
        return self::$model;
    }

    /**
     * 开通应用
     * @param $data
     * @return array
     */
    static function openApp($data){
        $res = self::model()->addOne($data);
        return $res;
    }

    /**
     *  获取关联数据
     * @param string $app_name
     * @param null $company_id
     * @return int
     * @throws \think\db\exception\DbException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function getAppData($app_name = '', $company_id = null){
        empty($company_id) && $company_id = AdminM::getCompanyId();
        $map = [
            'ta.company_id' => $company_id,
            'app.name' => $app_name
        ];
        $query = TenantAppM::alias('ta')
            ->where($map)
            ->join('addon app', 'app.id=ta.addon_id');
        return $query->find();
    }

    /**
     * 获取可用app
     * @param null $company_id
     * @param array $where
     * @return array|\think\Collection|\think\db\Query[]
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function getAppsDict($company_id = null, $where = []){
        empty($company_id) && $company_id = AdminM::getCompanyId();
        $map = [
            'ta.company_id' => $company_id
        ];
        $query = TenantAppM::alias('ta')
            ->where($map)
            ->join('addon app', 'app.id=ta.addon_id')
            ->field(['app.*', 'ta.deadline']);
        $where && $query = $query->where($where);
        return $query->column(['app.*', 'ta.deadline'], 'app.name');
    }

    /**
     * 获取可用app
     * @param null $company_id
     * @param array $where
     * @return array|\think\Collection|\think\db\Query[]
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function getActiveApps($company_id = null, $where = []){
        empty($company_id) && $company_id = AdminM::getCompanyId();
        $map = [
            'app.status' => 1,
            'ta.company_id' => $company_id
        ];
        $query = TenantAppM::alias('ta')
            ->where($map)
            ->where('ta.deadline','>', time())
            ->join('addon app', 'app.name=ta.app_name')
            ->field(['app.*', 'ta.deadline']);
        $where && $query = $query->where($where);
        return $query->select();
    }

    /**
     * 验证是否有该应用权限
     * @param string $app_name
     * @param null $company_id
     * @return int
     * @throws \think\db\exception\DbException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function checkAppOpenStatus($app_name = '', $company_id = null){
        empty($company_id) && $company_id = AdminM::getCompanyId();
        $map = [
            'app.status' => 1,
            'ta.company_id' => $company_id,
            'app.name' => $app_name
        ];
        $query = TenantAppM::alias('ta')
            ->where($map)
            ->where('ta.deadline','>', time())
            ->join('addon app', 'app.id=ta.addon_id');
        return $query->count();
    }

    /**
     * 当前开通应用数量
     * @param null $company_id
     * @param array $where
     * @return int
     * @throws \think\db\exception\DbException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function getActiveAppsNum($company_id = null, $where = []){
        empty($company_id) && $company_id = session(SESSION_AID);
        $map = [
            'app.status' => 1,
            'ta.company_id' => $company_id
        ];
        $query = TenantAppM::alias('ta')
            ->where($map)
            ->where('ta.deadline','>', time())
            ->join('app app', 'app.name=ta.app_name');
        $where && $query = $query->where($where);
        return $query->count();
    }

    /**
     * 过期应用数量
     * @param null $company_id
     * @param array $where
     * @return int
     * @throws \think\db\exception\DbException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function getDeadlineAppsNum($company_id = null, $where = []){
        is_null($company_id) && $company_id = Tenant::getCompanyId();
        $map = [
            'app.status' => 1,
            'ta.company_id' => $company_id
        ];
        $query = TenantAppM::alias('ta')
            ->where($map)
            ->where('ta.deadline','<=', time())
            ->join('app app', 'app.name=ta.app_name');
        $where && $query = $query->where($where);
        return $query->count();
    }
}