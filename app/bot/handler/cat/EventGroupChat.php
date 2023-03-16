<?php
/**
 * Created by PhpStorm.
 * Script Name: EventGroupChat.php
 * Create: 3/19/22 11:39 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\cat;

use app\bot\handler\HandlerGroupChat;
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
     * 关键词回复
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DbException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    /*public function keyword(){
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

            if(strpos($keyword['wxids'], $this->groupWxid) !== false){
                model('reply')->botReply($this->bot, $this->botClient, $keyword, $this->groupWxid, ['nickname' => $this->content['from_name']]);
                $flag = true;
            }
        }

        return $flag;
    }*/
}