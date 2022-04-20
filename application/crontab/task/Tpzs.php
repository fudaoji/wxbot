<?php
/**
 * Created by PhpStorm.
 * Script Name: Bot.php
 * Create: 12/24/21 10:45 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\crontab\task;

use app\common\model\tpzs\Task;
use ky\Bot\Vlw;
use ky\Bot\Wxwork;

class Tpzs extends Base
{
    public function __construct(){
        parent::__construct();
        set_time_limit(0);
    }

    /**
     * 定时任务
     * @return string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function minuteTask(){
        $task_m = new Task();
        if(count($task_list = $task_m->getAllJoin([
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
                $task_m->updateOne(['id' => $task['id'], 'complete_time' => time()]);
            }
            dump("num:" . count($task_list));
        }else{
            dump(0);
        }
    }
}