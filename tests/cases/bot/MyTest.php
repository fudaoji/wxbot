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
     * 发送链接消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendMusicMsg() {
        $res = $this->botClient->sendLinkMsg([
            'robot_wxid' => $this->robotJane,
            'to_wxid' => $this->wxidDj,
            'xml' => '<msg>
	<appmsg appid="wx5aa333606550dfd5" sdkver="0">
		<title>夏天的风</title>
		<des>刘瑞琦</des>
		<action>view</action>
		<type>3</type>
		<url>https://i.y.qq.com/v8/playsong.html?platform=11&amp;appshare=android_qq&amp;appversion=11080508&amp;hosteuin=7eC5NKCzNKCA&amp;songmid=001nZ7YK4cYAvu&amp;type=0&amp;appsongtype=1&amp;_wv=1&amp;source=weixin&amp;ADTAG=wxfshare</url>
		<appattach>
			<cdnthumburl>http://y.gtimg.cn/music/photo_new/T002R500x500M000004Hv0812BvAy9_2.jpg</cdnthumburl>
		</appattach>
		<dataurl>http://c6.y.qq.com/rsc/fcgi-bin/fcg_pyq_play.fcg?songid=&amp;songmid=001nZ7YK4cYAvu&amp;songtype=1&amp;fromtag=46&amp;uin=461960962&amp;code=3B92B</dataurl>
	</appmsg>
	<appinfo>
		<version>53</version>
		<appname>QQ音乐</appname>
	</appinfo>
</msg>'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 发送小程序消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendXmlMsg() {
        $res = $this->botClient->sendLinkMsg([
            'robot_wxid' => $this->robotJane,
            'to_wxid' => $this->wxidDj,
            'xml' => "<?xml version=\"1.0\"?>
<msg>
	<appmsg appid=\"\" sdkver=\"0\">
		<title>京东推广必备，跑单还有补贴！</title>
		<des />
		<username />
		<action>view</action>
		<type>33</type>
		<showtype>0</showtype>
		<content />
		<url>https://mp.weixin.qq.com/mp/waerrpage?appid=wx285a6fe4cfc80626&amp;type=upgrade&amp;upgradetype=3#wechat_redirect</url>
		<lowurl />
		<forwardflag>0</forwardflag>
		<dataurl />
		<lowdataurl />
		<contentattr>0</contentattr>
		<streamvideo>
			<streamvideourl />
			<streamvideototaltime>0</streamvideototaltime>
			<streamvideotitle />
			<streamvideowording />
			<streamvideoweburl />
			<streamvideothumburl />
			<streamvideoaduxinfo />
			<streamvideopublishid />
		</streamvideo>
		<canvasPageItem>
			<canvasPageXml><![CDATA[]]></canvasPageXml>
		</canvasPageItem>
		<appattach>
			<attachid />
			<cdnthumburl>3057020100044b30490201000204353f59cc02032f578a020424a4a274020462e780e8042461383536373333372d363538362d343738382d616131652d3662623238333532363361660204011800030201000405004c52ae00</cdnthumburl>
			<cdnthumbmd5>b87e733e574e64e9b8e187675819fe7c</cdnthumbmd5>
			<cdnthumblength>38984</cdnthumblength>
			<cdnthumbheight>576</cdnthumbheight>
			<cdnthumbwidth>720</cdnthumbwidth>
			<cdnthumbaeskey>524263f7db37f3016be48bdeb956873f</cdnthumbaeskey>
			<aeskey>524263f7db37f3016be48bdeb956873f</aeskey>
			<encryver>1</encryver>
			<fileext />
			<islargefilemsg>0</islargefilemsg>
		</appattach>
		<extinfo />
		<androidsource>0</androidsource>
		<sourceusername>gh_141150d6bfb2@app</sourceusername>
		<sourcedisplayname>京推选</sourcedisplayname>
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
		</emotionpageshared>
		<webviewshared>
			<shareUrlOriginal />
			<shareUrlOpen />
			<jsAppId />
			<publisherId>wxapp_wx285a6fe4cfc80626pages/home/index/index.html</publisherId>
		</webviewshared>
		<template_id />
		<md5>b87e733e574e64e9b8e187675819fe7c</md5>
		<weappinfo>
			<pagepath><![CDATA[pages/home/index/index.html]]></pagepath>
			<username>gh_141150d6bfb2@app</username>
			<appid>wx285a6fe4cfc80626</appid>
			<version>5</version>
			<type>2</type>
			<weappiconurl><![CDATA[http://wx.qlogo.cn/mmhead/Q3auHgzwzM6c7S0VzvEoPecYJOWYHthGnum8KmuUQl8fSw6sHYpwuw/96]]></weappiconurl>
			<weapppagethumbrawurl><![CDATA[https://cdn.jingtuixuan.com/mini/icon/share-img.png]]></weapppagethumbrawurl>
			<shareId><![CDATA[1_wx285a6fe4cfc80626_48e268137e9ffa57796ac0ed54820cd0_1659338984_0]]></shareId>
			<appservicetype>0</appservicetype>
			<secflagforsinglepagemode>0</secflagforsinglepagemode>
			<videopageinfo>
				<thumbwidth>720</thumbwidth>
				<thumbheight>576</thumbheight>
				<fromopensdk>0</fromopensdk>
			</videopageinfo>
		</weappinfo>
		<statextstr />
		<musicShareItem>
			<musicDuration>0</musicDuration>
		</musicShareItem>
		<finderLiveProductShare>
			<finderLiveID />
			<finderUsername />
			<finderObjectID />
			<finderNonceID />
			<liveStatus />
			<appId />
			<pagePath />
			<productId />
			<coverUrl />
			<productTitle />
			<marketPrice><![CDATA[0]]></marketPrice>
			<sellingPrice><![CDATA[0]]></sellingPrice>
			<platformHeadImg />
			<platformName />
			<shopWindowId />
			<flashSalePrice><![CDATA[0]]></flashSalePrice>
			<flashSaleEndTime><![CDATA[0]]></flashSaleEndTime>
			<ecSource />
			<sellingPriceWording />
		</finderLiveProductShare>
		<finderShopWindowShare>
			<finderUsername />
			<avatar />
			<nickname />
			<commodityInStockCount />
			<appId />
			<path />
			<appUsername />
			<query />
			<liteAppId />
			<liteAppPath />
			<liteAppQuery />
		</finderShopWindowShare>
		<findernamecard>
			<username />
			<avatar><![CDATA[]]></avatar>
			<nickname />
			<auth_job />
			<auth_icon>0</auth_icon>
			<auth_icon_url />
		</findernamecard>
		<finderGuarantee>
			<scene><![CDATA[0]]></scene>
		</finderGuarantee>
		<directshare>0</directshare>
		<gamecenter>
			<namecard>
				<iconUrl />
				<name />
				<desc />
				<tail />
				<jumpUrl />
			</namecard>
		</gamecenter>
		<patMsg>
			<chatUser />
			<records>
				<recordNum>0</recordNum>
			</records>
		</patMsg>
		<secretmsg>
			<issecretmsg>0</issecretmsg>
		</secretmsg>
		<referfromscene>0</referfromscene>
		<websearch>
			<rec_category>0</rec_category>
			<channelId>0</channelId>
		</websearch>
	</appmsg>
	<fromusername>wxid_xokb2ezu1p6t21</fromusername>
	<scene>0</scene>
	<appinfo>
		<version>1</version>
		<appname />
	</appinfo>
	<commenturl />
</msg>
",
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