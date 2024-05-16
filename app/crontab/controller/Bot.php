<?php

/**
 * Created by PhpStorm.
 * Script Name: Test.php
 * Create: 12/20/21 11:49 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\crontab\controller;

use app\common\service\Addon as AppService;
use app\constants\Addon;
use app\constants\Task;
use ky\Logger;
use zjkal\ChinaHoliday;

class Bot extends Base
{

    /**
     * @var \app\common\model\Task
     */
    private $taskM;
    /**
     * @var \app\common\model\Moments
     */
    private $momentsM;
    /**
     * @var \app\common\model\MomentsFollow
     */
    private $momentsFollowM;

    public function initialize()
    {
        parent::initialize();
        $this->taskM = new \app\common\model\Task();
        $this->momentsM = new \app\common\model\Moments();
        $this->momentsFollowM = new \app\common\model\MomentsFollow();
    }

    /**
     * 应用的分钟任务
     */
    public function addonMinute()
    {
        //插件新方案执行
        $addons = AppService::listOpenApps('');
        foreach ($addons as $k => $v){
            try {
                $class_name = "\\".config('addon.pathname')."\\".$v['name']."\\crontab\\controller\\Bot";
                if(class_exists($class_name)){
                    $class = new $class_name();
                    if(method_exists($class, 'minuteTask')){
                        $class->minuteTask();
                    }
                }
            }catch (\Exception $e){
                //dump($e->getMessage());
                Logger::error($e->getMessage());
            }
        }

        //插件旧方案
        $addons = Addon::addons();
        foreach ($addons as $k => $v){
            try{
                $class_name = '\\app\\crontab\\task\\' . ucfirst($k);
                if(class_exists($class_name)){
                    $class = new $class_name();
                    if(method_exists($class, 'minuteTask')){
                        $class->minuteTask();
                    }
                }
            }catch (\Exception $e){
                Logger::error($e->getMessage());
            }

        }
    }

    /**
     * 通用定时任务
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function basicMinute(){
        $this->sendBatch();
        $this->sendMoments();
        $this->followMoments();
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
     * 跟圈
     * Author: fudaoji<fdj@kuryun.cn>
     */
    private function followMoments(){
        $list = $this->momentsFollowM->alias('moments')
            ->join('bot bot', 'bot.id=moments.bot_id')
            ->where('moments.status', 1)
            ->where('bot.alive', 1)
            ->field('moments.*')
            //->where("last_time", "<=", time() - 60)
            ->select();
        if(! count($list)){
            return true;
        }

        $delay = 0;
        foreach ($list as $item){
            //放入任务队列
            invoke('\\app\\common\\event\\TaskQueue')->push([
                'delay' => $delay,
                'params' => [
                    'do' => ['\\app\\crontab\\task\\Bot', 'followMoments'],
                    'task' => $item
                ]
            ]);
            $delay += 1;
        }
        var_dump(__FUNCTION__ . count($list));
    }

    /**
     * 发送朋友圈
     * Author: fudaoji<fdj@kuryun.cn>
     */
    private function sendMoments(){
        if(count($list = $this->momentsM->where('status', 1)
            ->whereNotNull('media_id')
            ->where("plan_time", "<=", time())
            ->where("publish_time", "=", 0)
            ->select())){
            foreach ($list as $item){
                $this->momentsM->publishMoments($item);
            }
        }
        var_dump(__FUNCTION__ . ': ' . count($list));
    }

    /**
     * 消息群发
     * Author: fudaoji<fdj@kuryun.cn>
     */
    private function sendBatch(){
        $or_arr = [
            "(complete_time=0 and circle=".Task::CIRCLE_SINGLE." and plan_time <= ".time().")",
            "(circle=" . Task::CIRCLE_DAILY . " and plan_hour<='".date('H:i:s')."' and complete_time<".strtotime(date('Ymd 00:00')).")",
            "(circle=" . Task::CIRCLE_WORKDAY ." and plan_hour<='".date('H:i:s')."' and 1=" . intval(ChinaHoliday::isWorkday(time())) . " AND complete_time<".strtotime(date('Ymd 00:00')).")",
            "(circle=" . Task::CIRCLE_HOLIDAY ." and plan_hour<='".date('H:i:s')."' and 1=" . intval(ChinaHoliday::isHoliday(time())) . " AND complete_time<".strtotime(date('Ymd 00:00')).")",
        ];
        $where = "(complete_time=0 and circle=".Task::CIRCLE_SINGLE." and plan_time <= ".time().") or (circle=" . Task::CIRCLE_DAILY .
            " and plan_hour<='".date('H:i:s')."' and complete_time<".strtotime(date('Ymd 00:00')).")";
        $where = implode(' OR ', $or_arr);
        $view_table = $this->taskM->where('status', 1)
            //->whereNotNull('wxids')
            ->whereNotNull('medias')
            ->where($where)
            ->field('id')
            ->buildSql();

        if(count($task_list = $this->taskM->getAllJoin([
            'alias' => 'bt',
            'join' => [
                ['bot', 'bot.id=bt.bot_id'],
                [$view_table . ' t1', 't1.id=bt.id']
            ],
            'where' => ['bot.alive' => 1],
            'field' => ['bot.uuid', 'bot.uin', 'bot.app_key', 'bot.admin_id', 'bot.staff_id', 'bot.url', 'bot.protocol','bt.wxids', 'bt.medias', 'bt.id','bt.circle',
                'bt.complete_time','bt.atall','bt.member_tags'
            ],
            'refresh' => true
        ]))){
            $redis = get_redis();
            foreach($task_list as $task){
                $rKey = "task" . $task['id'];
                if($redis->get($rKey)){
                    continue;
                }
                //$redis->setex($rKey, 3600, 1);
                //var_dump($task);
                if((!empty($task['member_tags']) || !empty($task['wxids'])) && !empty($task['medias'])){
                    $this->taskM->updateOne(['id' => $task['id'], 'complete_time' => time()]);
                    $medias = json_decode($task['medias'], true);
                    foreach ($medias as $media){
                        $task['media_type'] = $media['type'];
                        $task['media_id'] = $media['id'];
                        $extra = ['atall' => $task['atall']];

                        if(empty($task['wxids'])){
                            $tags = explode(',', $task['member_tags']);
                            $wxids = [];
                            foreach ($tags as $tag){
                                $wxids = array_merge($wxids, model('admin/botMember')->getField('wxid', ['tags' => ['like', '%'.$tag.'%']]));
                            }
                        }else{
                            $wxids = explode(',', $task['wxids']);
                        }
                        $wxids = array_unique($wxids);

                        $delay = 0;
                        foreach ($wxids as $to_wxid){
                            //放入任务队列
                            invoke('\\app\\common\\event\\TaskQueue')->push([
                                'delay' => $delay,
                                'params' => [
                                    'do' => ['\\app\\crontab\\task\\Bot', 'sendMsgBatch'],
                                    'task' => $task,
                                    'reply' => $task,
                                    'to_wxid' => $to_wxid,
                                    'extra' => $extra
                                ]
                            ]);
                            $delay += model('common/setting')->getStepTime();
                        }
                        //model('reply')->botReply($task, $bot_client, $task, $task['wxids'], $extra);
                    }
                }
                $redis->del($rKey);
            }
        }
        var_dump(__FUNCTION__ . ': '. count($task_list) . ' tasks run');
    }
}
