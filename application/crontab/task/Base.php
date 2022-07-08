<?php
/**
 * Created by PhpStorm.
 * Script Name: Base.php
 * Create: 12/24/21 10:46 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\crontab\task;


class Base
{
    public function __construct()
    {
        model('common/setting')->settings();
    }
}