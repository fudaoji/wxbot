<?php
/**
 * Created by PhpStorm.
 * Script Name: HandlerFrieneVerify.php
 * Create: 2022/7/18 13:57
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler;


use app\constants\Addon;
use app\constants\Reply;

class HandlerFriendVerify extends Handler
{
    protected $addonHandlerName = 'friendVerifyHandle';

    public function handle(){
        $this->addon();
    }
}