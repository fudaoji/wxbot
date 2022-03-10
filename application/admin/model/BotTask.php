<?php
/**
 * Created by PhpStorm.
 * User: yyp
 * Date: 2022/1/30
 * Time: 14:03
 */

namespace app\admin\model;


use app\common\model\Base;

class BotTask extends Base
{
    //protected $isCache = true;

    /**
     * 修改某单发送时间后，队列重排
     * @param $data
     * @param $old
     * @return bool
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException Author: fudaoji<fdj@kuryun.cn>
     */
    public function afterUpdatePlanTime($data, $old){
        $list = $this->getAll([
            'where' => ['bot_id' => $data['bot_id'], 'status' => 1, 'complete_time' => 0, 'plan_time' => ['gt', $old['plan_time']]],
            'field' => ['id','plan_time'],
            'order' => ['plan_time' => 'asc'],
            'refresh' => true
        ]);
        if(count($list)){
            $step_tasktime = model('botConfig')->getStepTaskTime($data['admin_id']);
            foreach ($list as $k => $v){
                $this->updateOne(['id' => $v['id'], 'plan_time' => $data['plan_time'] + $step_tasktime * ($k + 1)]);
            }
        }
        return true;
    }
}