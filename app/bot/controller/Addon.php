<?php
/**
 * Created by PhpStorm.
 * Script Name: Addon.php
 * Create: 2022/4/18 10:58
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\controller;

use app\bot\handler\Handler;

class Addon extends Handler
{
    public function init($options = []){
        $this->botClient = $options['bot_client'];
        $this->botWxid = $options['bot_wxid'];
        $this->fromWxid = $options['from_wxid'];
        $this->fromName = $options['from_name'];
        $this->groupWxid = $options['group_wxid'];
        $this->groupName = $options['group_name'];
        $this->group = $options['group'];
        $this->content = $options['content'];
        $this->isNewFriend = $options['is_new_friend'];

        $this->driver = $options['driver'];
        $this->ajaxData = $options['ajax_data'];
        $this->event = $options['event'];
        $this->bot = $options['bot'];
        $this->beAtStr = $options['be_at_str'];
        return $this;
    }
}