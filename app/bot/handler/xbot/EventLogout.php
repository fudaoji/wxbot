<?php
/**
 * Created by PhpStorm.
 * Script Name: EventLoginHandle.php
 * Create: 3/19/22 10:59 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\xbot;

use app\bot\handler\HandlerLogin;
use ky\Logger;

class EventLogout extends HandlerLogin
{
    public function handle(){
        $this->basic();
        $this->addon();
    }

    public function basic(){
        $this->botM->updateOne([
            'id' => $this->bot['id'],
            'alive' => 0
        ]);
        //退出客户端程序
        $this->botClient->exit([
            'uuid' => $this->bot['uuid']
        ]);
    }

}