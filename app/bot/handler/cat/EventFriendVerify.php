<?php
/**
 * Created by PhpStorm.
 * Script Name: EventFrieneVerify.php
 * Create: 3/19/22 9:19 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\cat;

use app\bot\handler\Handler;
use ky\Logger;

class EventFriendVerify extends Handler
{
    public function handle(){
        Logger::error($this->content);
    }
}