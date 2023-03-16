<?php
/**
 * Created by PhpStorm.
 * Script Name: EventPrivateChat.php
 * Create: 3/19/22 11:14 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\webgo;

use app\bot\handler\HandlerPrivateChat;
use app\constants\Addon;
use app\constants\Bot;
use app\constants\Reply;
use app\constants\Task;
use ky\Logger;

class EventPrivateChat extends HandlerPrivateChat
{
    /**
     * 私聊消息接收器
     */
    public function handle(){
        $this->friend = $this->memberM->getOneByMap(['uin' => $this->bot['uin'], 'wxid' => $this->fromWxid]);
        $this->basic();
        $this->addon();
    }

    /**
     * 基本处理
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function basic(){
        $this->beAdded();
        //消息转播
        $this->forward();

        switch ($this->content['type']){
            case Bot::MSG_TEXT:
                $this->keyword();
                break;
        }

        //针对消息事件的特殊响应
        $this->eventReply();
    }

    /**
     * 消息转播
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function forward(){
        if($group = model('common/Forward')->getGather([
            'group_wxid' => '',
            'from_wxid' => $this->fromWxid,
            'bot_wxid' => $this->botWxid
        ])) {
            //2.取出机器人负责的群并转发
            $groups = explode(',', $group['wxids']);
            switch ($this->content['type']) {
                case Bot::MSG_IMG:
                    $this->botClient->sendImgToFriends([
                        'robot_wxid' => $this->content['robot_wxid'],
                        'to_wxid' => $groups,
                        'path' => $this->content['msg']]);
                    break;
                case Bot::MSG_VIDEO:
                    $this->botClient->sendVideoToFriends([
                        'robot_wxid' => $this->content['robot_wxid'],
                        'to_wxid' => $groups,
                        'path' => $this->content['msg']]);
                    break;
                case Bot::MSGTYPE_FILE:
                    $this->botClient->sendFileToFriends([
                        'robot_wxid' => $this->content['robot_wxid'],
                        'to_wxid' => $groups,
                        'path' => $this->content['msg']]);
                    break;
                case Bot::MSG_LINK:
                    //todo
                    break;
                default:
                    $this->botClient->sendTextToFriends([
                        'robot_wxid' => $this->content['robot_wxid'],
                        'to_wxid' => $groups,
                        'msg' => $this->content['msg']
                    ]);
                    break;
            }
        }
    }

    /**
     * 被添加好友
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function beAdded(){
        if(empty($this->friend)){
            $this->friend = $this->memberM->addFriend([
                'bot' => $this->bot,
                'nickname' => filter_emoji($this->content['from_name']),
                'wxid' => $this->content['from_wxid']
            ]);

            //回复消息
            $replys = model('reply')->getAll([
                'order' => ['sort' => 'desc'],
                'where' => [
                    'bot_id' => $this->bot['id'],
                    'event' => Reply::BEADDED,
                    'status' => 1
                ]
            ]);
            foreach ($replys as $k => $reply){
                if(empty($reply['wxids']) || strpos($reply['wxids'], $this->fromWxid) !== false){
                    model('reply')->botReply($this->bot, $this->botClient, $reply, $this->fromWxid);
                }
            }
        }
    }
}