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

    static function model(){
        if(is_null(self::$model)){
            self::$model = new M();
        }
        return self::$model;
    }

    /**
     * 获取可用席位
     * @param null $admin_info
     * @return bool|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getSeatByAdmin($admin_info = null)
    {
        empty($admin_info) && $admin_info = (new Admin())->find(session(SESSION_AID));
        return self::model()->getOneByMap(['wx_num' => '', 'admin_id' => $admin_info['id'], 'deadline' => ['>', time()]]
        , true, true, ['deadline' => 'desc']);
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
}