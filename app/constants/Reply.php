<?php
/**
 * Created by PhpStorm.
 * Script Name: Reply.php
 * Create: 2022/4/15 10:29
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\constants;


class Reply
{
    const BEADDED = 'beadded';
    const BEINVITED = 'beinvited';
    const FRIEND_IN = 'friend_in';
    const MSG = 'msg';

    const HANDLE_RM = 'rm';
    const HANDLE_DEL = 'del';
    const HANDLE_MSG = 'msg';

    public static function events($id = null){
        $list = [
            self::BEADDED => '被加好友',
            self::FRIEND_IN => '新人入群',
            self::BEINVITED => '被邀请入群',
            self::MSG => '消息事件'
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }

    public static function handleTypes($id = null){
        $list = [
            self::HANDLE_MSG => '消息回复',
            self::HANDLE_DEL => '删除好友',
            self::HANDLE_RM => '移出群聊',
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }
}