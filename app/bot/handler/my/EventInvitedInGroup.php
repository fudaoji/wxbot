<?php
/**
 * Created by PhpStorm.
 * Script Name: EventInvitedInGroup.php
 * Create: 2023/5/29 9:00
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\my;

use app\bot\handler\HandlerInvitedInGroup;

class EventInvitedInGroup extends HandlerInvitedInGroup
{
    /**
     * 被邀请入群事件
     */
    public function handle(){
        $this->addon();
    }
}