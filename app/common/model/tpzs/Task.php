<?php
namespace app\common\model\tpzs;

class Task extends Tpzs
{
    //protected $isCache = true;
    protected $table = 'task';

    const TYPE_BASIC = 'basic';
    const TYPE_CK = 'ck';
    const TYPE_JD = 'jd';

    public static function types($id = null){
        $list = [
            self::TYPE_BASIC => '普通',
            self::TYPE_JD => '京东联盟',
            //self::TYPE_CK => '创客店铺'
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }

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
            'where' => ['bot_id' => $data['bot_id'], 'status' => 1, 'complete_time' => 0, 'plan_time' => ['>', $old['plan_time']]],
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