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

    public static function events($id = null){
        $list = [
            self::BEADDED => '被加好友',
            self::FRIEND_IN => '新人入群',
            self::BEINVITED => '被邀请入群'
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }
}