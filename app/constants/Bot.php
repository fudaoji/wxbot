<?php
/**
 * Created by PhpStorm.
 * Script Name: Bot.php
 * Create: 2021/12/21 13:01
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\constants;

use app\common\service\XmlMini;
use ky\Logger;
use function GuzzleHttp\Psr7\str;

class Bot
{
    const PROTOCOL_WEB = 'webgo';
    const PROTOCOL_CAT = 'cat';
    const PROTOCOL_VLW = 'vlw';
    const PROTOCOL_WXWORK = 'wxwork'; //企业微信
    const PROTOCOL_MY = 'my'; //西瓜个微
    const PROTOCOL_MYCOM = 'mycom'; //西瓜企微
    const PROTOCOL_QXUN = 'qianxun'; //千寻个微
    const PROTOCOL_XBOT = 'xbot'; //XBOT个微
    const PROTOCOL_EXTIAN = 'extian'; //e小天个微
    const PROTOCOL_XHX = 'xhx'; //小浣熊
    const PROTOCOL_XY = 'xy'; //星云
    const PROTOCOL_KUV = 'kuv'; //酷v

    const FRIEND = 'friend';
    const GROUP = 'group';
    const MP = 'mp';

    const EVENT_INVITED_IN_GROUP = 'EventInvitedInGroup'; //被邀请进群
    const EVENT_GROUP_ESTABLISH = 'EventGroupEstablish'; //创建新群
    const EVENT_GROUP_MEMBER_ADD = 'EventGroupMemberAdd'; //群人员减少
    const EVENT_GROUP_MEMBER_DEC = 'EventGroupMemberDecrease'; //群人员减少
    const EVENT_FRIEND_VERIFY = 'EventFriendVerify'; //好友请求事件
    const EVENT_DEVICE = "EventDeviceCallback"; //设备回调事件
    const EVENT_LOGIN = "EventLogin"; //登录、退出
    const EVENT_LOGOUT = "EventLogout"; //退出
    const EVENT_GROUP_CHAT = "EventGroupChat"; //群聊事件
    const EVENT_PRIVATE_CHAT = "EventPrivateChat"; //私聊消息事件
    const EVENT_RECEIVE_TRANSFER = 'EventReceivedTransfer'; //收到转账
    const EVENT_SCAN_CASH_MONEY = 'EventScanCashMoney'; //面对面付款
    const EVENT_LOGIN_CODE = 'EventLoginCode'; //接收登录二维码信息
    const EVENT_CONNECTED = 'EventConnected';

    const MSG_TEXT = 1; //文本消息
    const MSG_IMG = 3; //图片消息
    const MSG_NEWS = 5; //图文链接
    const MSG_MINI = 33; //小程序
    const MSG_VOICE = 34; //语音消息
    const MSG_VIDEO = 43; //视频
    const MSG_LINK = 49; //分享链接、视频号
    const MSG_FINDER = 50; //视频号
    const MSG_FINDER_VIDEO = 51; //视频号短视频
    const MSG_FILE = 2004;  //文件消息
    const MSG_EMOTICON = 47;   // 表情消息
    const MSG_CARD = 42; //名片消息
    const MSG_LOCATION= 48;    // 地理位置消息
    const MSG_TRANSFER = 2000;  //转账消息
    const MSG_RED = 2001;  //红包消息
    const MSG_APP = 2002;  // 小程序消息
    const MSG_GROUPINVITE = 2003;  //群邀请
    const MSG_VERIFY = 37;    // 好友请求
    const MSG_SYS = 10000; // 系统消息
    const MSG_RECALLED = 2005;  // 消息撤回
    const MSG_OTHER = 0; // 其他消息
    const MSG_QUOTE = 10003; //引用消息

    //添加好友场景值
    const SCENE_WXNUM = 3; //微xin号搜索
    const SCENE_QQ = 12;  //QQ号搜索
    const SCENE_GROUP = 14; //群聊
    const SCENE_CONTACT = 15; //手机通讯录
    const SCENE_CARD = 17; //名片分享
    const SCENE_NEAR = 18; //附近人
    const SCENE_SHAKE = 29; //摇一摇
    const SCENE_SCAN = 30; //扫一扫

    /**
     * 消息类型
     * @param null $id
     * @return array|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public static function msgTypes($id = null){
        $list = [
            self::MSG_TEXT => '文本',
            self::MSG_IMG => '图片',
            self::MSG_FILE => '文件',
            self::MSG_VOICE => '语音',
            self::MSG_VIDEO => '视频',
            self::MSG_NEWS => '图文链接',
            self::MSG_LINK => '分享链接',
            self::MSG_EMOTICON => '动态表情',
            self::MSG_CARD => '微信名片',
            self::MSG_MINI => '小程序',
            self::MSG_FINDER => '视频号',
            self::MSG_FINDER_VIDEO => '视频号短视频',
            self::MSG_LOCATION => '地理位置',
            self::MSG_TRANSFER => '转账',
            self::MSG_RED => '红包',
            self::MSG_APP => 'app',
            self::MSG_GROUPINVITE => '进群邀请',
            self::MSG_VERIFY => '加好友验证',
            self::MSG_RECALLED => '消息撤回',
            self::MSG_QUOTE => '引用消息',
            self::MSG_OTHER => '其他'
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }

    /**
     * 机器人协议
     * @param null $id
     * @return array|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public static function protocols($id = null)
    {
        $list = [
            self::PROTOCOL_KUV  => 'kuv',
            self::PROTOCOL_XY => '星云',
            self::PROTOCOL_QXUN => '千寻个微',
            self::PROTOCOL_WEB => '网页版',
            self::PROTOCOL_MY => '西瓜个微',
            self::PROTOCOL_MYCOM => '西瓜企微',
            self::PROTOCOL_CAT => '可爱猫个微',
            self::PROTOCOL_VLW => 'VLW个微',
            self::PROTOCOL_WXWORK => 'VLW企微',
            self::PROTOCOL_XBOT => 'XBOT个微',
            self::PROTOCOL_EXTIAN => 'e小天个微',
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }

    /**
     * 机器人协议
     * @param null $id
     * @return array|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public static function webs($id = null)
    {
        $list = [
            self::PROTOCOL_WEB => 'go-wxbot',
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }

    /**
     * 机器人hook协议
     * @param null $id
     * @return array|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public static function hooks($id = null)
    {
        $list = [
            //self::PROTOCOL_MY => '西瓜个微',
            //self::PROTOCOL_MYCOM => '西瓜企微',
            //self::PROTOCOL_CAT => '可爱猫个微',
            self::PROTOCOL_EXTIAN => 'e小天个微',
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }

    /**
     * 支持扫码登录的机器人协议
     * @param null $id
     * @return array|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public static function canScan($id = null)
    {
        $list = [
            self::PROTOCOL_MY => '西瓜个微',
            self::PROTOCOL_MYCOM => '西瓜企微',
            self::PROTOCOL_EXTIAN=> 'e小天个微',
            //self::PROTOCOL_XBOT=> 'XBot个微'
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }

    /**
     * 解析消息类型
     * @param $content
     * @param string $protocol
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public static function getContentType(&$content, $protocol = self::PROTOCOL_EXTIAN){
        switch ($protocol){
            default:
                switch ($content['type']){
                    case self::MSG_VERIFY:
                        $object = (new XmlMini($content['msg']))->getObject();
                        $content['encryptusername'] = (string)$object['encryptusername'];
                        $content['ticket'] = (string)$object['ticket'];
                        $content['scene'] = (string)$object['scene'];
                        $content['fromid'] = (string)$object['fromusername'];
                        $content['nickName'] = (string)$object['fromnickname'];
                        $content['headImg'] = (string)$object['smallheadimgurl'];
                        break;
                    case self::MSG_LINK: //49
                        $object = new XmlMini($content['msg']);
                        $content['type'] = (string) $object->decodeObject()->type;
                        break;
                }
        }
        //Logger::error($content);
        return $content;
    }
}