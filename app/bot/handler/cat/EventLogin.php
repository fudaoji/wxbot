<?php
/**
 * Created by PhpStorm.
 * Script Name: EventLoginHandle.php
 * Create: 3/19/22 10:59 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\cat;

use app\bot\handler\HandlerLogin;

class EventLogin extends HandlerLogin
{
    /**
     *  登录、退出事件处理器
     * {
        "event":"EventLogin",
        "robot_wxid":"wxid_5hxa04j4z6pg22",
        "robot_name":"",
        "type":1,//账号离线, 0上线
        "from_wxid":"",
        "from_name":"",
        "final_from_wxid":"",
        "final_from_name":"",
        "to_wxid":"",
        "msg":""
    }

     */
    /*public function handle(){
        $this->botM->updateOne([
            'id' => $this->bot['id'],
            'alive' => $this->content['type'] ? 0 : 1
        ]);
    }*/

}