<?php
/**
 * Created by PhpStorm.
 * Script Name: Bot.php
 * Create: 12/24/21 10:45 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\task;


use ky\Bot\Vlw;
use ky\Bot\Wxwork;
use think\Db;
use think\facade\Log;

class Bot extends Base
{
    public function __construct(){
        parent::__construct();
        set_time_limit(0);
    }

    /**
     * 定时任务
     * @return string
     */
    public function runTask(){
        if(count($task_list = model('admin/botTask')->getAllJoin([
            'alias' => 'bt',
            'join' => [
                ['bot', 'bot.id=bt.bot_id']
            ],
            'where' => ['bt.complete_time' => 0, 'bot.alive' => 1, 'plan_time' => ['elt', time()]],
            'field' => ['bot.uin as wxid', 'bot.app_key', 'bot.url', 'bot.protocol','bt.members', 'bt.img', 'bt.content', 'bt.id']
        ]))){
            foreach($task_list as $task){
                /**
                 * @var $bot_client Vlw|Wxwork
                 */
                $bot_client = model('admin/bot')->getRobotClient($task);
                if(!empty($task['img'])){
                    $bot_client->sendImgToFriends(['robot_wxid' => $task['wxid'], 'to_wxid' => $task['members'], 'path' => $task['img']]);
                    sleep(3);
                }
                if(!empty($task['content'])){
                    $bot_client->sendTextToFriends(['robot_wxid' => $task['wxid'], 'to_wxid' => $task['members'], 'msg' => $task['content']]);
                }
                model('admin/botTask')->updateOne(['id' => $task['id'], 'complete_time' => time()]);
            }
            dump("num:" . count($task_list));
        }else{
            dump(0);
        }
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