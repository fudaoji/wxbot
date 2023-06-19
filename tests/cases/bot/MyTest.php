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
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use ky\WxBot\Driver\My;
use ky\WxBot\Driver\Mycom;

class MyTest extends BotTest
{
    private $botClient;
    private $botComClient;
    /**
     * @var My
     */
    private $botClient2;

    /**
     * 初始化
     * Author: Jason<dcq@kuryun.cn>
     */
    public function __construct() {
        parent::__construct();
        $this->botClient2 = new My(['app_key' => 'quNhWFeMrTcsjPnUIUtcpZHMHcAsEDRq', 'base_uri' => '124.222.4.168:8091']);
        $this->botClient = new My(['app_key' => 'quNhWFeMrTcsjPnUIUtcpZHMHcAsEDRq', 'base_uri' => '124.222.4.168:8091']);
        $this->botComClient = new Mycom(['app_key' => 'quNhWFeMrTcsjPnUIUtcpZHMHcAsEDRq', 'base_uri' => '124.223.70.93:8091']);
    }

    public function testGetSubscription(){
        $params = [
            'robot_wxid' => $this->robotJane,
        ];
        $res = $this->botClient->getSubscriptions($params);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    public function testGetContact(){
        $params = [
            'robot_wxid' => $this->robotJane,
        ];
        $res = $this->botClient->getContact($params);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 清空聊天记录
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testCleanChatHistory(){
        $params = [
            'robot_wxid' => $this->robotJane,
        ];
        $res = $this->botClient->cleanChatHistory($params);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    public function testGetMemberInfo(){
        $params = [
            'robot_wxid' => $this->robotFjq,
            'to_wxid' => 'gh_eb2ef06f2ba7'
        ];
        $res = $this->botClient->getMemberInfo($params);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    public function testFavoriteMsg(){
        $params = [
            'robot_wxid' => $this->robotJane,
            'msgid' => '5889647384385348114'
        ];
        $res = $this->botClient->favoritesMsg($params);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    public function testGetFavorites(){
        $params = [
            'robot_wxid' => $this->robotJane,
        ];
        $res = $this->botClient->getFavorites($params);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    public function testGetLoginCode(){
        $res = $this->botClient2->getLoginCode();
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 发送链接盆友圈
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendMomentsLink() {
        $res = $this->botClient->sendMomentsLink([
            'robot_wxid' => $this->robotJane,
            'content' => '发个链接',
            'title' => '标题',
            'img' => 'https://devhhb.images.huihuiba.net/1-6323eed2641e7.png',
            'url' => 'http://www.jdjl.com'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 发送视频盆友圈
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendMomentsVideo() {
        $res = $this->botClient->sendMomentsVideo([
            'robot_wxid' => $this->robotJane,
            'content' => '发个测试视频',
            'video' => 'https://devhhb.images.huihuiba.net/1-62df9dac60d2c.mp4'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 发送图片盆友圈
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendMomentsImg() {
        $res = $this->botClient->sendMomentsImg([
            'robot_wxid' => $this->robotJane,
            'content' => '发个测试图片',
            'img' => 'https://devhhb.images.huihuiba.net/1-6323eed2641e7.png'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 发送文本盆友圈
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendMomentsText() {
        $res = $this->botClient->sendMomentsText([
            'robot_wxid' => $this->robotJane,
            'content' => '发个测试文本'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 评论盆友圈
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testCommentMoments() {
        $res = $this->botClient->commentMoments([
            'robot_wxid' => $this->robotJane,
            'pyq_id' => '13952357871156203644',
            'content' => '[强]'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 点赞盆友圈
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testLikeMoments() {
        $res = $this->botClient->likeMoments([
            'robot_wxid' => $this->robotJane,
            'pyq_id' => '13952357871156203644',
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 获取好友盆友圈
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetFriendMoments() {
        $res = $this->botClient->getFriendMoments([
            'robot_wxid' => $this->robotJane,
            'to_wxid' => 'Emilyshuangren',
            'num' => 2
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 获取盆友圈
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetMoments() {
        $res = $this->botClient->getMoments([
            'robot_wxid' => $this->robotJane,
            'pyq_id' => '',
            'num' => 10
        ]);
        foreach ($res['data'] as $v){
            $xml = simplexml_load_string($v['object']);
            dump($xml);
            //dump((int) $xml->ContentObject->contentStyle);
        }
        //dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 建群
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testBuildingGroup() {
        $res = $this->botClient->buildingGroup([
            'robot_wxid' => $this->robotJane,
            'wxids' => [$this->robotJane, $this->wxidDj, $this->wxidYyp, $this->wxidYlp, $this->wxidDcq]
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 发送名片
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendCard() {
        $res = $this->botClient->sendCardToFriends([
            'robot_wxid' => $this->robotJane,
            'to_wxid' => $this->wxidDj,
            'content' => $this->robotJane
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 获取所有好友
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetFriends() {
        $res = $this->botClient2->getFriends([
            'robot_wxid' => $this->robotFjq,
            'refresh' => 1
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 获取所有机器人
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetRobotList() {
        $res = $this->botClient->getRobotList([]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 分享群链接邀请好友入群
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testInviteInGroupByLink() {
        $res = $this->botClient->inviteInGroupByLink([
            'robot_wxid' => $this->robotJane,
            'group_wxid' => $this->group51,
            'friend_wxid' => $this->wxidDj
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
            'v1' => 'wxid_xokb2ezu1p6t21',
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
     * 发送链接消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendMusicMsg() {
        $res = $this->botClient->sendLinkMsg([
            'robot_wxid' => $this->robotJane,
            'to_wxid' => $this->wxidDj,
            'xml' => ''
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 发送小程序消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendXmlMsg() {
        $res = $this->botClient->sendXml([
            'robot_wxid' => $this->robotJane,
            'to_wxid' => $this->wxidDj,
            'xml' => '<?xml version="1.0"?>
<msg>
	<appmsg appid="" sdkver="0">
		<title>750ml/瓶 无味杀虫气雾剂 速效杀灭苍蝇/蚊虫/蟑螂/跳蚤</title>
		<des />
		<type>33</type>
		<url>https://mp.weixin.qq.com/mp/waerrpage?appid=wxf856a911604ec0ad&amp;type=upgrade&amp;upgradetype=3#wechat_redirect</url>
		<appattach>
			<totallen>0</totallen>
			<attachid />
			<emoticonmd5></emoticonmd5>
			<fileext>jpg</fileext>
			<filekey>5f4e3199c1d12cb762dbba3324e8477e</filekey>
			<cdnthumburl>3057020100044b30490201000204cda5782702032df7950204f75d06af020464673be4042465623237363166342d663863352d346336382d386632652d3530356130663630363134370204011408030201000405004c543d00</cdnthumburl>
			<aeskey>ebd8c7d9672a0a22a10e8fddf01e925e</aeskey>
			<cdnthumbaeskey>ebd8c7d9672a0a22a10e8fddf01e925e</cdnthumbaeskey>
			<encryver>1</encryver>
			<cdnthumblength>58727</cdnthumblength>
			<cdnthumbheight>100</cdnthumbheight>
			<cdnthumbwidth>100</cdnthumbwidth>
		</appattach>
		<sourceusername>gh_a306e7f4ff8e@app</sourceusername>
		<sourcedisplayname>团婶婶</sourcedisplayname>
		<md5>4090f8394a67a1d5ed8c83a5685d48bb</md5>
		<recorditem><![CDATA[(null)]]></recorditem>
		<uploadpercent>95</uploadpercent>
		<weappinfo>
			<username><![CDATA[gh_a306e7f4ff8e@app]]></username>
			<appid><![CDATA[wxf856a911604ec0ad]]></appid>
			<type>2</type>
			<version>22</version>
			<weappiconurl><![CDATA[http://mmbiz.qpic.cn/mmbiz_png/YIQQgLwmsm87CbBjibpibMiccJ9N1ibEFSztS4HFrypmtoPJFZSHYDC04CXmWoTiaANVxWUGlV1QJkD6UkVaDicaABHQ/640?wx_fmt=png&wxfrom=200]]></weappiconurl>
			<pagepath><![CDATA[packagesD/pages/goods/sharePages.html?goodsId=819373728994562048&superId=809230199718809600]]></pagepath>
			<shareId><![CDATA[0_wxf856a911604ec0ad_a461cbbb547d4c2b4720ad79697ba726_1682430060_0]]></shareId>
			<appservicetype>0</appservicetype>
			<brandofficialflag>0</brandofficialflag>
			<showRelievedBuyFlag>0</showRelievedBuyFlag>
			<subType>0</subType>
			<isprivatemessage>0</isprivatemessage>
			<weapppagethumbrawurl><![CDATA[https://file.baogefang.cc/file/20230511/819382894920339456.png]]></weapppagethumbrawurl>
		</weappinfo>
	</appmsg>
	<fromusername>wxid_nd2jcy5200t712</fromusername>
	<scene>0</scene>
	<appinfo>
		<version>1</version>
		<appname></appname>
	</appinfo>
	<commenturl></commenturl>
</msg>',
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 发送链接消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendLinkMsg() {
        $res = $this->botClient->sendLinkMsg([
            'robot_wxid' => $this->robotJane,
            'to_wxid' => $this->wxidDj,
            'xml' => "<?xml version=\"1.0\"?><Title><![CDATA[标题]]></Title><Description><![CDATA[描述]]></Description><Url><![CDATA[http://www.baidu.com]]></Url>",
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
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
     * composer test --  --filter='MyTest::testSendGroupMsgAndAt'
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

    /**
     * 发送文件消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendFileMsg() {
        $res = $this->botClient2->sendFileMsg([
            'robot_wxid' => $this->robotJane,
            'to_wxid' => $this->wxidDj,
            'path' => 'https://devhhb.images.huihuiba.net/1-6258eebb2ad7f.pdf',
            'file_storage_path' => 'C:\ProgramData\Tencent\WeChat'
        ]);
        //dump(basename('https://devhhb.images.huihuiba.net/1-6258eebb2ad7f.pdf'));
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 发送文件消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendText() {
        $res = $this->botClient2->sendTextToFriends([
            'robot_wxid' => $this->robotJane,
            'to_wxid' => $this->wxidDj,
            'msg' => 'https://devhhb.images.huihuiba.net/1-6258eebb2ad7f.pdf',
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }
}