<?php
/**
 * Created by PhpStorm.
 * Script Name: MyTest.php
 * Create: 7/29/22 10:54 AM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace tests\cases\openai;

use app\common\model\ai\Config;
use ky\OpenAI\Driver\AIEdu;

class AIEduTest extends Base
{
    /**
     * @var Config
     */
    private $configM;
    private $bot;
    private $configs;

    public function __construct() {
        parent::__construct();
        $this->configM = new Config();
        $this->bot = model('admin/bot')->getOne(5);
        $this->configs = $this->configM->getConf(['bot_id' => $this->bot['id']]);
        $this->aiClient = $this->configM->getAiClient($this->bot, $this->configs['driver']);
    }

    /**
     * 检查用量
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testCheckKey() {
        $res = $this->aiClient->checkKey();
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 智能对话
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testAnswer() {
        $res = $this->aiClient->smart([
            'msg' => "你觉得百度的文心一言怎么样?"
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }
}