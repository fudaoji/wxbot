<?php
/**
 * Created by PhpStorm.
 * Script Name: AdminSeat.php
 * Create: 2023/2/17 18:08
 * Description: 微信号席位
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model;

use app\admin\model\Admin;

class AdminSeat extends Base
{
    /**
     * 注册用户初始化额度
     * @param array $admin
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public static function initSeat(array $admin)
    {
        if(! self::find($admin['id'])){
            $num = intval(config('system.bot.seat_default'));
            self::create(['id' => $admin['id'], 'total' => $num, 'remain' => $num]);
        }
    }

    /**
     * 获取可添加个数
     * @param array $admin_info
     * @return int|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public static function getRemain($admin_info = [])
    {
        $admin_id = Admin::getCompanyId($admin_info);
        $data = self::find($admin_id);
        return empty($data) ? 0 : $data['remain'];
    }
}