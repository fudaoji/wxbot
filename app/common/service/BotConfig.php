<?php
/**
 * Created by PhpStorm.
 * Script Name: Config.php
 * Create: 2025/1/16 17:54
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\service;

class BotConfig
{
    static $model = null;

    static function model(){
        if(is_null(self::$model)){
            self::$model = new \app\common\model\BotConfig();
        }
        return self::$model;
    }

    /**
     * 口令处理
     * @param int $bot_id
     * @param string $msg
     * @param string $wxid
     * @throws \think\Exception
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public static function handleCommand($bot_id = 0, $msg = '', $wxid = ''){
        $configs = self::getConf(['bot_id' => $bot_id]);
        $msg = trim($msg);
        $map = ['id' => $bot_id];
        $update = [];
        if(!empty($configs['command_on']) && $configs['command_on'] == $msg){
            $update = ['status' => 1];
            $msg = "已启用机器人！";
        }else if(!empty($configs['command_off']) && $configs['command_off'] == $msg){
            $update = ['status' => 0];
            $msg = "已禁用机器人！";
        }
        if(!empty($configs['command_wxids']) && strpos($configs['command_wxids'], $wxid) === false){
            return false;
        }
        if(!empty($update)){
            model('admin/bot')->updateByMap($map, $update);
            $res = self::getConf(['bot_id' => $bot_id], '', true);
            //Logger::error($res['switch']);
            return $msg;
        }
        return false;
    }

    /**
     * 全局设置
     * @param array $where
     * @param string $key
     * @param int $refresh
     * @return mixed
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public static function getConf($where = [], $key = '', $refresh = 0){
        $list = self::model()->getField(['key', 'value'], $where, $refresh);
        if(!empty($key)){
            return isset($list[$key]) ? $list[$key] : '';
        }
        return $list;
    }
}