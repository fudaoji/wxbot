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
use app\common\service\Bot;
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
use app\common\service\Media as MediaService;

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
        /*$media = model('media_' . $reply['media_type'])->getOneByMap([
            'admin_id' => ['in', [$bot['staff_id'], $bot['admin_id']]],
            'id' => $reply['media_id']
        ]);*/
        $media = MediaService::getMedia([
            'media_type' => $reply['media_type'],
            'staff_id' => $bot['staff_id'],
            'admin_id' => $bot['admin_id'],
            'media_id' => $reply['media_id']
        ]);
        if(empty($media)){
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
                $this->sendMsg([
                    'type' => $reply['media_type'],
                    'bot' => $bot,
                    'payload' => [
                        'robot_wxid' => $bot['uin'],
                        'to_wxid' => $to_wxid,
                        'url' => $media['url'],
                        'image_url' => $media['image_url'],
                        'title' => $media['title'],
                        'desc' => $media['desc']
                    ]
                ]);
                /*$client->sendShareLinkToFriends([
                    'robot_wxid' => $bot['uin'],
                    'uuid' => $bot['uuid'],
                    'to_wxid' => $to_wxid,
                    'url' => $media['url'],
                    'image_url' => $media['image_url'],
                    'title' => $media['title'],
                    'desc' => $media['desc']
                ]);*/
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
                    $this->sendMsg([
                        'type' => $reply['media_type'],
                        'bot' => $bot,
                        'payload' => [
                            'robot_wxid' => $bot['uin'],
                            'uuid' => $bot['uuid'],
                            'to_wxid' => $to_wxid,
                            'msg' => $msg
                        ]
                    ]);
                    /*$client->sendTextToFriends([
                        'robot_wxid' => $bot['uin'],
                        'uuid' => $bot['uuid'],
                        'to_wxid' => $to_wxid,
                        'msg' => $msg
                    ]);*/
                }
                break;
            case Media::IMAGE:
                $this->sendMsg([
                    'type' => $reply['media_type'],
                    'bot' => $bot,
                    'payload' => [
                        'robot_wxid' => $bot['uin'],
                        'uuid' => $bot['uuid'],
                        'to_wxid' => $to_wxid,
                        'path' => $media['url']
                    ]
                ]);
                /*$client->sendImgToFriends([
                    'robot_wxid' => $bot['uin'],
                    'uuid' => $bot['uuid'],
                    'to_wxid' => $to_wxid,
                    'path' => $media['url']
                ]);*/
                break;
            case Media::VIDEO:
                $this->sendMsg([
                    'type' => $reply['media_type'],
                    'bot' => $bot,
                    'payload' => [
                        'robot_wxid' => $bot['uin'],
                        'uuid' => $bot['uuid'],
                        'to_wxid' => $to_wxid,
                        'path' => $media['url']
                    ]
                ]);
                /*$client->sendVideoToFriends([
                    'robot_wxid' => $bot['uin'],
                    'uuid' => $bot['uuid'],
                    'to_wxid' => $to_wxid,
                    'path' => $media['url']
                ]);*/
                break;
            case Media::FILE:
                $res = $client->saveFile([
                    'data' => $media['url'],
                    'type' => 'url'
                ]);
                if(!empty($res['code'])){ //下载到本地提高成功率
                    $path = $res['data'];
                    $this->sendMsg([
                        'type' => $reply['media_type'],
                        'bot' => $bot,
                        'payload' => [
                            'robot_wxid' => $bot['uin'],
                            'to_wxid' => $to_wxid,
                            'path' => $path,
                            'file_type' => 'file'
                        ]
                    ]);
                }
                /*$client->sendFileToFriends([
                    'robot_wxid' => $bot['uin'],
                    'uuid' => $bot['uuid'],
                    'to_wxid' => $to_wxid,
                    'path' => $media['url'],
                    'file_storage_path' => config('system.bot.file_storage_path')
                ]);*/
                break;
            case Media::XML:
                $this->sendMsg([
                    'type' => $reply['media_type'],
                    'bot' => $bot,
                    'payload' => [
                        'robot_wxid' => $bot['uin'],
                        'to_wxid' => $to_wxid,
                        'xml' => $media['content']
                    ]
                ]);
                /*$client->sendXmlToFriends([
                    'robot_wxid' => $bot['uin'],
                    'uuid' => $bot['uuid'],
                    'to_wxid' => $to_wxid,
                    'xml' => $media['content']
                ]);*/
                break;
        }
    }

    private function sendMsg($params){
        $payload = $params['payload'];
        $wxid_arr = is_array($payload['to_wxid']) ? $payload['to_wxid'] : explode(',', $payload['to_wxid']);
        $delay = 0;
        foreach ($wxid_arr as $wxid){
            $payload['to_wxid'] = $wxid;
            $params['payload'] = $payload;
            invoke('\\app\\common\\event\\TaskQueue')->push([
                'delay' => $delay,
                'params' => array_merge($params, [
                    'do' => ['\\app\\common\\model\\Reply', 'sendJob']
                ])
            ]);
            $delay += random_int(1,2);
        }
    }


    /**
     * 发送消息的最小单元
     * @param $params
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function sendJob($params){
        /**
         * @var \think\queue\Job
         */
        $job = $params['job'];
        if ($job->attempts() > 2) {
            $job->delete();
        }

        $bot = $params['bot'];
        /**
         * @var $client Extian
         */
        $client = Bot::model()->getRobotClient($bot);
        $type = $params['type'];
        $payload = $params['payload'];
        switch ($type){
            case Media::LINK:
                $client->sendShareLinkMsg($payload);
                break;
            case Media::TEXT:
                $client->sendTextToFriend($payload);
                break;
            case Media::IMAGE:
                $client->sendImgToFriend($payload);
                break;
            case Media::VIDEO:
                $client->sendVideoMsg($payload);
                break;
            case Media::FILE:
                $client->sendFileMsg($payload);
                break;
            case Media::XML:
                $res = $client->sendXml($payload);
                //Logger::error($res);
                break;
        }

        $job->delete();
    }
}