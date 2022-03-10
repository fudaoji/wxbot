<?php
/**
 * Created by PhpStorm.
 * Script Name: Kam.php
 * Create: 12/20/21 11:42 PM
 * Description: 可爱猫驱动 https://gitee.com/ikam/http-sdk
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky\Bot;


use ky\Logger;

class Kam extends Base
{
    const EVENT_FRIEND_MSG = 'EventFriendMsg';
    const EVENT_SEND_TEXT = 'SendTextMsg';

    public function __construct($options = [])
    {
        parent::__construct($options);
    }

    /**
     * 发送文本消息给好友
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendTextToFriend($params = []){
        $url = '/';
        Logger::error($url);
        return $this->request([
            'url' => $url,
            'method' => 'post',
            'headers' => ['AppKey' => $this->appKey],
            'data' => $params['data'] //{to: "", content:"", type:"text"}
        ]);
    }

}