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
    const FRIEND = 'friend';
    const GROUP = 'group';
    const MP = 'mp';


    const MSGTYPE_TEXT = "text";           // 文本消息
    const MSGTYPE_IMAGE = "image";          // 图片消息
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

}