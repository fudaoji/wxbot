<?php

/**
 * Created by PhpStorm.
 * Script Name: Test.php
 * Create: 12/20/21 11:49 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\crontab\controller;

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
        //$exist =
        if(count($task_list = model('task')->getAllJoin([
            'alias' => 'bt',
            'join' => [
                ['bot', 'bot.id=bt.bot_id']
            ],
            'where' => ['bt.complete_time' => 0, 'bot.alive' => 1, 'plan_time' => ['elt', time()],
                'bt.status' => 1, 'bt.wxids' => ['neq', ''], 'bt.medias' => ['neq', '']
            ],
            'field' => ['bot.uin', 'bot.app_key', 'bot.admin_id','bot.url', 'bot.protocol','bt.wxids', 'bt.medias', 'bt.id'],
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
