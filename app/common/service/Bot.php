<?php
/**
 * Created by PhpStorm.
 * Script Name: Bot.php
 * Create: 2024/2/20 16:12
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\service;


use app\admin\model\Admin;
use app\common\service\AdminLog as AdminLogService;
use app\common\service\BotApply as ApplyService;
use app\constants\Bot as BotConst;

class Bot
{
    static $model = null;

    static function model(){
        if(is_null(self::$model)){
            self::$model = new \app\admin\model\Bot();
        }
        return self::$model;
    }

    static function getUinToTitle($where = []){
        //$where = array_merge(['status' => 1], $where);
        return self::model()->getField(['uin','title'], $where);
    }

    /**
     * 获取在线个数
     * @param string $type
     * @param null $admin_info
     * @return mixed
     * @throws \think\db\exception\DbException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function getActiveNum($type = ApplyService::WECHAT, $admin_info = null){
        $admin_id = Admin::getCompanyId($admin_info);
        $map = ['admin_id' => $admin_id, 'alive' => 1];
        if($type == ApplyService::WECHAT){
            $map['protocol'] = ['in', [BotConst::PROTOCOL_EXTIAN]];
        }else{
            $map['protocol'] = ['in', [BotConst::PROTOCOL_EASYCOM]];
        }
        return self::model()->total($map, true);
    }

    /**
     * 超出的个微踢下线
     * Author: fudaoji<fdj@kuryun.cn>
     * @param string $type
     * @param null $admin_info
     * @return bool
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    static function kickBot($type = ApplyService::WECHAT, $admin_info = null){
        if(Admin::isFounder($admin_info)){
            return true;
        }
        $seat_num = ApplyService::getSeatNum($type);
        if($seat_num >= self::getActiveNum($type, $admin_info)){ //未超出
            return true;
        }
        $admin_id = Admin::getCompanyId($admin_info);
        $map = ['admin_id' => $admin_id, 'alive' => 1];
        if($type == ApplyService::WECHAT){
            $map['protocol'] = ['in', [BotConst::PROTOCOL_EXTIAN]];
        }else{
            $map['protocol'] = ['in', [BotConst::PROTOCOL_EASYCOM]];
        }
        $list = self::model()->getAll([
            'where' => $map,
            'order' => ['id' => 'asc'],
            'field' => ['id', 'alive','nickname'],
            'refresh' => true,
        ]);
        $nickname_arr = [];
        foreach ($list as $k => $item){
            if($k >= $seat_num){
                $nickname_arr[] = $item['nickname'];
                self::model()->updateByMap(['id' => $item['id']], ['alive' => 0]);
            }
        }
        AdminLogService::addLog([
            'year' => (int)date('Y'),
            'type' => AdminLogService::BOT_KIK,
            'desc' => '由于席位不足，以下机器人被踢下线：' . implode(',', $nickname_arr) . '.'
        ]);
        return true;
    }
}