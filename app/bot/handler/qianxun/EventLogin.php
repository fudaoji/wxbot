<?php
/**
 * Created by PhpStorm.
 * Script Name: EventLogin.php
 * Create: 2022/9/1 10:08
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\qianxun;

use app\bot\handler\HandlerLogin;
use ky\Logger;

class EventLogin extends HandlerLogin
{
    /**
     *  登录、退出事件处理器
     *  {
        "event": 10014,
        "wxid": "wxid_3sq4tklb6c3121",
        "data": {
            "type": 1,  //1登录 0退出
            "wxid": "wxid_3sq4tklb6c3121",
            "port": 7335
            }
        }
     */
    /*public function handle(){
        $this->botM->updateOne([
            'id' => $this->bot['id'],
            'alive' => $this->content['type'] ? 0 : 1
        ]);
    }*/

}