<?php

/**
 * Created by PhpStorm.
 * Script Name: Test.php
 * Create: 12/20/21 11:49 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\crontab\controller;

use app\constants\Task;

class Bot extends Base
{

    public function initialize()
    {
        parent::initialize();
    }

    /**
     * 应用的分钟任务
     */
    public function addonMinute()
    {
        //推品助手
        controller('tpzs', 'task')->minuteTask();
    }

    /**
     * 通用定时任务
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function basicMinute(){
        $this->sendBatch();
    }

    /**
     * 每分钟任务
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function minuteTask(){
        $this->basicMinute();
        $this->addonMinute();
    }

    /**
     * 消息群发
     * Author: fudaoji<fdj@kuryun.cn>
     */
    private function sendBatch(){
        $view_table = model('task')->where('status', 1)
            ->whereNotNull('wxids')
            ->whereNotNull('medias')
            ->where("(complete_time=0 and circle=".Task::CIRCLE_SINGLE." and plan_time <= ".time().") or (circle=" . Task::CIRCLE_DAILY .
                " and plan_hour<='".date('H:i:s')."' and complete_time<".strtotime(date('Ymd 00:00')).")")
            ->field('id')
            ->buildSql();
        if(count($task_list = model('task')->getAllJoin([
            'alias' => 'bt',
            'join' => [
                ['bot', 'bot.id=bt.bot_id'],
                [$view_table . ' t1', 't1.id=bt.id']
            ],
            'where' => ['bot.alive' => 1],
            'field' => ['bot.uuid', 'bot.uin', 'bot.app_key', 'bot.admin_id','bot.url', 'bot.protocol','bt.wxids', 'bt.medias', 'bt.id','bt.circle','bt.complete_time'],
            'refresh' => true
        ]))){
            $redis = get_redis();
            foreach($task_list as $task){
                $rKey = "task" . $task['id'];
                if($redis->get($rKey)){
                    continue;
                }
                $redis->setex($rKey, 600, 1);

                if(!empty($task['wxids']) && !empty($task['medias'])){
                    $bot_client = model('admin/bot')->getRobotClient($task);
                    $medias = json_decode($task['medias'], true);
                    foreach ($medias as $media){
                        $task['media_type'] = $media['type'];
                        $task['media_id'] = $media['id'];
                        model('reply')->botReply($task, $bot_client, $task, $task['wxids']);
                    }
                    $task = model('task')->updateOne(['id' => $task['id'], 'complete_time' => time()]);
                    dump($task);
                }
            }
            dump($task_list);
        }else{
            dump(0);
        }
    }
}