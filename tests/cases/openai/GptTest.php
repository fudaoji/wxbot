<?php
/**
 * Created by PhpStorm.
 * Script Name: MyTest.php
 * Create: 7/29/22 10:54 AM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace tests\cases\openai;

use ky\OpenAI\Driver\Gpt;

class GptTest extends Base
{
    public function __construct() {
        parent::__construct();
        $this->aiClient = new Gpt();
    }

    /**
     * 智能对话
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testAIBot() {
        $res = $this->aiClient->smart([
            'msg' => "文心一言是什么?"
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }
}