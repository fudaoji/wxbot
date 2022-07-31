<?php
/**
 * Created by PhpStorm.
 * Script Name: EventPrivateChat.php
 * Create: 3/19/22 11:14 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\My;

use app\bot\handler\HandlerPrivateChat;
use app\constants\Bot;
use app\constants\Reply;
use app\constants\Task;

class EventPrivateChat extends HandlerPrivateChat
{
    private $friend;

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
    private function forward(){
        if($group = model('common/Forward')->getGather([
            'group_wxid' => '',
            'from_wxid' => $this->fromWxid,
            'bot_wxid' => $this->botWxid
        ])) {
            //2.取出机器人负责的群并转发
            $groups = explode(',', $group['wxids']);
            switch ($this->content['type']) {
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

    /**
     * 被添加好友
     * Author: fudaoji<fdj@kuryun.cn>
     */
    private function beAdded(){
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

    /**
     * 关键词回复
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DbException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function keyword(){
        $keywords = model('keyword')->getAll([
            'order' => ['sort' => 'desc'],
            'where' => [
                'bot_id' => $this->bot['id'],
                'keyword' => $this->content['msg'],
                'status' => 1
            ]
        ]);

        $flag = false;
        foreach ($keywords as $keyword){
            if(empty($keyword['wxids'])){
                $where = ['uin' => $this->botWxid];
                if($keyword['user_type']==Task::USER_TYPE_FRIEND){
                    $where['type'] = Bot::FRIEND;
                }elseif($keyword['user_type']==Task::USER_TYPE_GROUP){
                    $where['type'] = Bot::GROUP;
                }
                $keyword['wxids'] = implode(',', $this->memberM->getField('wxid', $where));
            }
            if(strpos($keyword['wxids'], $this->fromWxid) !== false){
                model('reply')->botReply($this->bot, $this->botClient, $keyword, $this->fromWxid);
                $flag = true;
            }
        }
        return $flag;
    }
}