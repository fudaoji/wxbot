<?php
/**
 * Created by PhpStorm.
 * Script Name: HandlerLoginCode.php
 * Create: 3/19/22 10:59 PM
 * Description: 返回登录码
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler;

use app\constants\Addon;

class HandlerConnected extends Handler
{
    protected $addonHandlerName = 'loginHandle';

    public function handle(){
        $this->basic();
        $this->addon();
    }

    public function basic(){

    }
}