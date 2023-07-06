<?php
/**
 * Created by PhpStorm.
 * Script Name: MyTest.php
 * Create: 7/29/22 10:54 AM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace tests\cases\openai;

use ky\OpenAI\Driver\Qyk;

class QykTest extends Base
{
    protected $appId = 0;


    /**
     * 初始化
     * Author: Jason<dcq@kuryun.cn>
     */
    public function __construct() {
        parent::__construct();
        $this->aiClient = new Qyk([
            'appid' => $this->appId
        ]);
    }

    /**
     * 智能对话
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testAIBot() {
        $res = $this->aiClient->smart([
            'msg' => "厦门天气"
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }
}