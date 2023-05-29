<?php
/**
 * Created by PhpStorm.
 * Script Name: EventQRcodePayment.php
 * Create: 2023/5/29 9:04
 * Description: 面对面收款事件
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\my;


use app\bot\handler\HandlerQRcodePayment;

class EventQRcodePayment extends HandlerQRcodePayment
{
    public function handle(){
        $this->addon();
    }
}