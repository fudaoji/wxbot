<?php
/**
 * Created by PhpStorm.
 * Script Name: Bot.php
 * Create: 12/24/21 10:45 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\crontab\task;

use app\common\service\BotMember;
use app\common\service\JsfTask;
use app\constants\Pyq;
use ky\Logger;
use ky\WxBot\Driver\Extian;

class Bot extends Base
{
    public function __construct(){
        parent::__construct();
        set_time_limit(0);
    }

    /**
     * 清理僵尸粉
     * @param array $params
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function clearZombie($params = []){
        /**
         * @var \think\queue\Job
         */
        $job = $params['job'];
        if ($job->attempts() > 1) {
            $job->delete();
        }
        $task = $params['task'];
        /**
         * @var $client Extian
         */
        $client = model('admin/bot')->getRobotClient($task);
        $wxid = $params['wxid'];
        $total = $params['total'];
        $index = $params['index'];

        $res = $client->checkIsFriend(['wxid' => $wxid]);
        if(!empty($res['data']['result'])){
            $t = JsfTask::model()->getOne($task['id']);
            $arr = explode(',', $t['wxids']);
            $arr[] = $wxid;
            $arr = array_unique($arr);
            $update = ['id' => $t['id'], 'wxids' => trim(implode(',', $arr), ',')];
            if($index >= $total){
                $update['complete_time'] = time() + 5;
                $update['status'] = JsfTask::STATUS_OVER;
            }
            JsfTask::model()->updateOne($update);
            //Logger::error($wxid.'是僵尸粉');
        }else{
            //Logger::error($wxid.'是好友');
        }
        $job->delete();
    }

    /**
     * 跟圈
     * @param array $params
     * Author: fudaoji<fdj@kuryun.cn>
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function followMoments($params = []){
        /**
         * @var \think\queue\Job
         */
        $job = $params['job'];
        if ($job->attempts() > 2) {
            $job->delete();
        }
        $task = $params['task'];
        $bot_info = model('admin/bot')->getOne($task['bot_id']);
        $client = model('admin/bot')->getRobotClient($bot_info);
        $wxid_arr = explode(',', $task['wxids']);
        foreach ($wxid_arr as $wxid){
            $res = $client->getFriendMoments([
                'robot_wxid' => $bot_info['uin'],
                'to_wxid' => $wxid,
                'num' => 1
            ]);
            if(empty($res['data'])){
                continue;
            }

            $list = $res['data'];
            foreach ($list as $item){
                $timeline = Pyq::decodeObject($item['object']);
                if(strtotime($timeline['create_time']) > $task['last_time']){
                    $client->sendMomentsXml([
                        'robot_wxid' => $bot_info['uin'],
                        'xml' => $item['object']
                    ]);
                }
            }
        }

        model('momentsFollow')->updateOne(['id' => $task['id'], 'last_time' => time()]);
        $job->delete();
        dump(date('Y-m-d H:i:s'));
    }


    /**
     * 发送消息
     * @param array $params
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sendMsgBatch($params = []){
        /**
         * @var \think\queue\Job
         */
        $job = $params['job'];
        if ($job->attempts() > 2) {
            $job->delete();
        }
        $task = $params['task'];
        $client = model('admin/bot')->getRobotClient($task);
        $reply = $params['reply'];
        $to_wxid = $params['to_wxid'];
        $extra = $params['extra'];
        //Logger::error(date('Y-m-d H:i:s') . '   ' . $to_wxid);
        model('common/reply')->botReply($task, $client, $reply, $to_wxid, $extra);
        $job->delete();
    }

    /**
     * 下拉通讯录
     * @param $params
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function pullMembers($params){
        /**
         * @var \think\queue\Job
         */
        $job = $params['job'];
        if ($job->attempts() > 2) {
            $job->delete();
        }
        $bot = $params['bot'];
        if(! BotMember::model()->total(['uin' => $bot['uin']], true)) {
            model('admin/BotMember')->pullFriends($bot);
            model('admin/BotMember')->pullGroups($bot);
        }
        $job->delete();
    }

    /**
     * 下拉群成员
     * @param $params
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function pullGroupMembers($params){
        /**
         * @var \think\queue\Job
         */
        $job = $params['job'];
        if ($job->attempts() > 2) {
            $job->delete();
        }
        $bot = $params['bot'];
        $group = $params['group'];
        model('admin/BotGroupmember')->pullMembers($bot, $group);

        $job->delete();
    }
}