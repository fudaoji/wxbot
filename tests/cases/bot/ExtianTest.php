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
use ky\WxBot\Driver\Extian;

class ExtianTest extends BotTest
{
    private $botClient;
    private $clientId = 4608;

    private $baseUri = 'http://124.222.4.168:8203';
    private $key = '2B95B3BF370C8C09209E9909B1B6315737DABA14';

    public function __construct() {
        parent::__construct();
        $this->botClient = new Extian(['app_key' => $this->key, 'base_uri' => $this->baseUri]);
    }

    /**
     * 保存文件
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSaveFile() {
        $res = $this->botClient->saveFile([
            'uuid' => $this->clientId,
            'data' => "https://devhhb.images.huihuiba.net/1-62df9dac60d2c.mp4",
            'path' => '1-62df9dac60d2c.mp4'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 获取信息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetMsg() {
        $res = $this->botClient->getMsg([
            'uuid' => $this->clientId,
            'msgid' => '8131007268833536822'
        ]);
        dump($res);
        if(!empty($res['data'][0]['ext2'])){
            $path = str_replace('.jpg', '.mp4', $res['data'][0]['ext2']);
            $this->botClient->sendVideoMsg([
                'uuid' => $this->clientId,
                'to_wxid' => $this->wxidDj,
                'path' => $path
            ]);
        }
        $this->assertContains($res['code'], $this->codeArr);
    }

    public function testDownloadFile(){
        $sid = '6029595085508160003';
        $params = [
            'path' => $sid
        ];
        $res = $this->botClient->downloadFile($params);
        if(!empty($res['data']['data'])){
            $url = upload_base64(time() . '.png', $res['data']['data']);
            dump($url);
            //dump(public_path(public_path('uploads') . 't.png'));
            //file_put_contents(public_path('uploads') . 't.png', $res['data']['data']);
        }else{
            dump($res);
        }
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 设置群名
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSetGroupName() {
        $res = $this->botClient->setGroupName([
            'uuid' => $this->clientId,
            'robot_wxid' => $this->robotFjq,
            'group_wxid' => '48202976260@chatroom',
            'group_name' => '龙年大吉2群'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 建群
     * resp: [
            "method" => "createRoom_Recv"
            "myid" => "wxid_7v3b6hncdo6f12"
            "pid" => 5452
            "status" => 1
            "msg" => ""
            "data" => [
                "msg" => "Everything is OK"
                "wxid" => "48202976260@chatroom"
                "count" => 3
                "member" => []
            ]
            "code" => 1
        ]
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testBuildingGroup() {
        $res = $this->botClient->buildingGroup([
            'uuid' => $this->clientId,
            'robot_wxid' => $this->robotFjq,
            'wxids' => [$this->wxidDj, 'wxid_bufi3udlbaqw22', 'wxid_7ittdniwav8k1']
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
            'uuid' => $this->clientId,
            'to_wxid' => $this->robotDj,
            'msgid' => '8131007268833536822'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 发送卡片
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendCard() {
        $res = $this->botClient->sendCardToFriend([
            'uuid' => $this->clientId,
            'to_wxid' => $this->wxidDj,
            'content' => "<?xml version=\"1.0\"?>\n<msg bigheadimgurl=\"http://wx.qlogo.cn/mmhead/Q3auHgzwzM4forBZ0HYx6vicU2B4xTlZ4WHofKxvPU7jSia6cem9tiaWQ/0\" smallheadimgurl=\"http://wx.qlogo.cn/mmhead/Q3auHgzwzM4forBZ0HYx6vicU2B4xTlZ4WHofKxvPU7jSia6cem9tiaWQ/132\" username=\"gh_ac5148129f0c\" nickname=\"腾讯理财通\" fullpy=\"腾讯理财通\" shortpy=\"TXLCT\" alias=\"cft_yuezengzhi\" imagestatus=\"3\" scene=\"17\" province=\"中国大陆\" city=\"广东\" sign=\"\" sex=\"0\" certflag=\"24\" certinfo=\"深圳市腾讯计算机系统有限公司\" brandIconUrl=\"http://mmbiz.qpic.cn/mmbiz_png/c1hrVO16zMRB6b8z1szsLDSJ8u37aIE6ZmwanN10lzHLOEkrpPDm2sFkJ7aiadTYdNicPdl7LZ5jwobCMWxBMytg/0?wx_fmt=png\" brandHomeUrl=\"\" brandSubscriptConfigUrl=\"\" brandFlags=\"0\" regionCode=\"CN_Guangdong\" />\n"
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }
    /**
     * 发送xml消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendXml() {
        $xml = "<?xml version=\"1.0\"?>
<msg>
        <videomsg aeskey=\"afb12860ebcd98006758b37d727029be\" cdnvideourl=\"3057020100044b304902010002042b56a25a02032f7f6102041be6a03d0204663b333a042431373437363636392d326566312d346230612d386331322d3137646237643666623466650204051800040201000405004c51e500\" cdnthumbaeskey=\"afb12860ebcd98006758b37d727029be\" cdnthumburl=\"3057020100044b304902010002042b56a25a02032f7f6102041be6a03d0204663b333a042431373437363636392d326566312d346230612d386331322d3137646237643666623466650204051800040201000405004c51e500\" length=\"1055796\" playlength=\"5\" cdnthumblength=\"15248\" cdnthumbwidth=\"0\" cdnthumbheight=\"0\" fromusername=\"wxid_xokb2ezu1p6t21\" md5=\"97090d37dda7bc127c874996ce216ba6\" newmd5=\"f2b4c03e06ce8b40331559ce9271de93\" isplaceholder=\"0\" rawmd5=\"\" rawlength=\"0\" cdnrawvideourl=\"\" cdnrawvideoaeskey=\"\" overwritenewmsgid=\"0\" originsourcemd5=\"\" isad=\"0\" />
</msg>";
        //$xml = "<appmsg appid=\"wx01bb1ef166cd3f4e\" sdkver=\"\"><title>推荐抽奖助手给你，来试试</title><des></des><action>view</action><type>33</type><showtype>0</showtype><content></content><url>https://mp.weixin.qq.com/mp/waerrpage?appid=wx01bb1ef166cd3f4e&amp;amp;type=upgrade&amp;amp;upgradetype=3#wechat_redirect</url><dataurl></dataurl><lowurl></lowurl><lowdataurl></lowdataurl><recorditem><![CDATA[]]></recorditem><thumburl></thumburl><messageaction></messageaction><extinfo></extinfo><sourceusername></sourceusername><sourcedisplayname>抽奖助手</sourcedisplayname><commenturl></commenturl><appattach><totallen>0</totallen><attachid></attachid><emoticonmd5></emoticonmd5><fileext>jpg</fileext><cdnthumburl>3053020100044730450201000204a2909e0802032f785902041a641d0e02045ff3f19c042033353163373338313038383133386431323037613866643162623835356566350204010808030201000405004c56f900</cdnthumburl><cdnthumblength>34626</cdnthumblength><cdnthumbheight>100</cdnthumbheight><cdnthumbwidth>100</cdnthumbwidth><aeskey>2afbc09f83eb8d1fc81c14dabd7ba4fd</aeskey><cdnthumbaeskey>2afbc09f83eb8d1fc81c14dabd7ba4fd</cdnthumbaeskey><cdnthumbmd5>fe88c190ab6996aea8ca044b1da41ac5</cdnthumbmd5><encryver>1</encryver><cdnthumblength>34626</cdnthumblength><cdnthumbheight>100</cdnthumbheight><cdnthumbwidth>100</cdnthumbwidth></appattach><weappinfo><pagepath>pages/index.html</pagepath><username>gh_0ba6f73cfa68@app</username><appid>wx01bb1ef166cd3f4e</appid><type>2</type><weappiconurl>http://mmbiz.qpic.cn/mmbiz_png/Vdys2e8jP1l1clbflznHYO7IRflCZWjPfD4NMn1Xqgr5gZbBy1qVc12cGVG1whLTXiafBT7kiaWRl38HCbqLnRzw/640?wx_fmt=png&amp;wxfrom=200</weappiconurl><appservicetype>0</appservicetype><shareId>2_wx01bb1ef166cd3f4e_455258050_1609822618_1</shareId></weappinfo><websearch /><finderFeed><objectId>0</objectId><objectNonceId>0</objectNonceId><feedType>-1</feedType><nickname></nickname><username></username><avatar></avatar><desc></desc><mediaCount>0</mediaCount><localId>0</localId><mediaList /></finderFeed><finderLive><finderLiveID>0</finderLiveID><finderUsername></finderUsername><finderObjectID>0</finderObjectID><nickname></nickname><desc></desc><finderNonceID>0</finderNonceID><headUrl></headUrl><liveStatus>-1</liveStatus><media><thumbUrl></thumbUrl><videoPlayDuration>0</videoPlayDuration><url></url><coverUrl></coverUrl><height>0</height><width>0</width><mediaType>-1</mediaType></media></finderLive></appmsg>";

        $res = $this->botClient->sendXmlToFriends([
            'uuid' => $this->clientId,
            'to_wxid' => $this->wxidDj,
            'xml' => $xml
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 发送普通链接消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendShareLinkMsg() {
        $res = $this->botClient->sendShareLinkToFriends([
            'uuid' => $this->clientId,
            'to_wxid' => [$this->wxidDj, $this->group51],
            'title' => "标题",
            "url" => "http://www.baidu.com",
            'desc' => '描述',
            'image_url' => 'http://img14.360buyimg.com/imgzone/jfs/t1/105427/11/20091/302754/61e1429aE6df0b06d/9a6307cacf90904f.jpg'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 发送视频消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendVideo() {
        //$path = "https://devhhb.images.huihuiba.net/1-62df9dac60d2c.mp4";
        $path = "https://guandaoji.oss-cn-hangzhou.aliyuncs.com/file/1-%E8%81%9A%E7%BE%8E%E4%BC%98%E5%93%81-20240513172659.mp4";
        $res = $this->botClient->sendVideoMsg([
            'uuid' => $this->clientId,
            'to_wxid' => $this->wxidDj,
            'path' => $path
            //'path' => "C:\\Users\\Administrator\\Documents\\WeChat Files\\wxid_7v3b6hncdo6f12\\FileStorage\\Video\\2024-05\\636106b910d49ee43c323fd5ab72e29c.mp4"
        ]);

        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 发送文件消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendFile() {
        $res = $this->botClient->sendFileToFriends([
            'uuid' => $this->clientId,
            'to_wxid' => $this->wxidDj,
            'path' => "http://mmbiz.qpic.cn/mmbiz_png/FUmbJicO6FCicxObbUXj5FcQ5a551rd1a9hgJAz5wnp3bU9I9vsHaq0g1lRxat0bYprRdianwnibibYgYMjTacicrnwA/640"
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
            'uuid' => $this->clientId,
            'to_wxid' => $this->wxidDj,
            'path' => "https://devhhb.images.huihuiba.net/1-637ae5c99e334.png"
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }
    /**
     * 发送文本消息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testSendText() {
        $res = $this->botClient->sendTextToFriends([
            'uuid' => $this->clientId,
            'to_wxid' => [$this->wxidDj, '48202976260@chatroom'],
            'msg' => '[玫瑰]欢迎进群！'
        ]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 退群
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testQuitGroup() {
        $res = $this->botClient->quitGroup(['uuid' => $this->clientId, 'group_wxid' => $this->groupTd]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 拉人入群
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testInviteInGroupByLink() {
        $res = $this->botClient->inviteInGroupByLink(['uuid' => $this->clientId,'to_wxid' => $this->wxidDj, 'group_wxid' => $this->group51]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 拉人入群
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testInviteInGroup() {
        $res = $this->botClient->inviteInGroup(['uuid' => $this->clientId,'to_wxid' => $this->wxidDj, 'group_wxid' => $this->group51]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 移除群成员
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testRemoveGroupMember() {
        $res = $this->botClient->removeGroupMember(['uuid' => $this->clientId,'to_wxid' => $this->wxidDj, 'group_wxid' => $this->group51]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 获取群成员
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetGroupMembers() {
        $res = $this->botClient->getGroupMembers(['uuid' => $this->clientId, 'group_wxid' => '23913604451@chatroom']);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 获取微信信息
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetRobotInfo() {
        $res = $this->botClient->getRobotInfo(['client_id' => $this->clientId]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 获取微信机器人列表
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetRobotList() {
        $res = $this->botClient->getRobotList();
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 登录码
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testGetLoginCode() {
        $res = $this->botClient->getLoginCode(['client_id' => 17036]);
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 注入微信
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testInject() {
        $res = $this->botClient->injectWechat();  //
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }

    /**
     * 添加异步通知
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function testNotifyAdd() {
        $params = [
            'type' => 'add',
            'data' => '1|3|34|37|42|43|47|48|49|701|702|703|704|705|706|707|708|720|721|723|724|725|726|727|728|729|802|803|804|810|840|10000',
            'url' => "http://wxbot.oudewa.cn/bot/api/extian",
            'reg' => '',
            'msg' => ''
        ];
        $res = $this->botClient->notify($params);  //
        dump($res);
        $this->assertContains($res['code'], $this->codeArr);
    }
}