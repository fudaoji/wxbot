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
    protected $robotJane = 'wxid_a98qqf9m4bny22';
    protected $robotComDj = '1688854317341474';
    protected $robotDj = 'wxid_xokb2ezu1p6t21';
    protected $robotFjq = 'wxid_7v3b6hncdo6f12';
    protected $wxidDj = 'wxid_xokb2ezu1p6t21';
    protected $wxidYyp = 'weiwei562608';
    protected $wxidYlp = 'wxid_ze4ojaebxstd22';
    protected $wxidDcq = 'wxid_96uxa953gra341';
    protected $group51 = '22972226702@chatroom';
    protected $groupTd = '21361397515@chatroom';
    protected $groupTest = '21160862376@chatroom';
    protected $comDj = '7881299942929761';

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->botM = new Bot();
    }
}