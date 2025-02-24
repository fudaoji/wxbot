<?php
/**
 * Created by PhpStorm.
 * Script Name: Reply.php
 * Create: 2022/4/15 10:23
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model;

use app\admin\model\BotMember;
use app\constants\Bot as BotConst;
use app\constants\Media;
use ky\Logger;
use ky\WxBot\Driver\Cat;
use ky\WxBot\Driver\Extian;
use ky\WxBot\Driver\My;
use ky\WxBot\Driver\Mycom;
use ky\WxBot\Driver\Qianxun;
use ky\WxBot\Driver\Vlw;
use ky\WxBot\Driver\Wxwork;

class Reply extends Base
{
    protected $isCache = true;

    /**
     * 封装素材回复
     * @param $bot
     * @param $client Vlw|Wxwork|Cat|My|Mycom|Qianxun|Extian
     * @param $reply
     * @param string $to_wxid
     * @param array $extra
     * Author: fudaoji<fdj@kuryun.cn>
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function botReply($bot, $client, $reply, $to_wxid = '', $extra = []){
        if(empty($media = model('media_' . $reply['media_type'])->getOneByMap([
            'admin_id' => ['in', [$bot['staff_id'], $bot['admin_id']]],
            'id' => $reply['media_id']
        ]))){
            return false;
        }
        if(in_array($bot['protocol'], [BotConst::PROTOCOL_XBOTCOM])){ //企业微信特殊处理
            $to_wxid_arr = is_array($to_wxid) ? $to_wxid : explode(',', $to_wxid);
            $member_m = new BotMember();
            $to_wxid = [];
            foreach ($to_wxid_arr as $wxid){
                $m = $member_m->getOneByMap(['uin' => $bot['uin'], 'wxid' => $wxid], ['username']);
                $to_wxid[] = $m['username'];
            }
        }
        switch($reply['media_type']){
            case Media::LINK:
                $client->sendShareLinkToFriends([
                    'robot_wxid' => $bot['uin'],
                    'uuid' => $bot['uuid'],
                    'to_wxid' => $to_wxid,
                    'url' => $media['url'],
                    'image_url' => $media['image_url'],
                    'title' => $media['title'],
                    'desc' => $media['desc']
                ]);
                break;
            case Media::TEXT:
                $msg = empty($extra['nickname']) ? $media['content'] : str_replace('[昵称]', $extra['nickname'], $media['content']);
                $msg = empty($extra['group_name']) ? $msg : str_replace('[群名称]', $extra['group_name'], $msg);
                if(!empty($extra['need_at'])){
                    $client->sendGroupMsgAndAt([
                        'robot_wxid' => $bot['uin'],
                        'uuid' => $bot['uuid'],
                        'group_wxid' => $to_wxid,
                        'member_wxid' => $extra['member_wxid'],
                        'msg' => $msg
                    ]);
                }elseif(!empty($extra['atall'])){
                    $group_ids = explode(',', $to_wxid);
                    foreach ($group_ids as $gid){
                        $client->sendMsgAtAll([
                            'robot_wxid' => $bot['uin'],
                            'uuid' => $bot['uuid'],
                            'group_wxid' => $gid,
                            'msg' => $msg
                        ]);
                    }
                }else{
                    $client->sendTextToFriends([
                        'robot_wxid' => $bot['uin'],
                        'uuid' => $bot['uuid'],
                        'to_wxid' => $to_wxid,
                        'msg' => $msg
                    ]);
                }
                break;
            case Media::IMAGE:
                $client->sendImgToFriends([
                    'robot_wxid' => $bot['uin'],
                    'uuid' => $bot['uuid'],
                    'to_wxid' => $to_wxid,
                    'path' => $media['url']
                ]);
                break;
            case Media::VIDEO:
                $client->sendVideoToFriends([
                    'robot_wxid' => $bot['uin'],
                    'uuid' => $bot['uuid'],
                    'to_wxid' => $to_wxid,
                    'path' => $media['url']
                ]);
                break;
            case Media::FILE:
                $client->sendFileToFriends([
                    'robot_wxid' => $bot['uin'],
                    'uuid' => $bot['uuid'],
                    'to_wxid' => $to_wxid,
                    'path' => $media['url'],
                    'file_storage_path' => config('system.bot.file_storage_path')
                ]);
                break;
            case Media::XML:
                $client->sendXmlToFriends([
                    'robot_wxid' => $bot['uin'],
                    'uuid' => $bot['uuid'],
                    'to_wxid' => $to_wxid,
                    'xml' => $media['content']
                ]);
                break;
        }
    }
}