<?php
/**
 * Created by PhpStorm.
 * Script Name: AgentGoods.php
 * Create: 2023/4/19 8:40
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model\bgf;


use app\common\service\XmlMini;

class AgentGoods extends Bgf
{
    protected $table = 'agent_goods';

    /**
     * 组装xml
     * @param array $data
     * @return string|string[]
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function generateXml($data = []){
        $template = $data['template'];
        $xml_o = new XmlMini($template['xml']);
        $super_id = $data['super_id'];
        $xml = str_replace(["<title>".$xml_o->getTitle()."</title>", "<![CDATA[" . $xml_o->getThumbRawUrl(), "goodsId=".$xml_o->getPathParams('goodsId'), "superId=".$xml_o->getPathParams('superId')],
            ["<title>".$data['goods_title']."</title>", "<![CDATA[" . $data['goods_cover'], "goodsId=".$data['goods_id'], "superId=".$super_id], $template['xml']
        );
        return $xml;
    }

    /**
     * 商品列表{id:title, ...}
     * @param array $where
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function getIdToTitle($where = []){
        return $this->getField(['id', 'goods_title'], $where);
    }

    /**
     * 插入数据
     * @param array $data
     * @return array|bool|false|mixed|\PDOStatement|string|\think\Model
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \Exception
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function insertGoods($data = []){
        $template_m = new Goods();
        if(! $template = $template_m->getOneByOrder(['order' => ['id' => 'asc']])){
            throw new \Exception("模版不存在!");
        }
        $xml_o = new XmlMini($template['xml']);

        $super_id = $data['super_id'];
        $xml = str_replace(["<title>".$xml_o->getTitle()."</title>", "<![CDATA[" . $xml_o->getThumbRawUrl(), "goodsId=".$xml_o->getPathParams('goodsId'), "superId=".$xml_o->getPathParams('superId')],
            ["<title>".$data['goods_title']."</title>", "<![CDATA[" . $data['goods_cover'], "goodsId=".$data['goods_id'], "superId=".$super_id], $template['xml']
        );
        $data['xml'] = $xml;

        if($goods = $this->getOneByMap(['goods_id' => $data['goods_id'], 'super_id' => $super_id])){
            $data['id'] = $goods['id'];
            $goods = $this->updateOne($data);
        }else{
            $goods = $this->addOne($data);
        }
        return $goods;
    }
}