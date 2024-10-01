<?php
/**
 * Created by PhpStorm.
 * Script Name: EventPrivateChat.php
 * Create: 3/19/22 11:14 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\extian;

use app\bot\handler\HandlerPrivateChat;
use app\constants\Bot;
use app\constants\Task;
use ky\Logger;

class EventPrivateChat extends HandlerPrivateChat
{
    /**
     * 私聊消息接收器
     */
    public function handle(){
        /*if($this->content['fromid'] == 'wxid_xokb2ezu1p6t21'){
            Logger::error($this->content['type']);
        }*/
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
}