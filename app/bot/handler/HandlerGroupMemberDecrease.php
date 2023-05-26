<?php
/**
 * Created by PhpStorm.
 * Script Name: HandlerGroupMemberDecrease.php
 * Create: 2022/7/12 15:03
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler;


class HandlerGroupMemberDecrease extends Handler
{
    protected $addonHandlerName = 'groupMemberDecreaseHandle';

    public function handle(){
        $this->group = $this->memberM->getOneByMap(['uin' => $this->botWxid, 'wxid' => $this->groupWxid]);
        $this->basic();
        $this->addon();
    }

    protected function basic(){
        $this->groupMemberM->rmMember(['bot_id' => $this->bot['id'], 'wxid' => $this->content['to_wxid'], 'group_id' => $this->group['id']]);
    }
}