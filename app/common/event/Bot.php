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
use think\Db;
use think\facade\Log;

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
        if ($job->attempts() > 2) {
            //通过这个方法可以检查这个任务已经重试了几次了
            $job->delete();
        }
        $bot = $data['bot_info'];
        $bot_client = $this->botM->getRobotClient($bot);
        $to_wxid = $data['to_wxid'];
        $robot_wxid = $bot['uin'];
        $msg_id = $data['msgid'];
        $type = $data['content_type'] ?? BotConst::MSG_TEXT;
        if($bot['protocol'] == BotConst::PROTOCOL_EXTIAN){
            switch ($type){
                case BotConst::MSG_VIDEO:
                    $res = $bot_client->getMsg([
                        'uuid' => $bot['uuid'],
                        'msgid' => $msg_id
                    ]);
                    //Logger::error($res);
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
                            //Logger::error($send_res);
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
        }else{
            $bot_client->forwardMsgToFriends([
                'robot_wxid' => $robot_wxid,
                'to_wxid' => $to_wxid,
                'msgid' => $msg_id
            ]);
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
        if ($job->attempts() > 2) {
            //通过这个方法可以检查这个任务已经重试了几次了
            $job->delete();
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