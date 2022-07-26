<?php
/**
 * Created by PhpStorm.
 * Script Name: EventSendOutMsg.php
 * Create: 2022/7/6 11:47
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\cat;

use app\bot\handler\Handler;
use ky\Logger;

class EventSendOutMsg extends Handler
{
    /**
     * 消息成功回调接收器
     */
    public function handle(){
        //Logger::error($this->content);
    }
}