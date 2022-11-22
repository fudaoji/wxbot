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

class HandlerFrieneVerify extends Handler
{
    /**
     * 插件处理
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function addon(){
        $addons = Addon::addons();
        foreach ($addons as $k => $v){
            $class_name = '\\app\\bot\\controller\\' . ucfirst($k);
            if(class_exists($class_name)){
                $class = new $class_name();
                if(method_exists($class, 'frieneVerifyHandle')){
                    $class->init($this->getAddonOptions())->frieneVerifyHandle();
                }
            }
        }
    }
}