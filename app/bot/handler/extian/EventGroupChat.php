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
}