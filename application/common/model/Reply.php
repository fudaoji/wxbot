<?php
/**
 * Created by PhpStorm.
 * Script Name: Reply.php
 * Create: 2022/4/15 10:23
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model;


use ky\Bot\Vlw;
use ky\Bot\Wxwork;

class Reply extends Base
{
    protected $isCache = true;

    /**
     *
     * @param $bot
     * @param $client Vlw|Wxwork
     * @param $reply
     * @param string $to_wxid
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function botReply($bot, $client, $reply, $to_wxid = ''){
        $media = model('media_' . $reply['media_type'])->getOneByMap(['admin_id' => $bot['admin_id'], 'id' => $reply['media_id']]);
        switch($reply['media_type']){
            default:
                $client->sendTextToFriend(['robot_wxid' => $bot['uin'], 'to_wxid' => $to_wxid, 'msg' => $media['content']]);
                break;
        }
    }
}