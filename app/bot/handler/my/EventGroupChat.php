<?php
/**
 * Created by PhpStorm.
 * Script Name: EventGroupChat.php
 * Create: 3/19/22 11:39 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\my;

use app\bot\handler\HandlerGroupChat;
use app\constants\Bot;
use app\constants\Rule;
use app\constants\Task;
use ky\WxBot\Driver\My;
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
            case My::MSG_TEXT:
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
                case My::MSG_TEXT:
                    $this->botClient->sendTextToFriends([
                        'robot_wxid' => $this->content['robot_wxid'],
                        'to_wxid' => $groups,
                        'msg' => $this->content['msg']]);
                    break;
                case My::MSG_LINK:
                    if ($this->bot['protocol'] == Bot::PROTOCOL_MYCOM) {
                        $msg = json_decode($this->content['msg'], true)['Link'][0];
                        $url = $msg['url'];
                        $this->botClient->sendShareLinkToFriends([
                            'robot_wxid' => $this->content['robot_wxid'],
                            'to_wxid' => $groups,
                            'url' => $url,
                            'image_url' => empty($msg['image_url']) ? 'https://zyx.images.huihuiba.net/default-headimg.png' : $msg['image_url'],
                            'title' => $msg['title'],
                            'desc' => $msg['desc']
                        ]);
                    } else { //个微
                        $this->botClient->forwardMsgToFriends([
                            'robot_wxid' => $this->botWxid,
                            'to_wxid' => $groups,
                            'msgid' => $this->content['msg_id']
                        ]);
                    }
                    break;
                default:
                    if ($this->bot['protocol'] == Bot::PROTOCOL_MYCOM) {
                        $this->botClient->sendTextToFriends([
                            'robot_wxid' => $this->content['robot_wxid'],
                            'to_wxid' => $groups,
                            'msg' => $this->content['msg']
                        ]);
                    } else { //个微
                        $this->botClient->forwardMsgToFriends([
                            'robot_wxid' => $this->botWxid,
                            'to_wxid' => $groups,
                            'msgid' => $this->content['msg_id']
                        ]);
                    }
                    break;
            }
        }
    }
}