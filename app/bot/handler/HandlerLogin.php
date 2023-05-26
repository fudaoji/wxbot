<?php
/**
 * Created by PhpStorm.
 * Script Name: EventLoginHandle.php
 * Create: 3/19/22 10:59 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler;

class HandlerLogin extends Handler
{
    protected $addonHandlerName = 'loginHandle';

    public function handle(){
        $this->basic();
        $this->addon();
    }

    public function basic(){
        $this->botM->updateOne([
            'id' => $this->bot['id'],
            'alive' => $this->content['type'] ? 0 : 1
        ]);
    }
}