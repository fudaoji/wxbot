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
    const HANDLE_TRANSFER_ACCEPT = 'transfer_accept';
    const HANDLE_TRANSFER_REFUSE = 'transfer_refuse';
    const HANDLE_ADDED_ACCEPT = 'added_accept';

    /**
     * 事件枚举
     * @param null $id
     * @return array|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public static function events($id = null){
        $list = [
            self::BEADDED => '成为好友',
            self::FRIEND_IN => '新人入群',
            self::BEINVITED => '被邀请入群',
            self::MSG => '消息事件'
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }

    /**
     * 响应枚举
     * @param null $id
     * @return array|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public static function handleTypes($id = null){
        $list = [
            self::HANDLE_MSG => '消息回复',
            self::HANDLE_DEL => '删除好友',
            self::HANDLE_RM => '移出群聊',
            self::HANDLE_TRANSFER_ACCEPT => '接收转账',
            self::HANDLE_TRANSFER_REFUSE => '拒收转账',
            self::HANDLE_ADDED_ACCEPT => '自动通过好友请求'
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }

    /**
     * 动作响应类型
     * @param null $id
     * @return array|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function actionTypes($id = null){
        $list = self::handleTypes();
        unset($list[self::HANDLE_MSG]);
        return isset($list[$id]) ? $list[$id] : $list;
    }
}