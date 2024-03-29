<?php
/**
 * Created by PhpStorm.
 * Script Name: EventGroupChat.php
 * Create: 3/19/22 11:39 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\webgo;

use app\bot\handler\Handler;
use app\bot\handler\HandlerGroupChat;
use app\constants\Addon;
use app\constants\Bot;
use app\constants\Rule;
use app\constants\Task;
use ky\Logger;

class EventGroupChat extends HandlerGroupChat
{
    /**
     * 群聊消息接收器
     */
    public function handle(){
        $this->basic();
        $this->addon();
    }

    /**
     * 基本处理
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function basic(){
        //消息转播
        $this->forward();
        //其他功能
        switch ($this->content['type']){
            case Bot::MSG_TEXT:
                if($this->keyword()) return;
                if($this->rmGroupMember()) return;
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
            'group_wxid' => $this->groupWxid,
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
}