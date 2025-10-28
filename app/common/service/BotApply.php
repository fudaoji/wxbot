<?php
/**
 * Created by PhpStorm.
 * Script Name: Blog.php
 * Create: 2025/4/20 下午12:32
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\service;


use app\admin\model\Admin;
use app\common\model\BotApply as M;
use ky\Logger;
use think\facade\Db;

class BotApply
{
    static $model = null;
    const WECHAT = 'wechat';
    const WXWORK = 'wxwork';

    static function model(){
        if(is_null(self::$model)){
            self::$model = new M();
        }
        return self::$model;
    }

    /**
     * 类型
     * @param string $type
     * @return array|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function types($type = ''){
        $list = [
            self::WECHAT => '个微',
            self::WXWORK => '企微',
        ];
        return $list[$type] ?? $list;
    }

    /**
     * 获取可用席位
     * @param null $admin_info
     * @param string $type
     * @return bool|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getSeatByAdmin($type = self::WECHAT, $admin_info = null)
    {
        empty($admin_info) && $admin_info = (new Admin())->find(session(SESSION_AID));
        $map = ['wx_num' => '', 'admin_id' => $admin_info['id'], 'deadline' => ['>', time()], 'type' => $type];
        return self::model()->getOneByMap($map, true, true, ['deadline' => 'desc']);
    }

    /**
     * 绑定席位
     * @param $seat
     * @param $bot
     * @return bool|mixed
     * @throws \think\Exception
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function bindBot($seat, $bot){
        return self::model()->updateOne([
            'id' => $seat['id'],
            'wx_num' => $bot['uin']
        ]);
    }

    /**
     * 解绑席位
     * @param $seat
     * @return bool|mixed
     * @throws \think\Exception
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public static function unbindBot($seat)
    {
        return self::model()->updateOne([
            'id' => $seat['id'],
            'wx_num' => ''
        ]);
    }

    /**
     * 获取可用席位个数
     * @param null $admin_info
     * @param string $type
     * @return int
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getSeatNum($type = self::WECHAT, $admin_info = null)
    {
        $admin_id = Admin::getCompanyId($admin_info);
        $map = ['admin_id' => $admin_id, 'deadline' => ['>', time()], 'type' => $type];
        return self::model()->total($map, true);
    }
}