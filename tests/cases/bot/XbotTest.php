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
    private $clientId = 11;


    public function __construct() {
        parent::__construct();
        $this->botClient = new Xbot(['app_key' => 'quNhWFeMrTcsjPnUIUtcpZHMHcAsEDRq', 'base_uri' => '124.222.4.168:8092']);
    }

    /**
     * 退出微信程序
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testExit() {
        $res = $this->botClient->exit([
            'uuid' => $this->clientId
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 转发信息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testForwardMsg() {
        $res = $this->botClient->forwardMsg([
            'uuid' => $this->clientId,
            'to_wxid' => $this->group51,
            'msgid' => '8596650352401251863'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 发送普通链接消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendShareLinkMsg() {
        $res = $this->botClient->sendShareLinkToFriends([
            'uuid' => $this->clientId,
            'to_wxid' => [$this->wxidDj, $this->group51],
            'title' => "标题",
            "url" => "http://www.baidu.com",
            'desc' => '描述',
            'image_url' => 'http://img14.360buyimg.com/imgzone/jfs/t1/105427/11/20091/302754/61e1429aE6df0b06d/9a6307cacf90904f.jpg'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 发送图片消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendImg() {
        $res = $this->botClient->sendImgToFriends([
            'uuid' => $this->clientId,
            'to_wxid' => $this->wxidDj,
            'path' => "https://devhhb.images.huihuiba.net/1-637ae5c99e334.png"
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 发送文本消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendText() {
        $res = $this->botClient->sendTextToFriends([
            'uuid' => $this->clientId,
            'to_wxid' => [$this->wxidDj, $this->group51],
            'msg' => 'hi'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 获取群成员列表
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetGroupMembers() {
        $res = $this->botClient->getGroupMembers([
            'uuid' => $this->clientId,
            'group_wxid' => $this->group51
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 获取群组列表
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSetGroupName() {
        $res = $this->botClient->setGroupName([
            'uuid' => $this->clientId,
            'group_wxid' => $this->group51,
            'group_name' => '群51'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
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