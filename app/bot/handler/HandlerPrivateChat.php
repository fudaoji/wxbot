<?php
/**
 * Created by PhpStorm.
 * Script Name: HandlerPrivateChat.php
 * Create: 2022/7/18 13:57
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler;


use app\constants\Addon;
use app\constants\Reply;

class HandlerPrivateChat extends Handler
{
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
                switch ($reply['handle_type']){
                    case Reply::HANDLE_DEL:
                        $this->botClient->deleteFriend(['robot_wxid' => $this->botWxid, 'to_wxid' => $this->fromWxid]);
                        break;
                    default:
                        model('reply')->botReply($this->bot, $this->botClient, $reply, $this->fromWxid);
                        break;
                }
            }
        }
    }

    /**
     * 插件处理
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function addon(){
        $addons = Addon::addons();
        foreach ($addons as $k => $v){
            $class_name = '\\app\\bot\\controller\\' . ucfirst($k);
            if(class_exists($class_name)){
                $class = new $class_name();
                if(method_exists($class, 'privateChatHandle')){
                    $class->init($this->getAddonOptions())->privateChatHandle();
                }
            }
        }
    }
}