<?php
/**
 * Created by PhpStorm.
 * Script Name: EventGroupMemberDecrease.php
 * Create: 2022/3/24 15:41
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\webgo;

use app\bot\controller\Api;
use app\bot\handler\HandlerGroupMemberAdd;
use app\constants\Addon;
use app\constants\Bot;
use app\constants\Reply;
use ky\Logger;

class EventGroupMemberAdd extends HandlerGroupMemberAdd
{

    public function handle(){
        //web 端暂时无此事件
    }


}