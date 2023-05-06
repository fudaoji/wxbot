<?php
/**
 * Created by PhpStorm.
 * Script Name: Bgf.php
 * Create: 12/24/21 10:45 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */
namespace app\crontab\task;

use app\common\model\bgf\Agent;
use app\common\model\bgf\AgentGoods;
use app\common\model\bgf\Config;
use app\common\model\bgf\Goods;
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
    /**
     * @var Agent
     */
    private $agentM;
    /**
     * @var AgentGoods
     */
    private $agentGoodsM;

    public function __construct(){
        parent::__construct();
        $this->taskM = new Task();
        $this->configM = new Config();
        $this->agentM = new Agent();
        $this->agentGoodsM = new AgentGoods();
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
                ['bot', 'bot.id=bt.bot_id']
            ],
            'where' => ['bt.complete_time' => 0, 'bot.alive' => 1, 'plan_time' => ['<=', time()]],
            'field' => ['uuid','bot.uin as robot_wxid', 'bot.app_key', 'bot.url', 'bot.protocol','super_ids', 'goods_title', 'goods_cover', 'goods_id','bt.id']
        ])) && $template = app()->make(Goods::class)->getDefaultTemplate()){
            foreach($task_list as $task){
                $this->taskM->updateOne(['id' => $task['id'], 'complete_time' => time()]);
                if(empty($task['super_ids'])){
                    continue;
                }
                $supers = explode(',', $task['super_ids']);
                $delay = 0;
                foreach ($supers as $super_id){
                    $agent = $this->agentM->getOneByMap(['super_id' => $super_id]);
                    if(empty($agent['groups'])){
                        continue;
                    }
                    $task['xml'] = $this->agentGoodsM->generateXml([
                        'template' => $template,
                        'super_id' => $super_id,
                        'goods_id' => $task['goods_id'],
                        'goods_title' => $task['goods_title'],
                        'goods_cover' => $task['goods_cover']
                    ]);

                    $wxids = explode(',', $agent['groups']);
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
        var_dump(__CLASS__ . ':'.__FUNCTION__ . ":" . count($task_list));
    }

    public function minuteTaskBak(){
        if(! $opens = $this->configM->getField('admin_id', ['key' => 'switch', 'value' => 1])){
            return  true;
        }

        if(count($task_list = $this->taskM->getAllJoin([
            'alias' => 'bt',
            'join' => [
                ['bot', 'bot.id=bt.bot_id'],
                ['bgf_agent_goods goods', 'goods.id=bt.goods_id']
            ],
            'where' => [/*'bt.admin_id' => ['in', $opens], */'bt.complete_time' => 0, 'bot.alive' => 1, 'plan_time' => ['<=', time()]],
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