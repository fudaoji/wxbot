<?php
/**
 * Created by PhpStorm.
 * Script Name: BotTest.php
 * Create: 2022/2/23 10:48
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace tests\cases;

use ky\Bot\Vlw;
use tests\TestCase;

class BotTest extends TestCase
{
    private $bot;
    private $robotWxid = 'wxid_xokb2ezu1p6t21';
    private $wxidSj = 'wxid_u5201kiugya612';
    private $wxidJane = 'wxid_a98qqf9m4bny22';

    /**
     * 初始化
     * Author: Jason<dcq@kuryun.cn>
     */
    public function __construct() {
        parent::__construct();
        $this->bot = new Vlw(['app_key' => '123456', 'base_uri' => '124.222.4.168:8090']);
    }

    /**
     * 邀请好友入群
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testInviteInGroup() {
        $res = $this->bot->inviteInGroup([
            'robot_wxid' => $this->robotWxid,
            'group_wxid' => '17890757671@chatroom',
            'friend_wxid' => 'weiwei562608'
        ]);
        dump($res);
        $this->assertSame(0, $res['Code']);
    }

    /**
     * 同意好友请求
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testAgreeFriendVerify() {
        $res = $this->bot->agreeFriendVerify([
            'robot_wxid' => $this->robotWxid,
            'v1' => 'v3_020b3826fd03010000000000b8fc0397a54ddf000000501ea9a3dba12f95f6b60a0536a1adb692b88b23075c5a65b99d03092f43947fb61057f3289aed4e8ab08dda54d27596629e0c72938fdbbb74a448671b42f66f7451ea6820b2cdd3663ddac7@stranger',
            'v2' => 'v4_000b708f0b0400000100000000006cdd0d34b4a7b831ddb792e615621000000050ded0b020927e3c97896a09d47e6e9ea1fb041269b590163ad9f4390f72cc01e55d952d776997714a1a37049429a07bde86f51c7df4257bd14f2be8925d4d6fe51b5e4fe4d9771d209422bf371354b71df48243264074b87c0435364286ed812e261bcf5249037311d4548d66edfa172a1ab8df39ecb2bd@stranger',
            'type' => 6
        ]);
        dump($res);
        $this->assertSame(0, $res['Code']);
    }

    /**
     * 删除好友
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testDeleteFriend() {
        $res = $this->bot->deleteFriend([
            'robot_wxid' => $this->robotWxid,
            'to_wxid' => $this->wxidJane
        ]);
        dump($res);
        $this->assertSame(0, $res['Code']);
    }

    /**
     * 发送文件消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendFileMsg() {
        $res = $this->bot->sendFileMsg([
            'robot_wxid' => $this->robotWxid,
            'to_wxid' => $this->wxidJane,
            'path' => "C:\DevTools\VLW\wxid_xokb2ezu1p6t21\00a02a9896674cf3a912dcb8c76a4347.gif"
        ]);
        dump($res);
        $this->assertSame(0, $res['Code']);
    }

    /**
     * 下发文件并发送
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testDownloadFile() {
        $res = $this->bot->downloadFile([
            'robot_wxid' => $this->robotWxid,
            'to_wxid' => $this->wxidJane,
            "url" => "https://www.runoob.com/try/demo_source/horse.mp3",
            'savePath' => "C:\DevTools\VLW\wxid_xokb2ezu1p6t21\horse.mp3",
            'useApi' => 'SendFileMsg'
        ]);
        dump($res);
        $this->assertSame(0, $res['Code']);
    }

    /**
     * 发送mp3音乐消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendMusicLinkMsg() {
        $res = $this->bot->sendMusicLinkMsg([
            'robot_wxid' => $this->robotWxid,
            'to_wxid' => $this->wxidJane,
            'title' => "标题",
            "url" => "https://www.runoob.com/try/demo_source/horse.mp3",
            'desc' => '描述',
            'dataurl' => 'https://www.runoob.com/try/demo_source/horse.mp3',
            'thumburl' => 'http://y.gtimg.cn/music/photo_new/T002R150x150M000002nIts51DQraX.jpg'
        ]);
        dump($res);
        $this->assertSame(0, $res['Code']);
    }

    /**
     * 发送普通链接消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendShareLinkMsg() {
        $res = $this->bot->sendShareLinkMsg([
            'robot_wxid' => $this->robotWxid,
            'to_wxid' => 'wxid_u5201kiugya612',
            'title' => "标题",
            "url" => "http://www.baidu.com",
            'desc' => '描述',
            'image_url' => 'http://img14.360buyimg.com/imgzone/jfs/t1/105427/11/20091/302754/61e1429aE6df0b06d/9a6307cacf90904f.jpg'
        ]);
        dump($res);
        $this->assertSame(0, $res['Code']);
    }

    /**
     * 发送链接消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendLinkMsg() {
        $res = $this->bot->sendLinkMsg([
            'robot_wxid' => $this->robotWxid,
            'to_wxid' => 'wxid_u5201kiugya612',
            'xml' => "<xml><Title><![CDATA[标题]]></Title><Description><![CDATA[描述]]></Description><Url><![CDATA[http://www.baidu.com]]></Url></xml>",
        ]);
        dump($res);
        $this->assertSame(0, $res['Code']);
    }

    /**
     * 发送卡片消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendCardMsg() {
        $res = $this->bot->sendCardMsg([
            'robot_wxid' => $this->robotWxid,
            'content' => 'wxid_n10wl9bz8qjx21',
            'to_wxid' => 'wxid_u5201kiugya612',
        ]);
        dump($res);
        $this->assertSame(0, $res['Code']);
    }

    /**
     * 群发消息并at某人
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendGroupMsgAndAt() {
        $res = $this->bot->sendGroupMsgAndAt([
            'robot_wxid' => $this->robotWxid,
            'group_wxid' => '21361397515@chatroom',
            'member_wxid' => 'wxid_u5201kiugya612',
            //'member_name' => '一一',
            'msg' => 'test'
        ]);
        dump($res);
        $this->assertSame(1, 1);
    }

    /**
     * 搜索账号
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSearchAccount() {
        $res = $this->bot->searchAccount([
            'robot_wxid' => $this->robotWxid,
            'content' => '18659253156'
        ]);
        dump($res);
        $this->assertSame(1, 1);
    }

    /**
     * 发送图片消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendImg() {
        $res = $this->bot->sendImgToFriends([
            'robot_wxid' => $this->robotWxid,
            'to_wxid' => $this->wxidJane,
            'path' => "C:\\DevTools\\VLW_Pro120\\Data\\wxid_xokb2ezu1p6t21\\1173273030.png"
        ]);
        dump($res);
        $this->assertSame(0, $res['code']);
    }
}