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
use ky\WxBot\Driver\Cat;

class CatTest extends BotTest
{
    private $botClient;

    /**
     * 初始化
     * Author: Jason<dcq@kuryun.cn>
     */
    public function __construct() {
        parent::__construct();
        $this->botClient = new Cat(['app_key' => 'quNhWFeMrTcsjPnUIUtcpZHMHcAsEDRq', 'base_uri' => '124.222.4.168:8090']);
    }

    /**
     * 获取朋友圈
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetMoments() {
        $res = $this->botClient->getMoments([
            'robot_wxid'  => $this->robotDj,
            'msg' => $this->robotJane
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * at好友
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendMusicMsg() {
        $res = $this->botClient->sendMusicLinkMsg([
            'robot_wxid'  => $this->robotDj,
            'to_wxid'  => $this->robotJane,
            'title'         => '泡沫',
            'desc' => 'qq'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * at好友
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendGroupMsgAndAt() {
        $res = $this->botClient->sendGroupMsgAndAt([
            'robot_wxid'  => $this->robotDj,
            'group_wxid'  => $this->group51,
            'member_wxid' => $this->robotJane,
            'msg'         => 'hi'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }
}