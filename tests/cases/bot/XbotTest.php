<?php
/**
 * Created by PhpStorm.
 * Script Name: MyTest.php
 * Create: 7/29/22 10:54 AM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace tests\cases\bot;

use app\constants\Bot;
use ky\WxBot\Driver\Xbot;

class XbotTest extends BotTest
{
    private $botClient;
    private $clientId = 9;


    public function __construct() {
        parent::__construct();
        $this->botClient = new Xbot(['app_key' => 'quNhWFeMrTcsjPnUIUtcpZHMHcAsEDRq', 'base_uri' => '124.222.4.168:8092']);
    }

    /**
     * 获取群组列表
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetGroups() {
        $res = $this->botClient->getGroups(['data' => ['uuid' => $this->clientId]]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 获取好友列表
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetFriends() {
        $res = $this->botClient->getFriends(['data' => ['uuid' => $this->clientId]]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 获取微信信息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetRobotInfo() {
        $res = $this->botClient->getRobotInfo(['client_id' => 9]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 登录码
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetLogin() {
        $res = $this->botClient->getLoginCode(['is_sync' => 1, 'client_id' => 9]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 注入微信
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testInject() {
        $res = $this->botClient->injectWechat(['is_sync' => 1]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }
}