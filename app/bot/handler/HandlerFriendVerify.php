<?php
/**
 * Created by PhpStorm.
 * Script Name: HandlerFriendVerify.php
 * Create: 2022/7/18 13:57
 * Description: 好友添加请求事件
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler;

use app\constants\Reply;
use ky\Logger;

class HandlerFriendVerify extends Handler
{
    protected $addonHandlerName = 'friendVerifyHandle';

    public function handle(){
        $this->addon();
    }

    protected function basic()
    {
        //Logger::error('自动同意好友申请！');
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
                switch ($reply['handle_type']){
                    case Reply::HANDLE_ADDED_ACCEPT:
                        //Logger::error($reply['handle_type']);
                        $this->botClient->agreeFriendVerify([
                            'uuid' => $this->bot['uuid'],
                            'robot_wxid' => $this->bot['uin'],
                            'encryptusername' => $this->content['encryptusername'],
                            'ticket' => $this->content['ticket'],
                            'scene' => $this->content['scene']
                        ]);
                        break;
                }
            }
        }
    }
}