<?php
/**
 * Created by PhpStorm.
 * Script Name: Zdjr.php
 * Create: 2022/9/7 11:55
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\crontab\task;

use app\common\model\zdjr\Block;
use app\common\model\zdjr\Clue;
use app\common\model\zdjr\Log;
use app\common\model\zdjr\Rule;
use app\admin\model\Bot;
use app\constants\Bot as BotConst;

class Zdjr extends Base
{
    /**
     * @var Rule
     */
    private $ruleM;
    /**
     * @var Clue
     */
    private $clueM;
    /**
     * @var Block
     */
    private $blockM;
    /**
     * @var Bot
     */
    private $botM;
    /**
     * @var Log
     */
    private $logM;

    public function __construct(){
        parent::__construct();
        $this->ruleM = new Rule();
        $this->clueM = new Clue();
        $this->blockM = new Block();
        $this->botM = new Bot();
        $this->logM = new Log();
    }

    /**
     * 定时任务
     * @return string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function minuteTask(){
        $tasks = $this->ruleM->getAll([
            'status' => 1
        ]);
        dump($tasks);
        foreach ($tasks as $task){
            $rules = json_decode($task['rules'], true);

            if(! $flag = $this->ruleM->canRun(['rule_id' => $task['id'], 'time_round' => $rules['time_round']])){
                continue; //每轮休息期间
            }

            if(! $bots = $this->blockM->getSaveBots(['admin_id' => $task['admin_id'], 'bots' => $task['bots']])){
                continue; //无机器人可用
            }
            foreach ($bots as $bot_id){
                if(! $clues = $this->clueM->getList([1, $rules['speed']],
                    ['admin_id' => $task['admin_id'], 'status' => 1, 'step' => Clue::STEP_NOT, 'project_id' => $task['project_id']]
                )){
                    break; //无线索
                }
                dump($clues);
                $bot = $this->botM->getOne($bot_id);
                $bot_client = $this->botM->getRobotClient($bot);
                foreach ($clues as $clue){
                    //判断是否触发每日好友申请上限
                    if(! $this->logM->checkLimit(['bot_id' => $bot['id'], 'admin_id' => $bot['admin_id']])){
                        break; //切换下个机器人
                    }
                    // 状态码 -1: 未知内容 0: 搜索成功 1: 找不到相关帐号 2: 对方已隐藏账号 3: 操作频繁 4: 用户不存在 5: 用户异常
                    $res_se = $bot_client->searchAccount([
                        'robot_wxid' => $bot['uin'],
                        'content' => $clue['content']
                    ]);

                    dump("================查找结果=========");
                    dump($res_se);

                    $clue_update = [
                        'id' => $clue['id'],
                        'bot_id' => $bot['id'],
                        'step' => Clue::STEP_FAILED
                    ];
                    if($res_se['code']){
                        //record log
                        $this->logM->addOne([
                            'admin_id' => $task['admin_id'],
                            'bot_id' => $bot['id'],
                            'rule_id' => $task['id'],
                            'clue_id' => $clue['id'],
                            'res' => $res_se['data']['status']
                        ]);

                        switch ($res_se['data']['status']){
                            case 3:
                                $this->blockM->blockBot([
                                    'admin_id' => $task['admin_id'],
                                    'bots' => $task['bots'],
                                    'bot_id' => $bot['id']
                                ]);
                                $clue_update['step'] = Clue::STEP_NOT;
                                break;
                            case 0:
                                $clue_update['wxid'] = $res_se['data']['wxid'];
                                $clue_update['nickname'] = filter_emoji($res_se['data']['nickname']);

                                $add_msg = str_replace('[名称]', $clue['title'], $rules['add_msg']);
                                
                                $res_add = $bot_client->addFriendBySearch([
                                    'robot_wxid' => $bot['uin'],
                                    'v1' => $res_se['data']['v1'],
                                    'v2' => $res_se['data']['v2'],
                                    'msg' => $add_msg,
                                    'scene' => BotConst::SCENE_WXNUM
                                ]);
                                if($res_add['code']){
                                    $clue_update['step'] = Clue::STEP_APPLIED;
                                    dump("================添加结果=========");
                                    dump($res_add);

                                    //添加日志
                                    $this->logM->addOne([
                                        'admin_id' => $task['admin_id'],
                                        'bot_id' => $bot['id'],
                                        'rule_id' => $task['id'],
                                        'clue_id' => $clue['id'],
                                        'res' => $res_se['data']['status'],
                                        'type' => 2
                                    ]);
                                }
                                break;
                        }
                    }
                    //update clue
                    $clue = $this->clueM->updateOne($clue_update);
                    dump("================线索更新=========");
                    dump($clue);
                    //sleep
                    $this->ruleM->sleep($rules);
                }
            }
        }
    }
}