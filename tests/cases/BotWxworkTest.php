<?php
/**
 * Created by PhpStorm.
 * Script Name: BotTest.php
 * Create: 2022/2/23 10:48
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace tests\cases;

use ky\Bot\Wxwork;
use tests\TestCase;

class BotWxworkTest extends TestCase
{
    private $bot;
    private $robotFdj = 'wxid_xokb2ezu1p6t21';
    private $qyRobotFdj = '1688854317341474';
    private $robotYxg = '1688856404324777';
    private $externalYxg = '7881301713149756';
    private $internalFdj = '1688854317341474';
    private $groupId = 'R:10840821540390231';
    private $cpGroupId='R:10951134140940878';

    /**
     * 初始化
     * Author: Jason<dcq@kuryun.cn>
     */
    public function __construct() {
        parent::__construct();
        $this->bot = new Wxwork(['app_key' => 'quNhWFeMrTcsjPnUIUtcpZHMHcAsEDRq', 'base_uri' => '124.222.4.168:8090']);
    }

    /**
     * 获取群成员
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetGroupMember() {
        $res = $this->bot->getGroupMember([
            'robot_wxid' => $this->qyRobotFdj,
            'group_wxid' => $this->cpGroupId,
        ]);
        dump($res);
        $this->assertSame(1, $res['code']);
    }

    /**
     * 获取群列表
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetGroups() {
        $res = $this->bot->getGroups([
            'robot_wxid' => $this->robotYxg
        ]);
        dump($res);
        $this->assertSame(1, $res['code']);
    }

    /**
     * 发送图片消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendImg() {
        $res = $this->bot->sendImgToFriends([
            'robot_wxid' => $this->robotYxg,
            'to_wxid' => $this->externalYxg,
            'path' => "https://www.liangcang.cc/static/img/qrcode.c2f2668b.png"
        ]);
        dump($res);
        $this->assertSame(1, $res['code']);
    }

    /**
     * 发送文本消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendText() {
        $res = $this->bot->sendTextToFriends([
            'robot_wxid' => $this->robotYxg,
            'to_wxid' => $this->internalFdj,
            'msg' => "来自企业微信的消息"
        ]);
        dump($res);
        $this->assertSame(1, $res['code']);
    }

    /**
     * 获取内部联系人列表
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetInternalFriendlist() {
        $res = $this->bot->getInternalFriendlist([
            'robot_wxid' => $this->qyRobotFdj
        ]);
        dump($res);
        $this->assertSame(1, $res['code']);
    }

    /**
     * 获取外部联系人列表
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetExternalFriendlist() {
        $res = $this->bot->getExternalFriendlist([
            'robot_wxid' => $this->qyRobotFdj
        ]);
        dump($res);
        $this->assertSame(1, $res['code']);
    }

    /**
     * 获取外部联系人列表
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetFriends() {
        $res = $this->bot->getFriends([
            'robot_wxid' => $this->robotYxg
        ]);
        dump($res);
        $this->assertSame(1, $res['code']);
    }
}