<?php
/**
 * Created by PhpStorm.
 * Script Name: OvooaTest.php
 * Create: 5/28/22 12:12 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace tests\cases;
use ky\Ovooa;
use tests\UnitTestCase;

class OvooaTest extends UnitTestCase
{
    /**
     * @var Ovooa
     */
    private $ov;

    /**
     * 初始化
     * Author: Jason<dcq@kuryun.cn>
     */
    public function __construct() {
        parent::__construct();
        $this->ov = new Ovooa();
    }

    /**
     * 咪咕音乐
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testMigu() {
        $res = $this->ov->migu([
            'msg' => '世上只有妈妈好',
            'n' => 1,
            ///'type' => 'json',
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

}