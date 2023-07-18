<?php
/**
 * Created by PhpStorm.
 * Script Name: AdminLog.php
 * Create: 2023/7/6 10:10
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\service;

use app\admin\model\Admin;
use app\admin\model\AdminLog as LogM;

class AdminLog
{
    const LOGIN = 'login';
    const BOT_LOGIN = 'bot_login';
    const DEL = 'del';

    public static function types($id = null){
        $list = [
            self::LOGIN => '账户登录',
            self::BOT_LOGIN => '机器人登录',
            self::DEL => '数据删除'
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }

    /**
     * 记录日志
     * @param array $params
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public static function addLog($params = []){
        empty($params['admin_id']) && $params['admin_id'] = session(SESSION_AID);
        if($admin_info = (new Admin())->getOne($params['admin_id'])){
            $params['admin_username'] = $admin_info['username'];
            $params['ip'] = request()->ip();
            if($res = (new LogM())->addOne($params)){
                return $res;
            }
        }
        return false;
    }
}