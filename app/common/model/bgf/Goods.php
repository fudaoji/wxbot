<?php
/**
 * Created by PhpStorm.
 * Script Name: Agent.php
 * Create: 2023/4/18 14:32
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model\bgf;


use app\common\service\XmlMini;

class Goods extends Bgf
{
    protected $table = 'goods';

    /**
     * 获取默认商品模板
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DbException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function getDefaultTemplate(){
        return $this->getOneByOrder(['order' => ['id' => 'asc']]);
    }

    /**
     * 商品列表{id:title, ...}
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function getIdToTitle($where = []){
        $where['status'] = 1;
        $list = $this->getAll([
            'where' => $where,
            'order' => ['id' => 'desc'],
            'field' => ['id', 'title']
        ]);
        $goods_list = [];
        foreach ($list as $v){
            /*$xml = new XmlMini($v['xml']);
            $title = $xml->getTitle();*/
            $goods_list[$v['id']] = $v['title'];
        }
        return $goods_list;
    }
}