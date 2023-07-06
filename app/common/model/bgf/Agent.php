<?php
/**
 * Created by PhpStorm.
 * Script Name: Agent.php
 * Create: 2023/4/18 14:32
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model\bgf;


class Agent extends Bgf
{
    protected $table = 'agent';

    /**
     * 获取代理商信息
     * @param $id
     * @param string $column
     * @return array|false|mixed|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getAgentInfo($id, $column = '')
    {
        $data = $this->getOneByMap(['admin_id' => $id]);
        return isset($data[$column]) ? $data[$column] : $data;
    }
}