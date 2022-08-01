<?php
/**
 * Created by PhpStorm.
 * Script Name: MyTest.php
 * Create: 7/29/22 10:54 AM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace tests\cases\openai;


use ky\OpenAI\Driver\Weixin;

class WeixinTest extends Base
{
    private $token = 't0ZGUkVJqU930Qq7J275wX3EaERgtA';
    private $encodingAesKey = 'eU65pic2kl2PxGBHs21klnWXRZJYkjrsWtAz6irrRxg';
    protected $appId = '1WSX842fqB1JHSL';


    /**
     * 初始化
     * Author: Jason<dcq@kuryun.cn>
     */
    public function __construct() {
        parent::__construct();
        $this->aiClient = new Weixin([
            'token' => $this->token,
            'appid' => $this->appId,
            'encoding_aes_key' => $this->encodingAesKey
        ]);
    }

    /**
     * 智能对话
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testAIBot() {
        $res = $this->aiClient->smart([
            'userid' => $this->userId,
            'msg' => "美女图片",
            //'first_priority_skills' => ["bid_42249_天气服务"],
            //'second_priority_skills' => ["bid_42249_天气服务"]
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * at群员
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetSignature() {
        $res = $this->aiClient->getSignature([
            'userid' => $this->userId
        ]);
        dump($res);
        $this->assertContains(1, $this->codeArr);
    }

}