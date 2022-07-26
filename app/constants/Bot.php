<?php
/**
 * Created by PhpStorm.
 * Script Name: Bot.php
 * Create: 2021/12/21 13:01
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\constants;


class Bot
{
    const PROTOCOL_WEB = 'webgo';
    const PROTOCOL_CAT = 'cat';
    const PROTOCOL_VLW = 'vlw';
    const PROTOCOL_WXWORK = 'wxwork'; //企业微信
    const PROTOCOL_MY = 'my'; //我的个微
    const PROTOCOL_MYCOM = 'mycom'; //我的企微

    const FRIEND = 'friend';
    const GROUP = 'group';
    const MP = 'mp';

    const EVENT_GROUP_MEMBER_ADD = 'EventGroupMemberAdd'; //群人员减少
    const EVENT_GROUP_MEMBER_DEC = 'EventGroupMemberDecrease'; //群人员减少
    const EVENT_FRIEND_VERIFY = 'EventFriendVerify'; //好友请求事件
    const EVENT_DEVICE = "EventDeviceCallback"; //设备回调事件
    const EVENT_LOGIN = "EventLogin"; //登录、退出
    const EVENT_GROUP_CHAT = "EventGroupChat"; //群聊事件
    const EVENT_PRIVATE_CHAT = "EventPrivateChat"; //私聊消息事件
    const EVENT_RECEIVE_TRANSFER = 'EventReceivedTransfer'; //收到转账
    const EVENT_SCAN_CASH_MONEY = 'EventScanCashMoney'; //面对面付款

    //2004文件消息  1/文本消息 3/图片消息 34/语音消息  42/名片消息  43/视频 47/动态表情 48/地理位置  49/分享链接  2001/红包  2002/小程序  2003/群邀请
    const MSG_TEXT = 1;
    const MSG_IMG = 3;
    const MSG_VOICE = 34;
    const MSG_CARD = 42;
    const MSG_VIDEO = 43;
    const MSG_LINK = 49;
    const MSG_EMOTICON = 47;   // 表情消息
    const MSG_LOCATION= 48;    // 地理位置消息
    const MSG_TRANSFER = 2000;  //转账消息
    const MSG_RED = 2001;  //红包消息
    const MSG_APP = 2002;  // 小程序消息
    const MSG_GROUPINVITE = 2003;  //群邀请
    const MSG_RECEIVEFILE = 2004;  //接收文件
    const MSG_VERIFY = 37;    // 好友验证
    const MSG_SYS = 10000; // 系统消息
    const MSG_RECALLED = 2005;  // 消息撤回

    const MSGTYPE_TEXT = "text";           // 文本消息
    const MSGTYPE_IMAGE = "image";          // 图片消息
    const MSGTYPE_FILE = "file";          // 文件消息
    const MSGTYPE_VOICE = "voice";          // 语音消息
    const MSGTYPE_VIDEO = "video" ;         // 视频消息
    const MSGTYPE_LINK = "link";           // 分享链接
    const MSGTYPE_EMOTICON = "emotion" ;       // 表情消息
    const MSGTYPE_VERIFY        = "verify";         // 认证消息
    const MSGTYPE_POSSIBLEFRIEND = "possiblefriend"; // 好友推荐
    const MSGTYPE_SHARECARD      = "sharecard";      // 名片消息

    const MSGTYPE_LOCATION  = "location";   // 地理位置消息
    const MSGTYPE_APP         = "app"    ;    // APP消息
    const MSGTYPE_VOIP        = "voip"    ;   // VOIP消息
    const MSGTYPE_VOIPNOTIFY  = "voipnotify"; // voip结束消息
    const MSGTYPE_VOIPINVITE  = "voipinvite" ;// VOIP邀请
    const MSGTYPE_MICROVIDEO  = "microvideo" ;// 小视频消息
    const MSGTYPE_SYS         = "sys"        ;// 系统消息
    const MSGTYPE_RECALLED    = "recalled"   ;// 消息撤回

    /**
     * 机器人协议
     * @param null $id
     * @return array|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public static function protocols($id = null)
    {
        $list = [
            self::PROTOCOL_WEB => '网页版',
            self::PROTOCOL_MY => '我的个微',
            self::PROTOCOL_MYCOM => '我的企微',
            self::PROTOCOL_CAT => '可爱猫个微',
            self::PROTOCOL_VLW => 'VLW个微',
            self::PROTOCOL_WXWORK => 'VLW企微',
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
            self::PROTOCOL_MY => '我的个微',
            self::PROTOCOL_MYCOM => '我的企微',
            self::PROTOCOL_CAT => '可爱猫个微',
            self::PROTOCOL_VLW => 'VLW个微',
            self::PROTOCOL_WXWORK => 'VLW企微',
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }
}