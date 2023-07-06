<?php
/**
 * Created by PhpStorm.
 * Script Name: EventDeviceCallback.php
 * Create: 3/19/22 11:33 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\my;

use app\bot\handler\HandlerDeviceCallback;
use app\constants\Addon;

class EventDeviceCallback extends HandlerDeviceCallback
{
    public function handle(){
        $this->addon();
        // Logger::error($this->content);
    }
}