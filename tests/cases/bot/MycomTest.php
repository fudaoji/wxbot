<?php
/**
 * Created by PhpStorm.
 * Script Name: MycomTest.php
 * Create: 2022/2/23 10:48
 * Description: 我的企业微信
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace tests\cases;

use ky\WxBot\Driver\Mycom;
use tests\cases\bot\BotTest;

class MycomTest extends BotTest
{
    /**
     * @var Mycom
     */
    private $botClient;
    private $internalFdj = '1688854317341474';
    private $externalFdj = '7881299942929761';

    /**
     * 初始化
     * Author: Jason<dcq@kuryun.cn>
     */
    public function __construct() {
        parent::__construct();
        $this->botClient = new Mycom(['app_key' => 'quNhWFeMrTcsjPnUIUtcpZHMHcAsEDRq', 'base_uri' => '124.222.4.168:8091']);
    }

    /**
     * 获取登录二维码
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetLoginCode() {
        $res = $this->botClient->getLoginCode();
        dump($res);
        $this->assertSame(1, $res['code']);
    }

    /**
     * 根据手机号加微信
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testAddFriendBySearch() {
        $res = $this->botClient->addFriendBySearch([
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
        $res = $this->botClient->getGroupMemberInfo([
            'robot_wxid' => $this->internalFdj,
            'group_wxid' => $this->group51,
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
        $res = $this->botClient->sendGroupMsgAndAt([
            'robot_wxid' => $this->internalFdj,
            'group_wxid' => $this->group51,
            'member_wxid' => $this->wxidDj,
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
        $res = $this->botClient->forwardMsg([
            'robot_wxid' => $this->internalFdj,
            'to_wxid' => $this->group51,
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
        $res = $this->botClient->getGroupMember([
            'robot_wxid' => $this->robotComDj,
            'group_wxid' => $this->group51,
        ]);
        dump($res);
        $this->assertSame(1, $res['code']);
    }

    /**
     * 获取群列表
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetGroups() {
        $res = $this->botClient->getGroups([
            'robot_wxid' => $this->robotComDj
        ]);
        dump($res);
        $this->assertSame(1, $res['code']);
    }

    /**
     * 发送文本消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendShareLink() {
        $res = $this->botClient->sendShareLinkMsg([
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
        $res = $this->botClient->sendImgToFriends([
            'robot_wxid' => $this->robotComDj,
            'to_wxid' => $this->externalFdj,
            'path' => "https://www.liangcang.cc/static/img/qrcode.c2f2668b.png"
        ]);
        dump($res);
        $this->assertSame(1, $res['code']);
    }

    /**
     * 获取外部联系人列表
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetFriends() {
        $res = $this->botClient->getFriends([
            'robot_wxid' => $this->robotComDj
        ]);
        dump($res);
        $this->assertSame(1, $res['code']);
    }
}