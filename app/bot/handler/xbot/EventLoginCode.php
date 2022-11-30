<?php
/**
 * Created by PhpStorm.
 * Script Name: EventLoginHandle.php
 * Create: 3/19/22 10:59 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\xbot;

use app\bot\handler\HandlerLoginCode;
use app\constants\Bot as BotConst;
use ky\Logger;

class EventLoginCode extends HandlerLoginCode
{
    public function handle(){
        //缓存起来供后台获取
        $this->botM->cacheLoginCode(BotConst::PROTOCOL_XBOT, request()->server('REMOTE_ADDR'), $this->ajaxData);

        Logger::error($this->botM->cacheLoginCode(BotConst::PROTOCOL_XBOT, request()->server('REMOTE_ADDR')));
    }
}