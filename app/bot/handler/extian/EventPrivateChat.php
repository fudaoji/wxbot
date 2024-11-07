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
use app\constants\Reply;
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

    /**
     * 被添加好友
     * Author: fudaoji<fdj@kuryun.cn>
     */
    protected function beAdded(){
        if(empty($this->friend)){
            $this->friend = $this->memberM->addFriend([
                'bot' => $this->bot,
                'nickname' => filter_emoji($this->fromName),
                'wxid' => $this->fromWxid,
                'headimgurl' => $this->content['headImg'] ?? ''
            ]);
        } else{
            empty($this->fromName) && $this->fromName = $this->friend['nickname']; //兼容个别驱动
        }

        if(strpos($this->content['msg'],'我通过了你的朋友验证请求，现在我们可以开始聊天了') !== false ||
            ($this->content['type'] == Bot::MSG_SYS && (strpos($this->content['msg'],'你已添加了') !== false))){
            $this->isNewFriend = true;
        }else{
            exit(0); //说明不是新好友
        }

        $reply_m = new \app\common\model\Reply();
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
            if(empty($reply['medias'])){ //兼容旧版
                if(empty($reply['wxids']) || strpos($reply['wxids'], $this->fromWxid) !== false){
                    $reply_m->botReply($this->bot, $this->botClient, $reply, $this->fromWxid, ['nickname' => $this->fromName]);
                }
            }else{
                $medias = json_decode($reply['medias'], true);
                foreach ($medias as $media) {
                    $reply['media_type'] = $media['type'];
                    $reply['media_id'] = $media['id'];

                    if(empty($reply['wxids']) || strpos($reply['wxids'], $this->fromWxid) !== false){
                        $reply_m->botReply($this->bot, $this->botClient, $reply, $this->fromWxid, ['nickname' => $this->fromName]);
                    }
                }
            }
        }
    }
}