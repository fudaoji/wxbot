<?php
/**
 * Created by PhpStorm.
 * Script Name: EventGroupChat.php
 * Create: 3/19/22 11:39 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\extian;

use app\bot\handler\HandlerGroupChat;
use app\constants\Bot;
use ky\Logger;

class EventGroupChat extends HandlerGroupChat
{
    /**
     * 群聊消息接收器
     */
    public function handle(){
        $this->group = $this->memberM->getOneByMap(['uin' => $this->bot['uin'], 'wxid' => $this->groupWxid]);
        $this->basic();
        $this->addon();
    }

    /**
     * 基本处理
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function basic(){
        //新群入库
        $this->newGroup();
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

    protected function newGroup(){
        if(empty($this->group)){
            $this->isNewGroup = true;
            $this->group = $this->memberM->addGroup([
                'bot' => $this->bot,
                'nickname' => filter_emoji($this->groupName) ?: $this->groupName,
                'wxid' => $this->groupWxid,
                //'headimgurl' => $this->content['headImg'] ?? ''
            ]);
        }elseif (empty($this->group['nickname'])){
            $this->memberM->updateOne(['id' => $this->group['id'], 'nickname' => $this->groupName]);
            $this->group = $this->memberM->getOneByMap(['uin' => $this->bot['uin'], 'wxid' => $this->groupWxid, true, true]);
        }
    }
}