<?php
/**
 * Created by PhpStorm.
 * Script Name: Bot.php
 * Create: 2022/7/12 11:07
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\event;

use app\constants\Bot as BotConst;
use ky\Logger;

class Bot extends Base
{
    /**
     * @var \app\admin\model\Bot
     */
    private $botM;

    public function __construct()
    {
        parent::__construct();
        $this->botM = new \app\admin\model\Bot();
    }

    /**
     * 消息转发
     * @param array $data
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function forwardMsg($data = []){
        /**
         * @var \think\queue\Job
         */
        $job = $data['job'];
        //Logger::error('尝试次数：'.$job->attempts());
        if ($job->attempts() > 1) {
            //通过这个方法可以检查这个任务已经重试了几次了
            $job->delete();
            return true;
        }
        $bot = $data['bot_info'];
        $bot_client = $this->botM->getRobotClient($bot);
        $to_wxid = $data['to_wxid'];
        $robot_wxid = $bot['uin'];
        $content = $data['content'];
        $msg_id = $content['msg_id'];
        $type = $content['type'] ?? BotConst::MSG_TEXT;
        //Logger::error($type);
        switch($bot['protocol']){
            case BotConst::PROTOCOL_EASYCOM:
                switch ($type){
                    case BotConst::MSG_NEWS:
                        $msg = $content['msg'];
                        $file_type = 4;
                        $params = [
                            'fileAesKey' => $msg['iconAesKey'],
                            'fileAuthKey' => $msg['iconAuthKey'],
                            'fileSize' => $msg['iconSize'],
                            'fileType' => $file_type,
                            'fileUrl' => $msg['iconUrl'],
                        ];
                        $res = $bot_client->downloadFile($params);
                        if(empty($res['code'])){
                            Logger::error($res['errmsg']);
                            break;
                        }
                        $res = $bot_client->sendShareLinkToFriends([
                            'to_wxid' => $to_wxid,
                            'title' => $msg['title'],
                            'image_url' => $res['data']['cloudUrl'],
                            'url' => $msg['linkUrl'],
                            'desc' => $msg['desc']
                        ]);

                        break;
                    case BotConst::MSG_FILE:
                        $file_type = 5;
                        $params = [
                            'fileAesKey' => $content['msg']['fileAesKey'],
                            'fileAuthKey' => $content['msg']['fileAuthKey'],
                            'fileSize' => $content['msg']['fileSize'],
                            'fileType' => $file_type,
                            'fileUrl' => $content['msg']['fileHttpUrl'],
                        ];
                        $res = $bot_client->downloadFile($params);
                        if(empty($res['code'])){
                            Logger::error($res['errmsg']);
                            break;
                        }
                        $res = $bot_client->uploadCdn([
                            'url' => $res['data']['cloudUrl'],
                            'file_type' => $file_type
                        ]);
                        if(empty($res['code'])){
                            Logger::error($res['errmsg']);
                            break;
                        }
                        $bot_client->sendFileToFriends([
                            'to_wxid' => $to_wxid,
                            'detail' => $res['data'],
                            'filename' => $content['msg']['filename']
                        ]);
                        break;
                    case BotConst::MSG_VIDEO:
                        $file_type = 4;
                        $params = [
                            'fileAesKey' => $content['msg']['fileAesKey'],
                            'fileAuthKey' => $content['msg']['fileAuthKey'],
                            'fileSize' => $content['msg']['fileSize'],
                            'fileType' => $file_type,
                            'fileUrl' => $content['msg']['fileHttpUrl'],
                        ];
                        $res = $bot_client->downloadFile($params);
                        if(empty($res['code'])){
                            Logger::error($res['errmsg']);
                            break;
                        }
                        $res = $bot_client->uploadCdn([
                            'url' => $res['data']['cloudUrl'],
                            'file_type' => $file_type
                        ]);
                        if(empty($res['code'])){
                            Logger::error($res['errmsg']);
                            break;
                        }
                        $bot_client->sendVideoToFriends([
                            'to_wxid' => $to_wxid,
                            'detail' => $res['data']
                        ]);
                        break;
                    case BotConst::MSG_IMG:
                        $file_type = 4;
                        $params = [
                            'fileAesKey' => $content['msg']['fileAesKey'],
                            'fileAuthKey' => $content['msg']['fileAuthKey'],
                            'fileSize' => $content['msg']['fileBigSize'],
                            'fileType' => $file_type,
                            'fileUrl' => $content['msg']['fileBigHttpUrl'],
                        ];
                        $res = $bot_client->downloadFile($params);
                        if(empty($res['code'])){
                            Logger::error($res['errmsg']);
                            break;
                        }
                        $res = $bot_client->uploadCdn([
                            'url' => $res['data']['cloudUrl'],
                            'file_type' => $file_type
                        ]);
                        if(empty($res['code'])){
                            Logger::error($res['errmsg']);
                            break;
                        }
                        $bot_client->sendImgToFriends([
                            'to_wxid' => $to_wxid,
                            'detail' => $res['data']
                        ]);
                        break;
                    case BotConst::MSG_TEXT:
                        $bot_client->sendTextToFriends([
                            'to_wxid' => $to_wxid,
                            'msg' => $content['msg']
                        ]);
                        break;
                }
                break;
            case BotConst::PROTOCOL_EXTIAN:
                switch ($type){
                    case BotConst::MSG_VIDEO:
                        //视频转发失效了
                        /*$res = $bot_client->getMsg([
                            'uuid' => $bot['uuid'],
                            'msgid' => $msg_id
                        ]);
                        Logger::error($res);
                        if(!empty($res['data'][0]['ext2'])){
                            $path = str_replace('.jpg', '.mp4', $res['data'][0]['ext2']);
                            $tries = 0;
                            do{
                                $send_res = $bot_client->sendVideoToFriends([
                                    'uuid' => $bot['uuid'],
                                    'to_wxid' => $to_wxid,
                                    'path' => $path
                                ]);
                                $tries++;
                                sleep(1);
                            }while(empty($send_res['code']) && $tries < 10);
                        }*/
                        break;
                    case BotConst::MSG_IMG:
                        $res = $bot_client->downloadFile([
                            'uuid' => $bot['uuid'],
                            'path' => $msg_id
                        ]);
                        if(!empty($res['data']['path'])){
                            $path = $res['data']['path'];
                            $tries = 0;
                            do{
                                $send_res = $bot_client->sendFileToFriends([
                                    'uuid' => $bot['uuid'],
                                    'to_wxid' => $to_wxid,
                                    'path' => $path
                                ]);
                                $tries++;
                                sleep(1);
                            }while(empty($send_res['code']) && $tries < 10);
                        }
                        break;
                    default:
                        $bot_client->forwardMsgToFriends([
                            'robot_wxid' => $robot_wxid,
                            'to_wxid' => $to_wxid,
                            'msgid' => $msg_id
                        ]);
                        break;
                }
                break;
            default:
                $bot_client->forwardMsgToFriends([
                    'robot_wxid' => $robot_wxid,
                    'to_wxid' => $to_wxid,
                    'msgid' => $msg_id
                ]);
                break;
        }

        $job->delete();
    }

    /**
     * 群成员增减统计
     * Author: fudaoji<fdj@kuryun.cn>
     * @param array $data
     */
    public function tjGroup($data = []){
        /**
         * @var \think\queue\Job
         */
        $job = $data['job'];
        if ($job->attempts() > 1) {
            //通过这个方法可以检查这个任务已经重试了几次了
            $job->delete();
            return true;
        }
        $field = $data['type'] == 'add' ? 'add_num' : 'decr_num';
        if(! $tj = model('common/TjGroup')->getOneByMap(['group_id' => $data['group_id'], 'day' => $data['day']], true, true)){
            $bot = model('admin/bot')->getOne($data['bot_id']);
            $insert = [
                'admin_id' => $bot['admin_id'],
                'group_id' => $data['group_id'],
                'day' => $data['day'],
                'bot_id' => $data['bot_id'],
                $field => 1
            ];
            model('common/TjGroup')->addOne($insert);
        }else{
            model('common/TjGroup')->updateOne(['id' => $tj['id'],  $field => $tj[$field] + 1]);
        }
        $job->delete();
    }
}