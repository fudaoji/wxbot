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

use ky\WxBot\Client;
use app\constants\Bot as BotConst;
use ky\Logger;
use ky\WxBot\Driver\Cat;
use ky\WxBot\Driver\My;
use ky\WxBot\Driver\Vlw;
use ky\WxBot\Driver\Webgo;
use ky\WxBot\Driver\Wxwork;

class Bot extends Base
{
    /**
     * 获取机器人客户端
     * @param array $bot
     * @return Cat|Vlw|Wxwork|Webgo
     * @throws \Exception
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getRobotClient($bot = []){
        $options = ['app_key' => $bot['app_key'], 'base_uri' => $bot['url'], 'uuid' => $bot['uuid']];
        config('system.bot.step_time') && $options['step_time'] = explode('-', config('system.bot.step_time'));
        return Client::getInstance($options, $bot['protocol'])->getBot();
    }

    /**
     * 获取机器人信息
     * @param array $params
     * @return array|string
     * Author: fudaoji<fdj@kuryun.cn>
     * @throws \Exception
     */
    public function getRobotInfo($params = []){
        /**
         * @var $bot_client Vlw|Cat|Wxwork|My
         */
        $bot_client = $this->getRobotClient($params);
        switch ($params['protocol']){
            case BotConst::PROTOCOL_CAT:
                $return = $bot_client->getRobotList();
                if($return['code'] && !empty($return['data'])){
                    foreach ($return['data'] as $v){
                        if($v['wxid'] == $params['uin']){
                            $v['username'] = $v['wx_num'];
                            return $v;
                        }
                    }
                }else{
                    return  $bot_client->getError();
                }
                break;
            case BotConst::PROTOCOL_WXWORK:
            case BotConst::PROTOCOL_MYCOM:
            case BotConst::PROTOCOL_MY:
                $return = $bot_client->getRobotList();
                if($return['code'] && !empty($return['ReturnJson'])){
                    foreach ($return['ReturnJson']['data'] as $v){
                        if($v['wxid'] == $params['uin']){
                            $v['nickname'] = $v['username'];
                            $v['username'] = $v['wx_num'];
                            $v['headimgurl'] = $v['wx_headimgurl'];
                            return $v;
                        }
                    }
                }else{
                    return  $bot_client->getError();
                }
                break;
            default:
                $return = $bot_client->getCurrentUser(['data' => ['robot_wxid' => $params['uin']]]);
                if($return['code'] && !empty($return['ReturnJson'])){
                    $info = $return['ReturnJson'];
                    $info['username'] = $info['wx_num'];
                    return $info;
                }else{
                    return  $bot_client->getError();
                }
                break;
        }
        return [];
    }
}