<?php
/**
 * Created by PhpStorm.
 * Script Name: EventLoginHandle.php
 * Create: 3/19/22 10:59 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\vlw;

use app\bot\handler\HandlerLogin;
use ky\Logger;

class EventLogin extends HandlerLogin
{
    /**
     *  登录、退出事件处理器
     *  {"sdkVer":5,"Event":"Login","content":{"type":1,"Wxid":"wxid_xokb2ezu1p6t21","robot_type":0}}
     */
    /*public function handle(){
        $this->botM->updateOne([
            'id' => $this->bot['id'],
            'alive' => $this->content['type'] ? 0 : 1
        ]);
    }*/

}