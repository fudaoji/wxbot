<?php
/**
 * Created by PhpStorm.
 * Script Name: EventPrivateChat.php
 * Create: 3/19/22 11:14 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\my;

use app\bot\handler\HandlerPrivateChat;
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
            case Bot::MSG_APP:
                Logger::error($this->content);
                break;
            case Bot::MSG_TEXT:
                $this->keyword();
                break;
            case Bot::MSG_VOICE:
                //Logger::error($this->content);
                break;
            case Bot::MSG_FILE:
                //Logger::error($this->content);
                break;
            //other types
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
                case Bot::MSG_APP:
                    $this->botClient->sendXmlToFriends([
                        'robot_wxid' => $this->content['robot_wxid'],
                        'to_wxid' => $groups,
                        'xml' => $this->content['msg']
                    ]);
                    break;
                case Bot::MSG_FILE:
                    $begin_time = time();
                    do{
                        $res = $this->botClient->downloadFile([
                            'robot_wxid' => $this->botWxid,
                            'to_wxid' => $groups,
                            'path' => str_replace(["[File=", "]"], "", $this->content['msg'])
                        ]);
                    }while($res['code'] == 0 && $begin_time + 60 > time());

                    $this->botClient->sendTextToFriends([
                        'robot_wxid' => $this->botWxid,
                        'to_wxid' => $groups,
                        'msg' => $this->content['msg']
                    ]);
                    break;
                case Bot::MSG_VIDEO:
                    $begin_time = time();
                    do{
                        $res = $this->botClient->downloadFile([
                            'robot_wxid' => $this->botWxid,
                            'to_wxid' => $groups,
                            'path' => str_replace(["[File=", "]"], "", $this->content['msg'])
                        ]);
                    }while($res['code'] == 0 && $begin_time + 120 > time());
                    $this->botClient->forwardMsgToFriends([
                        'robot_wxid' => $this->botWxid,
                        'to_wxid' => $groups,
                        'msgid' => $this->content['msg_id']
                    ]);
                    break;
                case Bot::MSG_TEXT:
                    $this->botClient->sendTextToFriends([
                        'robot_wxid' => $this->content['robot_wxid'],
                        'to_wxid' => $groups,
                        'msg' => $this->content['msg']]);
                    break;
                case Bot::MSG_LINK:
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
                        $res = $this->botClient->forwardMsgToFriends([
                            'robot_wxid' => $this->botWxid,
                            'to_wxid' => $groups,
                            'msgid' => $this->content['msg_id']
                        ]);
                        Logger::error($res);
                    }
                    break;
            }
        }
    }
}