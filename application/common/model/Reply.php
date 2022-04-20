<?php
/**
 * Created by PhpStorm.
 * Script Name: Reply.php
 * Create: 2022/4/15 10:23
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model;


use app\constants\Media;
use ky\Bot\Vlw;
use ky\Bot\Wxwork;
use ky\Logger;

class Reply extends Base
{
    protected $isCache = true;

    /**
     * 封装素材回复
     * @param $bot
     * @param $client Vlw|Wxwork
     * @param $reply
     * @param string $to_wxid
     * @param array $extra
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function botReply($bot, $client, $reply, $to_wxid = '', $extra = []){
        $media = model('media_' . $reply['media_type'])->getOneByMap(['admin_id' => $bot['admin_id'], 'id' => $reply['media_id']]);
        //Logger::error($media);
        switch($reply['media_type']){
            case Media::LINK:
                $client->sendShareLinkToFriends([
                    'robot_wxid' => $bot['uin'],
                    'to_wxid' => $to_wxid,
                    'url' => $media['url'],
                    'image_url' => $media['image_url'],
                    'title' => $media['title'],
                    'desc' => $media['desc']
                ]);
                break;
            case Media::TEXT:
                $msg = empty($extra['nickname']) ? $media['content'] : str_replace('[昵称]', $extra['nickname'], $media['content']);
                $client->sendTextToFriends(['robot_wxid' => $bot['uin'], 'to_wxid' => $to_wxid, 'msg' => $msg]);
                break;
            case Media::IMAGE:
                $client->sendImgToFriends([
                    'robot_wxid' => $bot['uin'],
                    'to_wxid' => $to_wxid,
                    'path' => $media['url']
                ]);
                break;
            case Media::VIDEO:
                $client->sendVideoToFriends([
                    'robot_wxid' => $bot['uin'],
                    'to_wxid' => $to_wxid,
                    'path' => $media['url']
                ]);
                break;
            case Media::FILE:
                $client->sendFileToFriends([
                    'robot_wxid' => $bot['uin'],
                    'to_wxid' => $to_wxid,
                    'path' => $media['url']
                ]);
                break;
        }
    }
}