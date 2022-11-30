<?php
/**
 * Created by PhpStorm.
 * Script Name: HandlerLogout.php
 * Create: 3/19/22 10:59 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler;

use app\constants\Addon;

class HandlerLogout extends Handler
{
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