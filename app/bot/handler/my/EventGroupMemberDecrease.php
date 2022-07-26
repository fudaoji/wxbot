<?php
/**
 * Created by PhpStorm.
 * Script Name: EventGroupMemberDecrease.php
 * Create: 2022/3/24 15:41
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\My;

use app\bot\handler\HandlerGroupMemberDecrease;
use ky\Logger;

class EventGroupMemberDecrease extends HandlerGroupMemberDecrease
{

    /**
     *
     * Author: fudaoji<fdj@kuryun.cn>
     */
    /*public function handle(){
        $this->group = $this->memberM->getOneByMap(['uin' => $this->botWxid, 'wxid' => $this->groupWxid]);
        $this->groupMemberM->rmMember(['bot_id' => $this->bot['id'], 'wxid' => $this->content['to_wxid'], 'group_id' => $this->group['id']]);
    }*/
}