<?php
/**
 * Created by PhpStorm.
 * Script Name: BotTest.php
 * Create: 2022/7/27 16:00
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace tests\cases\bot;


use app\admin\model\Bot;
use tests\UnitTestCase;

class BotTest extends UnitTestCase
{
    protected $botM;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->botM = new Bot();
    }
}