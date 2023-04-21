<?php
/**
 * Created by PhpStorm.
 * Script Name: Bgf.php
 * Create: 12/24/21 10:45 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */
namespace app\crontab\task;

use app\common\model\bgf\Config;
use app\common\model\bgf\Task;
use ky\WxBot\Driver\My;

class Bgf extends Base
{
    /**
     * @var Task
     */
    private $taskM;
    /**
     * @var Config
     */
    private $configM;

    public function __construct(){
        parent::__construct();
        $this->taskM = new Task();
        $this->configM = new Config();
    }

    /**
     * 定时任务
     * @return string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function minuteTask(){
        if(! $opens = $this->configM->getField('admin_id', ['key' => 'switch', 'value' => 1])){
            return  true;
        }

        if(count($task_list = $this->taskM->getAllJoin([
            'alias' => 'bt',
            'join' => [
                ['bot', 'bot.id=bt.bot_id'],
                ['bgf_agent_goods goods', 'goods.goods_id=bt.goods_id']
            ],
            'where' => ['bt.admin_id' => ['in', $opens], 'bt.complete_time' => 0, 'bot.alive' => 1, 'plan_time' => ['<=', time()]],
            'field' => ['uuid','bot.uin as robot_wxid', 'bot.app_key', 'bot.url', 'bot.protocol','bt.wxids', 'goods.xml', 'bt.id']
        ]))){
            foreach($task_list as $task){
                if(!empty($task['wxids']) && !empty($task['xml'])){
                    $this->taskM->updateOne(['id' => $task['id'], 'complete_time' => time()]);
                    $wxids = explode(',', $task['wxids']);
                    $delay = 0;
                    foreach ($wxids as $to_wxid){
                        //放入任务队列
                        invoke('\\app\\common\\event\\TaskQueue')->push([
                            'delay' => $delay,
                            'params' => [
                                'do' => ['\\app\\crontab\\task\\Bgf', 'sendMsg'],
                                'task' => $task,
                                'to_wxid' => $to_wxid
                            ]
                        ]);
                        $delay += model('common/setting')->getStepTime();
                    }
                }
            }
        }
        var_dump(__FUNCTION__ . ":" . count($task_list));
    }

    public function sendMsg($params = []){
        /**
         * @var \think\queue\Job
         */
        $job = $params['job'];
        if ($job->attempts() > 2) {
            $job->delete();
        }
        $task = $params['task'];
        $to_wxid = $params['to_wxid'];
        /**
         * @var $bot_client My
         */
        $bot_client = model('admin/bot')->getRobotClient($task);
        $res = $bot_client->sendXmlToFriends([
            'robot_wxid' => $task['robot_wxid'],
            'to_wxid' => $to_wxid,
            'xml' => $task['xml']
        ]);
        if($task['protocol'] == 'my'){
            //var_dump($bot_client->getRobotList());
        }
        $job->delete();
    }
}