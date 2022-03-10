<?php

/**
 * Created by PhpStorm.
 * Script Name: Test.php
 * Create: 12/20/21 11:49 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\crontab\controller;

use think\Controller;
use app\admin\model\Goods;
use think\Db;
use ky\Bot\Wx;
use YearDley\EasyTBK\Factory;
use YearDley\EasyTBK\JingDong\Request\JdUnionOrderQueryRequest;
use ky\Jtt;

class Bot extends Base
{

    public function initialize()
    {
        parent::initialize();
    }

    /**
     * 发单中心的定时任务
     */
    public function runTask()
    {
        controller('admin/bot', 'task')->runTask();
    }
}
