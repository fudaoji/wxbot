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
    const PROTOCOL_WEB = 'web';
    const PROTOCOL_XP = 'xp';
    const PROTOCOL_KAM = 'kam';
    const PROTOCOL_VLW = 'vlw';
    const PROTOCOL_WXWORK = 'wxwork'; //企业微信

    const FRIEND = 'friend';
    const GROUP = 'group';
    const MP = 'mp';

    const EVENT_GROUP_MEMBER_ADD = 'EventGroupMemberAdd'; //群人员减少
    const EVENT_GROUP_MEMBER_DEC = 'EventGroupMemberDecrease'; //群人员减少
    const EVENT_FRIEND_VERIFY = 'EventFrieneVerify'; //好友请求事件
    const EVENT_DEVICE = "EventDeviceCallback"; //设备回调事件
    const EVENT_LOGIN = "Login"; //登录、退出
    const EVENT_GROUPCHAT = "EventGroupChat"; //群聊事件
    const EVENT_PRIVATECHAT = "EventPrivateChat"; //私聊消息事件

    const MSGTYPE_TEXT = "text";           // 文本消息
    const MSGTYPE_IMAGE = "image";          // 图片消息
    const MSGTYPE_FILE = "file";          // 文件消息
    const MSGTYPE_VOICE = "voice";          // 语音消息
    const MSGTYPE_VIDEO = "video" ;         // 视频消息
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
            self::PROTOCOL_VLW => '个微',
            self::PROTOCOL_WXWORK => '企微',
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }
}