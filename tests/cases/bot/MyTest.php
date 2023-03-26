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

    public function testGetMemberInfo(){
        $params = [
            'robot_wxid' => $this->robotFjq,
            'to_wxid' => $this->wxidDj
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
     * 发送xml盆友圈
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendMomentsXml() {
        $list = $this->botClient->getFriendMoments([
            'robot_wxid' => $this->robotJane,
            'to_wxid' => 'Emilyshuangren',
            'num' => 1
        ]);
        $res = $this->botClient->sendMomentsXml([
            'robot_wxid' => $this->robotJane,
            'xml' => $list['data'][0]['object']
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
            'to_wxid' => 'wxid_xokb2ezu1p6t21',
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
            'wxids' => [$this->wxidDj, $this->wxidYyp, $this->wxidYlp, $this->wxidDcq]
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
		<title>吃喝玩乐 尽在美团</title>
		<des />
		<username />
		<action>view</action>
		<type>33</type>
		<showtype>0</showtype>
		<content />
		<url>https://mp.weixin.qq.com/mp/waerrpage?appid=wxde8ac0a21135c07d&amp;type=upgrade&amp;upgradetype=3#wechat_redirect</url>
		<lowurl />
		<forwardflag>0</forwardflag>
		<dataurl />
		<lowdataurl />
		<contentattr>0</contentattr>
		<appattach>
			<attachid />
			<cdnthumburl>3057020100044b30490201000204353f59cc02032f5dc90204d129e17c020463eb3c0d042462646434363337382d326535382d343561622d623566652d3232306266343533323362380204011800030201000405004c543d00</cdnthumburl>
			<cdnthumbmd5>f3baf6a8a842594d7b7422d044f5843d</cdnthumbmd5>
			<cdnthumblength>46256</cdnthumblength>
			<cdnthumbheight>576</cdnthumbheight>
			<cdnthumbwidth>720</cdnthumbwidth>
			<cdnthumbaeskey>8cd7ce5f2832e93e834611ce7186b6ae</cdnthumbaeskey>
			<aeskey>8cd7ce5f2832e93e834611ce7186b6ae</aeskey>
			<encryver>1</encryver>
			<fileext />
			<islargefilemsg>0</islargefilemsg>
		</appattach>
		<extinfo />
		<androidsource>0</androidsource>
		<sourceusername>gh_870576f3c6f9@app</sourceusername>
		<sourcedisplayname>美团丨外卖美食买菜酒店电影购物</sourcedisplayname>
		<commenturl />
		<thumburl />
		<mediatagname />
		<messageaction><![CDATA[]]></messageaction>
		<messageext><![CDATA[]]></messageext>
		<emoticongift>
			<packageflag>0</packageflag>
			<packageid />
		</emoticongift>
		<emoticonshared>
			<packageflag>0</packageflag>
			<packageid />
		</emoticonshared>
		<designershared>
			<designeruin>0</designeruin>
			<designername>null</designername>
			<designerrediretcturl>null</designerrediretcturl>
		</designershared>
		<emotionpageshared>
			<tid>0</tid>
			<title>null</title>
			<desc>null</desc>
			<iconUrl>null</iconUrl>
			<secondUrl>null</secondUrl>
			<pageType>0</pageType>
			<setKey>null</setKey>
		</emotionpageshared>
		<webviewshared>
			<shareUrlOriginal />
			<shareUrlOpen />
			<jsAppId />
			<publisherId>wxapp_wxde8ac0a21135c07dindex/pages/mt/mt.html</publisherId>
		</webviewshared>
		<template_id />
		<md5>f3baf6a8a842594d7b7422d044f5843d</md5>
		<websearch>
			<rec_category>0</rec_category>
			<channelId>0</channelId>
		</websearch>
		<weappinfo>
			<pagepath><![CDATA[index/pages/mt/mt.html]]></pagepath>
			<username>gh_870576f3c6f9@app</username>
			<appid>wxde8ac0a21135c07d</appid>
			<version>1114</version>
			<type>2</type>
			<weappiconurl><![CDATA[http://wx.qlogo.cn/mmhead/Q3auHgzwzM5IfaiappYJdWCApgZnQUtjqDLBOB2U2l4nsfASxgxkubQ/96]]></weappiconurl>
			<shareId><![CDATA[1_wxde8ac0a21135c07d_48e268137e9ffa57796ac0ed54820cd0_1676360717_0]]></shareId>
			<appservicetype>0</appservicetype>
			<secflagforsinglepagemode>0</secflagforsinglepagemode>
			<videopageinfo>
				<thumbwidth>720</thumbwidth>
				<thumbheight>576</thumbheight>
				<fromopensdk>0</fromopensdk>
			</videopageinfo>
			<showRelievedBuyFlag>538</showRelievedBuyFlag>
		</weappinfo>
		<statextstr />
	</appmsg>
	<fromusername>wxid_xokb2ezu1p6t21</fromusername>
	<scene>0</scene>
	<appinfo>
		<version>1</version>
		<appname />
	</appinfo>
	<commenturl />
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
            'robot_wxid' => $this->robotFjq,
            'to_wxid' => $this->wxidDj,
            'path' => 'https://devhhb.images.huihuiba.net/1-6258eebb2ad7f.pdf'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }
}