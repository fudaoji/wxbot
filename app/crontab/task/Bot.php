<?php
/**
 * Created by PhpStorm.
 * Script Name: Bot.php
 * Create: 12/24/21 10:45 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\crontab\task;

use ky\Logger;

class Bot extends Base
{
    public function __construct(){
        parent::__construct();
        set_time_limit(0);
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
        Logger::error(date('Y-m-d H:i:s'));
        model('common/reply')->botReply($task, $client, $reply, $to_wxid, $extra);
        $job->delete();
        dump(date('Y-m-d H:i:s'));
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
        $bot = $params['bot'];
        model('admin/BotMember')->pullFriends($bot);
        model('admin/BotMember')->pullGroups($bot);

        $job->delete();
    }
}