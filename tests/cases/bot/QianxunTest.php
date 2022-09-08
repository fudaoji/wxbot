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
use ky\WxBot\Driver\Qianxun;

class QianxunTest extends BotTest
{
    private $botClient;
    private $botComClient;

    /**
     * 初始化
     * Author: Jason<dcq@kuryun.cn>
     */
    public function __construct() {
        parent::__construct();
        $this->botClient = new Qianxun(['app_key' => 'quNhWFeMrTcsjPnUIUtcpZHMHcAsEDRq', 'base_uri' => '124.223.70.93:8091']);
        //$this->botComClient = new Mycom(['app_key' => 'quNhWFeMrTcsjPnUIUtcpZHMHcAsEDRq', 'base_uri' => '124.223.70.93:8091']);
    }

    /**
     * 搜索好友
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testAddFriendByWxid() {
        $res = $this->botClient->addFriendByWxid([
            'robot_wxid' => $this->robotJane,
            'wxid' => 'wxid_fdbkuts97niu22',
            'msg' => '我是Jane',
            'scene' => Bot::SCENE_GROUP
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 搜索好友
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testAddFriendBySearch() {
        $res = $this->botClient->addFriendBySearch([
            'robot_wxid' => $this->robotJane,
            'v1' => 'v3_020b3826fd030100000000004d8c0b57e3c2a2000000501ea9a3dba12f95f6b60a0536a1adb64374ee1191dceea81d665720e900301117f5c06465de783e371970aae6ec149e6a6b3a6e293c0f7cac14407864111992efef3daed6468fb51b0186e7b6@stranger',
            'msg' => '我是Jane',
            'scene' => Bot::SCENE_WXNUM,
            'type' => 1
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 搜索好友
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSearchAccount() {
        $res = $this->botClient->searchAccount([
            'robot_wxid' => $this->robotJane,
            'content' => 'doogiefu'
            //'content' => 'i75123888'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 发送分享连接消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendShareLink() {
        $res = $this->botClient->sendShareLinkToFriends([
            'robot_wxid' => $this->robotJane,
            'to_wxid' => $this->wxidDj,
            'url' => 'https://wx74161fcecb84d46c.wx.ckjr001.com/kpv2p/6m5oe8/?1649472627421=#/?refereeId=b8ln59l',
            'image_url' => 'https://my.chinaz.com/avatar/user.png',
            'title' => 'This is title',
            'desc' => 'This is desc',
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 获取对象信息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetMemberInfo() {
        $res = $this->botClient->getMemberInfo([
            'robot_wxid' => $this->robotJane,
            'to_wxid' => $this->wxidDj
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 发送文件
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendFile() {
        $path_base = "C:\\Users\\Administrator\\Documents\\WeChat Files\\wxid_a98qqf9m4bny22\\FileStorage\\File\\2022-09\\";
        $res = $this->botClient->sendFileMsg([
            'robot_wxid' => $this->robotJane,
            'to_wxid' => $this->wxidDj,
            'path' => $path_base . '新建 XLS 工作表.xls'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 发送图片
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendImg() {
        $res = $this->botClient->sendImgToFriends([
            'robot_wxid' => $this->robotJane,
            'to_wxid' => $this->wxidDj,
            'path' => 'https://cdn.apifox.cn/app/project-icon/custom/20220714/405897b6-3097-4f59-82a9-241915b8b538.png'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 拉取群成员列表
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetGroupMembers() {
        $res = $this->botClient->getGroupMembers([
            'robot_wxid' => $this->robotJane,
            'group_wxid' => '20668619112@chatroom'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 拉取群聊列表
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetGroups() {
        $res = $this->botClient->getGroups([
            'robot_wxid' => $this->robotJane,
            'is_refresh' => 1
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 拉取好友列表
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetFriends() {
        $res = $this->botClient->getFriends([
            'robot_wxid' => $this->robotJane,
            'is_refresh' => 1
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 发送文本
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendText() {
        $res = $this->botClient->sendTextToFriend([
            'robot_wxid' => $this->robotJane,
            'to_wxid' => $this->wxidDj,
            'msg' => 'hi'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 获取机器人状态信息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetRobotInfo() {
        $res = $this->botClient->getRobotInfo([
            'robot_wxid' => $this->robotJane
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * composer test --  --filter='QianxunTest::testGetRobotList'
     * 获取机器人列表
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetRobotList() {
        $res = $this->botClient->getRobotList([
            'robot_wxid' => $this->robotJane
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }
}