<?php
/**
 * Created by PhpStorm.
 * Script Name: HandlerInvitedInGroup.php
 * Create: 2023/5/29 8:57
 * Description: 被邀请入群事件 //企微信不传递此事件
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler;


class HandlerInvitedInGroup extends Handler
{
    protected $addonHandlerName = 'InvitedInGroupHandle';
}