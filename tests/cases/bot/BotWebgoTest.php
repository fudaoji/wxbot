<?php
/**
 * Created by PhpStorm.
 * Script Name: BotTest.php
 * Create: 2022/2/23 10:48
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace tests\bot\cases;

use ky\WxBot\Driver\Webgo;
use tests\cases\bot\BotTest;

class BotWebgoTest extends BotTest
{
    private $bot;
    /**
     * @var Webgo
     */
    private $client;
    private $wxidDj = '@e395a5245b20e7888cad96991e36e0d5fc85b496450e59604fd98589e6e29f8d';
    private $wxidJane = '@a0f7bf23f82168160b49c37378311bb97a6c73a38f89ef67bf613235ce0ec9af';

    /**
     * 初始化
     * Author: Jason<dcq@kuryun.cn>
     */
    public function __construct() {
        parent::__construct();
        $this->bot = $this->botM->getOne(14);
        $this->client = $this->botM->getRobotClient($this->bot);
    }

    /**
     * 群发文本消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendTextToFriends() {
        $res = $this->client->sendTextToFriends([
            'to_wxid' => [$this->wxidDj, $this->wxidJane],
            'msg' => "hi"
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 发送文本消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendText() {
        $res = $this->client->sendTextToFriend([
            'to_wxid' => $this->wxidDj,
            'msg' => "hi"
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 群发文件消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendFileToFriends() {
        $res = $this->client->sendFileToFriends([
            'to_wxid' => [$this->wxidDj, $this->wxidJane],
            'path' => "https://zmzgz.images.huihuiba.net/20220716/1/1_Cx2QCGvo_upgrade-1.5.0.zip"
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 发送文件消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendFile() {
        $res = $this->client->sendFileMsg([
            'to_wxid' => $this->wxidDj,
            'path' => "https://zmzgz.images.huihuiba.net/20220716/1/1_Cx2QCGvo_upgrade-1.5.0.zip"
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 发送图片消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendImgToFriends() {
        $res = $this->client->sendImgToFriends([
            'to_wxid' => [$this->wxidDj, $this->wxidJane],
            'path' => "https://zyx.images.huihuiba.net/0-5b84f6adbded5.png"
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 发送图片消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendImg() {
        $res = $this->client->sendImgToFriend([
            'to_wxid' => $this->wxidDj,
            'path' => "https://zyx.images.huihuiba.net/0-5b84f6adbded5.png"
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 群发视频消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendVideoToFriends() {
        $res = $this->client->sendVideoToFriends([
            'to_wxid' => [$this->wxidDj, $this->wxidJane],
            'path' => "https://zmzgz.images.huihuiba.net/20191113/1/1_uxTZBI17_Wildlife.mp4"
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 发送图片消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendVideo() {
        $res = $this->client->sendVideoMsg([
            'to_wxid' => $this->wxidDj,
            'path' => "https://zmzgz.images.huihuiba.net/20191113/1/1_uxTZBI17_Wildlife.mp4"
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }
}