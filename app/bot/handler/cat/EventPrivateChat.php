<?php
/**
 * Created by PhpStorm.
 * Script Name: EventPrivateChat.php
 * Create: 3/19/22 11:14 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\cat;

use app\bot\handler\Handler;
use app\bot\handler\HandlerPrivateChat;
use app\constants\Addon;
use app\constants\Bot;
use app\constants\Reply;
use app\constants\Task;
use ky\Logger;

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
            $this->botClient->forwardMsgToFriends([
                'robot_wxid' => $this->botWxid,
                'to_wxid' => $groups,
                'msg' => $this->content['msgid']
            ]);
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
     * @throws \think\exception\DbException
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