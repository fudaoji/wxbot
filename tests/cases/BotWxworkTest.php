<?php
/**
 * Created by PhpStorm.
 * Script Name: BotTest.php
 * Create: 2022/2/23 10:48
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace tests\cases;

use ky\WxBot\Driver\Wxwork;
use tests\TestCase;

class BotWxworkTest extends TestCase
{
    private $bot;
    private $robotFdj = 'wxid_xokb2ezu1p6t21';
    private $robotYxg = '1688856404324777';
    private $externalYxg = '7881301713149756';
    private $internalFdj = '1688854317341474';
    private $groupId = 'R:10951134140940878';
    private $externalFdj = '7881299942929761';

    /**
     * 初始化
     * Author: Jason<dcq@kuryun.cn>
     */
    public function __construct() {
        parent::__construct();
        $this->bot = new Wxwork(['app_key' => 'quNhWFeMrTcsjPnUIUtcpZHMHcAsEDRq', 'base_uri' => '124.222.4.168:8090']);
    }

    /**
     * 根据手机号加微信
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testAddFriendBySearch() {
        $res = $this->bot->addFriendBySearch([
            'robot_wxid' => $this->internalFdj,
            'content' => '15659827559',
            'msg' => 'hi, 我是Rocky'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 获取某个群成员信息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetGroupMemberInfo() {
        $res = $this->bot->getGroupMemberInfo([
            'robot_wxid' => $this->internalFdj,
            'group_wxid' => $this->groupId,
            'member_wxid' => '7881300941971174'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     *
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendGroupMsgAndAt() {
        $res = $this->bot->sendGroupMsgAndAt([
            'robot_wxid' => $this->internalFdj,
            'group_wxid' => $this->groupId,
            'member_wxid' => $this->robotFdj,
            'member_name' => "DJ",
            'msg' => 'cool'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 转发信息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testForwardMsg() {
        $res = $this->bot->forwardMsg([
            'robot_wxid' => $this->internalFdj,
            'to_wxid' => $this->groupId,
            'msgid' => '1022188'
        ]);
        dump($res);
        $this->assertSame(1, $res['code']);
    }

    /**
     * 获取群成员
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetGroupMember() {
        $res = $this->bot->getGroupMember([
            'robot_wxid' => $this->robotYxg,
            'group_wxid' => $this->groupId,
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
     * 发送文本消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendShareLink() {
        $res = $this->bot->sendShareLinkMsg([
            'robot_wxid' => $this->internalFdj,
            'to_wxid' => $this->externalFdj,
            'url' => 'https://wx74161fcecb84d46c.wx.ckjr001.com/kpv2p/6m5oe8/?1649472627421=#/?refereeId=b8ln59l',
            'image_url' => 'https://my.chinaz.com/avatar/user.png',
            'title' => '6-12岁儿童家庭都在用的【每日里】学习平台',
            'desc' => '你的每日里，学习更轻松、生活更有味！',
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
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
            'to_wxid' => $this->externalFdj,
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
            'robot_wxid' => $this->robotYxg
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
            'robot_wxid' => $this->robotYxg
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