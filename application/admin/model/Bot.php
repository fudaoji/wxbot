<?php
/**
 * Created by PhpStorm.
 * Script Name: ${FILE_NAME}
 * Create: 12/21/21 12:28 AM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\model;


use app\common\model\Base;
use ky\Bot\Vlw;
use ky\Bot\Wxwork;

class Bot extends Base
{
    /**
     * 获取机器人客户端
     * @param array $bot
     * @return Vlw
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getRobotClient($bot = []){
        switch ($bot['protocol']){
            case \app\constants\Bot::PROTOCOL_WXWORK:
                $client = new Wxwork(['app_key' => $bot['app_key'], 'base_uri' => $bot['url']]);
                break;
            default:
                $client = new Vlw(['app_key' => $bot['app_key'], 'base_uri' => $bot['url']]);
                break;
        }
        return $client;
    }

    /**
     * 获取机器人信息
     * @param array $params
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getRobotInfo($params = []){
        switch ($params['free']){
            case 0:
                $return = Wxwork::init(['app_key' => $params['app_key'], 'base_uri' => $params['url']])
                    ->getRobotList();
                if($return['code'] && !empty($return['ReturnJson'])){
                    foreach ($return['ReturnJson']['data'] as $v){
                        if($v['wxid'] == $params['uin']){
                            $v['nickname'] = $v['username'];
                            $v['username'] = $v['wx_num'];
                            $v['headimgurl'] = $v['wx_headimgurl'];
                            return $v;
                        }
                    }
                }
                break;
            default:
                $return = Vlw::init(['app_key' => $params['app_key'], 'base_uri' => $params['url']])
                    ->getCurrentUser(['data' => ['robot_wxid' => $params['uin']]]);
                if($return['code'] && !empty($return['ReturnJson'])){
                    $info = $return['ReturnJson'];
                    $info['username'] = $info['wx_num'];
                    return $info;
                }
                break;
        }
        return [];
    }
}