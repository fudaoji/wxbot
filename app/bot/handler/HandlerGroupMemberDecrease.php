<?php
/**
 * Created by PhpStorm.
 * Script Name: HandlerGroupMemberDecrease.php
 * Create: 2022/7/12 15:03
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler;

use app\constants\Addon;
use ky\Logger;

class HandlerGroupMemberDecrease extends Handler
{
    public function handle(){
        $this->group = $this->memberM->getOneByMap(['uin' => $this->botWxid, 'wxid' => $this->groupWxid]);
        $this->basic();
        $this->addon();
    }

    protected function basic(){
        $this->groupMemberM->rmMember(['bot_id' => $this->bot['id'], 'wxid' => $this->content['to_wxid'], 'group_id' => $this->group['id']]);
    }

    protected function addon()
    {
        $addons = Addon::addons();
        foreach ($addons as $k => $v){
            $class_name = '\\app\\bot\\controller\\' . ucfirst($k);
            if(class_exists($class_name)){
                $class = new $class_name();
                if(method_exists($class, 'groupMemberDecreaseHandle')){
                    $class->groupMemberDecreaseHandle();
                }
            }
        }
    }
}