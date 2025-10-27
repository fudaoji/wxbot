<?php
/**
 * Created by PhpStorm.
 * Script Name: EventFrieneVerify.php
 * Create: 3/19/22 9:19 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\extian;

use app\bot\handler\HandlerFriendVerify;
use app\constants\Reply;
use ky\Logger;

class EventFriendVerify extends HandlerFriendVerify
{
    public function handle(){
        $this->basic();
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
                        $this->botClient->agreeFriendVerify([
                            'uuid' => $this->bot['uuid'],
                            'robot_wxid' => $this->bot['uin'],
                            'encryptusername' => $this->content['encryptusername'],
                            'ticket' => $this->content['ticket'],
                            'scene' => $this->content['scene']
                        ]);

                        //增加其他通过好友的动作
                        if(!empty($reply['group_wxids'])){
                            $groups = explode(',', $reply['group_wxids']);
                            foreach ($groups as $group_wxid){ //自动拉群
                                $this->botClient->inviteInGroupByLink([
                                    'robot_wxid' => $this->botWxid,
                                    'group_wxid' => $group_wxid,
                                    'to_wxid' => $this->fromWxid
                                ]);
                            }
                        }
                        break;
                }
            }
        }
    }
}