<?php
/**
 * Created by PhpStorm.
 * Script Name: HandlerGroupEstablish.php
 * Create: 2022/7/18 13:57
 * Description: 创建新群事件
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler;

use app\constants\Addon;
use app\constants\Bot;
use app\constants\Reply;
use app\constants\Rule;
use app\constants\Task;
use ky\Logger;

class HandlerGroupEstablish extends Handler
{
    protected $addonHandlerName = 'groupEstablishHandle';
}