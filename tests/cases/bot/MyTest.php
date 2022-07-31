<?php
/**
 * Created by PhpStorm.
 * Script Name: MyTest.php
 * Create: 7/29/22 10:54 AM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace tests\cases\bot;


use ky\WxBot\Driver\My;
use ky\WxBot\Driver\Mycom;

class MyTest extends BotTest
{
    private $botClient;
    private $botComClient;

    /**
     * 初始化
     * Author: Jason<dcq@kuryun.cn>
     */
    public function __construct() {
        parent::__construct();
        $this->botClient = new My(['app_key' => 'quNhWFeMrTcsjPnUIUtcpZHMHcAsEDRq', 'base_uri' => '124.223.70.93:8091']);
        $this->botComClient = new Mycom(['app_key' => 'quNhWFeMrTcsjPnUIUtcpZHMHcAsEDRq', 'base_uri' => '124.223.70.93:8091']);
    }

    /**
     * at群员
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendMsgAtAll() {
        $res = $this->botClient->sendMsgAtAll([
            'robot_wxid' => $this->robotJane,
            'group_wxid' => $this->group51,
            'msg' => 'test'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 群发消息并at某人
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendGroupMsgAndAtCom() {
        $res = $this->botComClient->sendGroupMsgAndAt([
            'robot_wxid' => $this->robotComDj,
            'group_wxid' => $this->group51,
            'member_wxid' => $this->wxidDj,
            'msg' => 'test'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 群发消息并at某人
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendGroupMsgAndAt() {
        $res = $this->botClient->sendGroupMsgAndAt([
            'robot_wxid' => $this->robotJane,
            'group_wxid' => $this->group51,
            'member_wxid' => $this->wxidDj,
            //'member_name' => 'DJ',
            'msg' => 'test'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }
}