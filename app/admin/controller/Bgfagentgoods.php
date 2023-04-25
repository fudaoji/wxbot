<?php
/**
 * Created by PhpStorm.
 * Script Name: Tenant.php
 * Create: 2023/2/16 14:37
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\controller;

use app\common\model\bgf\AgentGoods as AgentGoodsM;
use app\common\model\bgf\Agent as AgentM;
use app\common\model\bgf\Goods as GoodsM;
use app\common\service\XmlMini;

class Bgfagentgoods extends Bbase
{
    /**
     * @var AgentGoodsM
     */
    protected $model;
    /**
     * @var AgentM
     */
    private $agentM;
    /**
     * @var GoodsM
     */
    private $goodsM;

    public function initialize(){
        parent::initialize();
        $this->model = new AgentGoodsM();
        $this->agentM = new AgentM();
        $this->goodsM = new GoodsM();
    }

    /**
     * 代理商商品
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function index(){
        if(request()->isPost()){
            $post_data = input('post.');
            $where = ['ag.admin_id' => $this->adminInfo['id']];
            !empty($post_data['search_key']) && $where['goods_title'] = ['like', '%'.$post_data['search_key'].'%'];

            $params = [
                'alias' => 'ag',
                'join' => [
                    ['bgf_goods goods', 'ag.goods_id=goods.id', 'left']
                ],
                'refresh' => true
            ];
            $total = $this->model->totalJoin($params);
            if ($total) {
                $list = $this->model->getListJoin(array_merge($params, [
                    'limit' => [$post_data['page'], $post_data['limit']],
                    'order' => ['ag.id' => 'desc'],
                    'field' => ['ag.*']
                ]));
            } else {
                $list = [];
            }

            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $builder = new ListBuilder();
        $builder->setSearch([
            ['type' => 'text', 'name' => 'search_key', 'title' => '搜索词', 'placeholder' => '商品名称']
        ])
            ->addTopButton('addnew', ['title' => '添加商品'])
            ->addTableColumn(['title' => '商品ID', 'field' => 'goods_id'])
            ->addTableColumn(['title' => '商品名称', 'field' => 'goods_title'])
            ->addTableColumn(['title' => '分享图片', 'field' => 'goods_cover', 'type' => 'picture'])
            ->addTableColumn(['title' => 'superID', 'field' => 'super_id'])
            ->addTableColumn(['title' => '添加时间', 'field' => 'create_time', 'type' => 'datetime'])
            ->addTableColumn(['title' => '操作', 'minWidth' => 120, 'type' => 'toolbar'])
            ->addRightButton('edit')
            ->addRightButton('delete');
        return $builder->show();
    }
    public function indexBak(){
        if(request()->isPost()){
            $post_data = input('post.');
            $where = ['ag.admin_id' => $this->adminInfo['id']];
            !empty($post_data['search_key']) && $where['goods_title'] = ['like', '%'.$post_data['search_key'].'%'];

            $params = [
                'alias' => 'ag',
                'join' => [
                    ['bgf_goods goods', 'ag.goods_id=goods.id']
                ],
                'refresh' => true
            ];
            $total = $this->model->totalJoin($params);
            if ($total) {
                $list = $this->model->getListJoin(array_merge($params, [
                    'limit' => [$post_data['page'], $post_data['limit']],
                    'order' => ['ag.id' => 'desc'],
                    'field' => ['ag.*']
                ]));
            } else {
                $list = [];
            }

            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $builder = new ListBuilder();
        $builder->setSearch([
            ['type' => 'text', 'name' => 'search_key', 'title' => '搜索词', 'placeholder' => '商品名称']
        ])
            ->addTopButton('addnew', ['title' => '添加商品'])
            ->addTableColumn(['title' => '序号', 'type' => 'index'])
            ->addTableColumn(['title' => '商品名称', 'field' => 'goods_title'])
            ->addTableColumn(['title' => '添加时间', 'field' => 'create_time', 'type' => 'datetime'])
            ->addTableColumn(['title' => '操作', 'minWidth' => 120, 'type' => 'toolbar'])
            ->addRightButton('edit')
            ->addRightButton('delete');
        return $builder->show();
    }

    /**
     * 添加
     */
    public function add(){
        //使用FormBuilder快速建立表单页面。
        $builder = new FormBuilder();
        $builder->setMetaTitle('新增')  //设置页面标题
            ->setPostUrl(url('savepost')) //设置表单提交地址
            ->addFormItem('template_id', 'chosen', '选择模版', '选择模版', $this->getGoodsList(), 'required')
            ->addFormItem('goods_title', 'text', '商品名称', '也是卡片分享标题，30字内', [], 'required minlength="2" maxlength="30"')
            ->addFormItem('goods_cover', 'picture_url', '商品图片', '商品图片', [], 'required')
            ->addFormItem('goods_id', 'number', '商品ID', '商品ID', [], 'required min=1')
            ->addFormItem('super_id', 'text', 'superID', '多个用英文逗号,隔开', [], 'required');
        return $builder->show();
    }

    private function getGoodsList(){
        //$exists = $this->model->getField('goods_id', ['admin_id' => $this->adminInfo['id']]);
        //['id' => ['notin', count($exists) ? $exists : [0]]]
        return $this->goodsM->getIdToTitle();
    }

    /**
     * 编辑
     */
    public function edit(){
        $id = input('id');
        $data = $this->model->getOne($id);
        if(! $data){
            $this->error('id参数错误');
        }

        //使用FormBuilder快速建立表单页面。
        $builder = new FormBuilder();
        $builder->setMetaTitle('编辑')  //设置页面标题
            ->setPostUrl(url('savepost')) //设置表单提交地址
            ->addFormItem('id', 'hidden', 'id', 'id')
            ->addFormItem('template_id', 'chosen', '选择模版', '选择模版', $this->getGoodsList(), 'required')
            ->addFormItem('goods_title', 'text', '商品名称', '也是卡片分享标题，30字内', [], 'required minlength="2" maxlength="30"')
            ->addFormItem('goods_cover', 'picture_url', '商品图片', '商品图片', [], 'required')
            ->addFormItem('goods_id', 'number', '商品ID', '商品ID', [], 'required min=1')
            ->addFormItem('super_id', 'number', 'superID', 'superID', [], 'required min=1')
            ->setFormData($data);

        return $builder->show();
    }

    public function savePost($jump_to = '/undefined', $data = [])
    {
        $post_data = input('post.');
        if(! $goods = $this->goodsM->getOne($post_data['template_id'])){
            $this->error("模版不存在!");
        }
        $xml_o = new XmlMini($goods['xml']);
        $super_ids = explode(',', $post_data['super_id']);

        foreach ($super_ids as $super_id){
            $xml = str_replace(["<title>".$xml_o->getTitle()."</title>", "<![CDATA[" . $xml_o->getThumbRawUrl(), "goodsId=".$xml_o->getPathParams('goodsId'), "superId=".$xml_o->getPathParams('superId')],
                ["<title>".$post_data['goods_title']."</title>", "<![CDATA[" . $post_data['goods_cover'], "goodsId=".$post_data['goods_id'], "superId=".$super_id], $goods['xml']
            );
            $post_data['super_id'] = $super_id;
            $post_data['xml'] = $xml;
            $post_data['admin_id'] = $this->adminInfo['id'];
            if(empty($post_data['id'])){
                $this->model->addOne($post_data);
            }else{
                $this->model->updateOne($post_data);
            }
        }

        $this->success('操作成功！', '/undefined');
    }
    public function savePostBak($jump_to = '/undefined', $data = [])
    {
        $post_data = input('post.');
        $goods_title = $post_data['goods_title'];
        if(! $goods = $this->goodsM->getOne($post_data['goods_id'])){
            $this->error("商品不存在!");
        }
        $xml_o = new XmlMini($goods['xml']);
        $xml = $goods['xml'];
        //替换标题
        $goods_title && $xml = str_replace("<title>".$xml_o->getTitle()."</title>", "<title>{$goods_title}</title>", $xml);
        //替换superId
        if($agent = $this->agentM->getAgentInfo($this->adminInfo['id'])){
            $xml = str_replace("superId=".$xml_o->getPathParams('superId'), "superId=".$agent['super_id'], $xml);
        }
        $post_data['xml'] = $xml;
        return parent::savePost($jump_to, $post_data);
    }
}