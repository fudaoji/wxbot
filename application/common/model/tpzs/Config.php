<?php
/**
 * Created by PhpStorm.
 * User: yyp
 * Date: 2022/1/30
 * Time: 14:03
 */

namespace app\common\model\tpzs;

class Config extends Tpzs
{
    protected $isCache = true;
    protected $table = 'config';

    /**
     * 全局设置
     * @param array $where
     * @param string $key
     * @param int $refresh
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function getConf($where = [], $key = '', $refresh = 0){
        $list = $this->getField(['key', 'value'], $where, ['refresh' => $refresh]);
        if(!empty($key)){
            return isset($list[$key]) ? $list[$key] : '';
        }
        return $list;
    }

    /**
     * 获取间隔发送时间
     * @param int $admin_id
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getStepTaskTime($admin_id = 0){
        if(! $step_tasktime = $this->getConf(['admin_id' => $admin_id], 'step_tasktime')){
            $step_tasktime = config('system.send.step_tasktime');
        }
        return $step_tasktime;
    }
}