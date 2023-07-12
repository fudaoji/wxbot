<?php
/**
 * Created by PhpStorm.
 * Script Name: HandlerPrivateChat.php
 * Create: 2022/7/18 13:57
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler;

use app\constants\Bot;
use app\constants\Reply;
use app\constants\Task;
use ky\Logger;

class HandlerPrivateChat extends Handler
{
    protected $friend;
    protected $addonHandlerName = 'privateChatHandle';

    /**
     * 消息转播
     * Author: fudaoji<fdj@kuryun.cn>
     */
    protected function forward(){
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
                'msgid' => $this->content['msg_id']
            ]);
        }
    }

    /**
     * 关键词回复
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DbException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    protected function keyword(){
        $this->ignoreKeyword($this->content['msg']);
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
                if($keyword['user_type'] == Task::USER_TYPE_FRIEND){
                    $where['type'] = Bot::FRIEND;
                }elseif($keyword['user_type'] == Task::USER_TYPE_GROUP){
                    $where['type'] = Bot::GROUP;
                }
                $keyword['wxids'] = implode(',', $this->memberM->getField('wxid', $where));
            }
            if(strpos($keyword['wxids'], $this->fromWxid) !== false){
                model('reply')->botReply($this->bot, $this->botClient, $keyword, $this->fromWxid);
                $flag = true;
                parent::$replied = true;
            }
        }
        return $flag;
    }

    /**
     * 被添加好友
     * Author: fudaoji<fdj@kuryun.cn>
     */
    protected function beAdded(){
        if(empty($this->friend)){
            $reply_m = new \app\common\model\Reply();
            $this->isNewFriend = true;

            $this->friend = $this->memberM->addFriend([
                'bot' => $this->bot,
                'nickname' => filter_emoji($this->fromName),
                'wxid' => $this->fromWxid
            ]);

            //回复消息
            $replys = $reply_m->getAll([
                'order' => ['sort' => 'desc'],
                'where' => [
                    'bot_id' => $this->bot['id'],
                    'event' => Reply::BEADDED,
                    'status' => 1
                ]
            ]);
            foreach ($replys as $k => $reply){
                if(empty($reply['wxids']) || strpos($reply['wxids'], $this->fromWxid) !== false){
                    $reply_m->botReply($this->bot, $this->botClient, $reply, $this->fromWxid);
                }
            }
        }else{
            empty($this->fromName) && $this->fromName = $this->friend['nickname']; //兼容个别驱动
        }
    }

    /**
     * 针对消息事件的特殊响应
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function eventReply(){
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
                switch ($reply['handle_type']){ //todo
                    case Reply::HANDLE_TRANSFER_REFUSE:

                        break;
                    case Reply::HANDLE_TRANSFER_ACCEPT:

                        break;
                    case Reply::HANDLE_DEL:
                        $this->botClient->deleteFriend(['robot_wxid' => $this->botWxid, 'to_wxid' => $this->fromWxid]);
                        break;
                    case Reply::HANDLE_MSG:
                        model('reply')->botReply($this->bot, $this->botClient, $reply, $this->fromWxid);
                        break;
                }
            }
        }
    }
}