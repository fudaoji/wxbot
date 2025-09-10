<?php
/**
 * Created by PhpStorm.
 * Script Name: HandlerFriendVerify.php
 * Create: 2022/7/18 13:57
 * Description: 好友添加请求事件
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\easycom;

use app\bot\handler\Handler;
use app\constants\Bot as BotConst;
use app\constants\Reply;
use ky\Logger;

class EventFriendVerify extends Handler
{
    protected $addonHandlerName = 'friendVerifyHandle';

    public function handle(){
        $this->basic();
        $this->addon();
    }

    protected function basic()
    {
        $this->eventReply();
    }

    protected function eventReply(){
        $replys = model('reply')->getAll([
            'order' => ['sort' => 'desc'],
            'where' => [
                'bot_id' => $this->bot['id'],
                'event' => Reply::MSG,
                'status' => 1,
                'msg_type' => $this->content['type']
            ]
        ]);
        foreach ($replys as $k => $reply){
            if(empty($reply['wxids']) || strpos($reply['wxids'], $this->fromWxid) !== false){
                $bot_info = $this->botClient->getRobotInfo();
                $corpId = isset($bot_info['data']['corpId']) ? $bot_info['data']['corpId'] : '';

                $this->botClient->agreeFriendVerify([
                    'wxid' => ''.$this->fromWxid,
                    'corpId' => $corpId,
                ]);

            }
        }
    }
}