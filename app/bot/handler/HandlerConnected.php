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
    public function handle(){
        $this->basic();
        $this->addon();
    }

    public function basic(){

    }

    protected function addon()
    {
        $addons = Addon::addons();
        foreach ($addons as $k => $v){
            $class_name = '\\app\\bot\\controller\\' . ucfirst($k);
            if(class_exists($class_name)){
                $class = new $class_name();
                if(method_exists($class, 'loginHandle')){
                    $class->init($this->getAddonOptions())->loginHandle();
                }
            }
        }
    }
}