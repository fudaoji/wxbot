<?php
/**
 * Created by PhpStorm.
 * Script Name: EventGroupMemberDecrease.php
 * Create: 2022/3/24 15:41
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\vlw;


use app\bot\controller\Api;
use ky\Logger;

class EventGroupMemberAdd extends Api
{
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
    }

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
    public function handle(){
        $this->groupWxid = $this->content['from_group'];
        $this->group = $this->memberM->getOneByMap(['uin' => $this->botWxid, 'wxid' => $this->groupWxid]);
        $guest = $this->content['guest'];
        $this->groupMemberM->addMember([
            'bot_id' => $this->bot['id'],
            'wxid' => $guest['wxid'],
            'group_id' => $this->group['id'],
            'nickname' => filter_emoji($guest['username'])
        ]);
        //Logger::error($this->content);
    }
}