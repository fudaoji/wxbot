<?php
/**
 * Created by PhpStorm.
 * Script Name: EventGroupMemberDecrease.php
 * Create: 2022/3/24 15:41
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\My;

use app\bot\handler\HandlerGroupMemberAdd;
use app\constants\Addon;
use app\constants\Reply;
use ky\Logger;

class EventGroupMemberAdd extends HandlerGroupMemberAdd
{
    /**
     *
    {
        "sdkVer": 5,  // SDK版本号
        "Event": "EventGroupMemberAdd", // 事件（易语言模板的子程序名）
        "content": {
            "robot_wxid": "",  // 机器人账号id
            "from_group": "",  // 群号
            "from_group_name": "",  // 群名
            "guest": [{
                "wxid": "wxid_e6shncy2hlzm32",
                "username": "测试"
            }],  // 新人
            "inviter": {
                "wxid": "wxid_6ungmd6wtdh521",
                "username": "??[奸笑]??"
            },  // 邀请者
            "clientid": 0,  // 企业微信可用
            "robot_type": 0  // 来源微信类型 0 正常微信 / 1 企业微信
        }
    }
     * Author: fudaoji<fdj@kuryun.cn>
     */
    /*public function handle(){
        $this->group = $this->memberM->getOneByMap(['uin' => $this->botWxid, 'wxid' => $this->groupWxid]);
        $this->basic();
        $this->addon();
    }

    private function basic()
    {
        $guest = $this->botClient->getGuest($this->content);
        $nickname = $guest['nickname'];

        $nickname && $this->groupMemberM->addMember([
            'bot_id' => $this->bot['id'],
            'wxid' => $guest['wxid'],
            'group_id' => $this->group['id'],
            'nickname' => $nickname,
            'group_nickname' => $nickname
        ]);
        //回复消息
        $replys = model('reply')->getAll([
            'order' => ['sort' => 'desc'],
            'where' => [
                'bot_id' => $this->bot['id'],
                'event' => Reply::FRIEND_IN,
                'status' => 1
            ]
        ]);
        foreach ($replys as $k => $reply){
            if(empty($reply['wxids']) || strpos($reply['wxids'], $this->groupWxid) !== false){
                model('reply')->botReply($this->bot, $this->botClient, $reply, $this->groupWxid, ['nickname' => $nickname]);
            }
        }
    }

    private function addon()
    {
        $addons = Addon::addons();
        foreach ($addons as $k => $v){
            $class_name = '\\app\\bot\\controller\\' . ucfirst($k);
            if(class_exists($class_name)){
                $class = new $class_name();
                if(method_exists($class, 'groupMemberAddHandle')){
                    controller('bot/'.$k)->groupMemberAddHandle();
                }
            }
        }
    }*/
}