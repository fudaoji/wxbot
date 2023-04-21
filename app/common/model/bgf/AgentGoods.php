<?php
/**
 * Created by PhpStorm.
 * Script Name: AgentGoods.php
 * Create: 2023/4/19 8:40
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model\bgf;


class AgentGoods extends Bgf
{

    protected $table = 'agent_goods';

    /**
     * 商品列表{id:title, ...}
     * @param array $where
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function getIdToTitle($where = []){
        return $this->getField(['goods_id', 'goods_title'], $where);
    }
}