<?php
/**
 * Created by PhpStorm.
 * Script Name: EventGroupMemberDecrease.php
 * Create: 2022/3/24 15:41
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\cat;

use app\bot\handler\HandlerGroupMemberDecrease;
use ky\Logger;

class EventGroupMemberDecrease extends HandlerGroupMemberDecrease
{
    /**
     * content:
     * array (
        'robot_wxid' => '1688854317341474',
        'from_group' => 'R:10951134140940878',
        'from_group_name' => '傅道集、严选官、每日里严选官',
        'to_wxid' => '1688854317341474',
        'to_name' => '傅道集',
        'time' => '1648109643',
        'clientid' => 6,
        'robot_type' => 1,
    )
     * Author: fudaoji<fdj@kuryun.cn>
     */
    /*public function handle(){
        $this->group = $this->memberM->getOneByMap(['uin' => $this->botWxid, 'wxid' => $this->groupWxid]);
        $this->groupMemberM->rmMember(['bot_id' => $this->bot['id'], 'wxid' => $this->content['to_wxid'], 'group_id' => $this->group['id']]);
    }*/
}