<?php

/**
 * Created by PhpStorm.
 * Script Name: Bgftask.php
 * Create: 2020/5/24 上午10:25
 * Description: 定时任务
 * Author: fudaoji<fdj@kuryun.cn>
 */
namespace app\admin\controller;

use app\common\model\bgf\Agent;
use app\common\model\bgf\AgentGoods;
use app\common\model\bgf\Goods;
use app\common\model\bgf\Task;
use app\constants\Media;

class Bgftask extends Botbase
{
    protected $needBotId = true;
    /**
     * @var Task
     */
    protected $model;
    /**
     * @var array
     */
    private $tabList;

    /**
     * @var AgentGoods
     */
    private $agentGoodsM;
    /**
     * @var Agent
     */
    private $agentM;
    /**
     * @var Goods
     */
    private $goodsM;

    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->model = new Task();
        $this->agentGoodsM = new AgentGoods();
        $this->agentM = new Agent();
        $this->goodsM = new Goods();
        $this->tabList = [
            'todo' => [
                'title' => '待发送',
                'href' => url('index', ['name' => 'todo'])
            ],
            'done' => [
                'title' => '已发送',
                'href' => url('index', ['name' => 'done'])
            ],
        ];
    }

    public function index()
    {
        $name = input('name', 'todo');
        if (request()->isPost()) {
            $post_data = input('post.');
            $where = ['bot_id' => $this->bot['id']];
            !empty($post_data['search_key']) && $where['remark|goods_title'] = ['like', '%' . $post_data['search_key'] . '%'];
            //isset($post_data['complete']) && $post_data['complete'] >= 0 && $where['complete_time'] = $post_data['complete'] == 0 ? 0 : ['>', 0];
            if($name == 'todo'){
                $where['complete_time'] = 0;
                $order = ['plan_time' => 'asc'];
            }else{
                $where['complete_time'] = ['>', 0];
                $order = ['complete_time' => 'desc'];
            }

            $params = [
                'alias' => 'task',
                'join' => [
                    ['bgf_agent_goods goods', 'goods.id=task.goods_id', 'left']
                ],
                'where' => $where,
                'refresh' => true
            ];
            $total = $this->model->totalJoin($params);
            if ($total) {
                $list = $this->model->getListJoin(array_merge($params, [
                    'limit' => [$post_data['page'], $post_data['limit']],
                    'order' => $order,
                    'field' => ['task.*', 'task.goods_title']
                ]));
                foreach ($list as $k => $v){
                    $ids = explode(',', $v['super_ids']);
                    if($member = $this->agentM->getOneByMap([
                        'super_id' => $ids[0]
                    ])){
                        $v['supers'] = $member['title'];
                        if(count($ids) > 1){
                            $v['supers'] .= "等".count($ids)."个代理";
                        }
                    }else{
                        $v['supers'] = "--";
                    }
                    $list[$k] = $v;
                }
            }else{
                $list = [];
            }

            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $builder = new ListBuilder();
        $builder->setSearch([
            //['type' => 'select', 'name' => 'complete', 'title' => '发送状态', 'options' => [-1 => '全部', 0 => '未发送', 1 => '已发送']],
            ['type' => 'text', 'name' => 'search_key', 'title' => '关键词', 'placeholder' => '内容']
        ])
            ->setTabNav($this->tabList, $name)
            ->setDataUrl(url('index', ['name' => $name]))
            //->addTopButton('addnew', ['title' => '快速添加', 'href' => url('quickAdd')])
            ->addTopButton('addnew', ['title' => '添加任务'])
            ->addTableColumn(['title' => '发送顺序', 'field' => 'id', 'type' => 'index'])
            ->addTableColumn(['title' => '计划发送时间', 'field' => 'plan_time', 'minWidth' => 180, 'type' => 'datetime'])
            ->addTableColumn(['title' => '商品名称', 'field' => 'goods_title', 'minWidth' => 100])
            ->addTableColumn(['title' => '代理人名称', 'field' => 'supers', 'minWidth' => 200])
            ->addTableColumn(['title' => '备注', 'field' => 'remark', 'minWidth' => 60]);
        if($name == 'done'){
            $builder->addTableColumn(['title' => '完成时间', 'field' => 'complete_time', 'minWidth' => 180, 'type' => 'datetime']);
        }else{
            $builder->addTableColumn(['title' => '是否开启', 'field' => 'status', 'minWidth' => 70, 'type' => 'switch', 'options' => [0 => '停止', 1 => '开启']])
                ->addTableColumn(['title' => '操作', 'minWidth' => 120, 'type' => 'toolbar'])
                ->addRightButton('edit')
                ->addRightButton('delete');
        }

        return $builder->show();
    }

    /**
     * 新增任务
     * @return mixed
     * @throws \think\db\exception\DbException
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function add(){
        $id2title = $this->listGoodsTemplates();
        if($this->request->isPost()){
            $post_data = input('post.');
            if(! empty($post_data['goods_id'])){
                //$this->error('请选择要发送的商品!');

                if($goods = $this->goodsM->getOne($post_data['goods_id'])){
                    $post_data['goods_title'] = $goods['title'];
                }
            }

            if(!empty($post_data['media_id_type']) && count($post_data['media_id_type']) > 0){
                $medias = [];
                foreach ($post_data['media_id_type'] as $id_type){
                    list($id, $type) = explode('_', $id_type);
                    $medias[] = ['id' => $id, 'type' => $type];
                }
                $post_data['medias'] = json_encode($medias, JSON_UNESCAPED_UNICODE);
                unset($post_data['media_id_type']);
            }

            $plan_time = empty($post_data['plan_time']) ? time() : strtotime($post_data['plan_time']);
            $plan_time_arr = [$plan_time];
            !empty($post_data['plan_time1']) && $plan_time_arr[] = strtotime($post_data['plan_time1']);
            !empty($post_data['plan_time2']) && $plan_time_arr[] = strtotime($post_data['plan_time2']);
            $post_data['bot_id'] = $this->bot['id'];
            $post_data['admin_id'] = $this->adminInfo['id'];
            unset($post_data['__token__'], $post_data['plan_time1'], $post_data['plan_time2']);
            foreach ($plan_time_arr as $plan_time){
                $post_data['plan_time'] = $plan_time;
                $this->model->addOne($post_data);
            }
            $this->success('操作成功！', '/undefined');
        }

        $data = [];
        $supers = $this->agentM->getField(['super_id', 'title'], ['staff_id' => $this->adminInfo['id']]);
        $last_one = $this->model->getOneByOrder([
            'where' => ['admin_id' => $this->adminInfo['id'], 'bot_id' => $this->bot['id']],
            'field' => 'super_ids',
            'order' => ['id' => 'desc']
        ]);
        if(!empty($last_one['super_ids'])){
            $data['super_ids'] = explode(',', $last_one['super_ids']);
        }else{
            $data['super_ids'] = array_keys($supers);
        }

        $builder = new FormBuilder();
        $builder->setPostUrl(url('add'))
            ->addFormItem('media', 'choose_media_multi', '介绍内容', '素材顺序决定推送顺序', ['types' => Media::types()])
            ->addFormItem('goods_id', 'chosen', '选择商品', '选择商品', $id2title)
            ->addFormItem('super_ids', 'chosen_multi', '选择代理商', '选择代理商', $supers, 'required')
            ->addFormItem('plan_time', 'datetime', '发送时间1', '不填则取当前时间', [], '')
            ->addFormItem('plan_time1', 'datetime', '发送时间2', '不填则不生效', [], '')
            ->addFormItem('plan_time2', 'datetime', '发送时间3', '不填则不生效', [], '')
            ->addFormItem('remark', 'text', '备注', '')
            ->setFormData($data);
        return $builder->show();
    }

    public function edit(){
        $id = input('id', 0, 'intval');
        if(!$data = $this->model->getOne($id)){
            $this->error('数据不存在');
        }

        if($this->request->isPost()){
            $post_data = input('post.');
            if(!empty($post_data['goods_id']) && $goods = $this->goodsM->getOne($post_data['goods_id'])){
                $post_data['goods_title'] = $goods['title'];
            }

            $post_data['plan_time'] = empty($post_data['plan_time']) ? time() : strtotime($post_data['plan_time']);

            if(!empty($post_data['media_id_type']) && count($post_data['media_id_type']) > 0){
                $medias = [];
                foreach ($post_data['media_id_type'] as $id_type){
                    list($id, $type) = explode('_', $id_type);
                    $medias[] = ['id' => $id, 'type' => $type];
                }
                $post_data['medias'] = json_encode($medias, JSON_UNESCAPED_UNICODE);
                unset($post_data['media_id_type']);
            }

            return parent::savePost('/undefined', $post_data);
        }

        $id2title= $this->listGoodsTemplates();
        $supers = $this->agentM->getField(['super_id', 'title'], ['staff_id' => $this->adminInfo['id']]);
        $data['super_ids'] = explode(',', $data['super_ids']);

        $materials = [];
        if($data['medias']){
            $data['medias'] = json_decode($data['medias'], true);
            foreach ($data['medias'] as $item){
                $m = model('media_' . $item['type'])->getOneByMap([
                    'admin_id' => $data['admin_id'],
                    'id' => $item['id']
                ], true, true);
                $m['type'] = $item['type'];
                $materials[] = $m;
            }
        }

        $builder = new FormBuilder();
        $builder->setPostUrl(url('edit'))
            ->addFormItem('id', 'hidden', 'id', 'id')
            ->addFormItem('media', 'choose_media_multi', '介绍内容', '素材顺序决定推送顺序', ['types' => Media::types(), 'materials' => $materials])
            ->addFormItem('goods_id', 'chosen', '选择商品', '选择商品', $id2title)
            ->addFormItem('super_ids', 'chosen_multi', '选择代理商', '选择代理商', $supers, 'required')
            ->addFormItem('plan_time', 'datetime', '发送时间', '不填则根据设置的发单间隔时间确定', [], '')
            ->addFormItem('remark', 'text', '备注', '')
            ->setFormData($data);
        return $builder->show();
    }

    public function add230530(){
        $list = $this->pullGoodsList();
        if($this->request->isPost()){
            $post_data = input('post.');
            if(empty($post_data['goods_id'])){
                $this->error('请选择要发送的商品!');
            }
            $goods_id = $post_data['goods_id'];
            $goods_title = '';
            $goods_cover = '';
            foreach ($list as $item){
                if($item['goodsId'] == $goods_id){
                    $goods_title = $item['goodsTitle'];
                    $goods_cover = $item['shareImage'];
                    break;
                }
            }

            if(count($post_data['media_id_type']) > 0){
                $medias = [];
                foreach ($post_data['media_id_type'] as $id_type){
                    list($id, $type) = explode('_', $id_type);
                    $medias[] = ['id' => $id, 'type' => $type];
                }
                $post_data['medias'] = json_encode($medias, JSON_UNESCAPED_UNICODE);
                unset($post_data['media_id_type']);
            }

            $plan_time = empty($post_data['plan_time']) ? time() : strtotime($post_data['plan_time']);
            $plan_time_arr = [$plan_time];
            !empty($post_data['plan_time1']) && $plan_time_arr[] = strtotime($post_data['plan_time1']);
            !empty($post_data['plan_time2']) && $plan_time_arr[] = strtotime($post_data['plan_time2']);
            $post_data['bot_id'] = $this->bot['id'];
            $post_data['admin_id'] = $this->adminInfo['id'];
            $post_data['goods_title'] = $goods_title;
            $post_data['goods_cover'] = $goods_cover;
            unset($post_data['__token__'], $post_data['plan_time1'], $post_data['plan_time2']);
            foreach ($plan_time_arr as $plan_time){
                $post_data['plan_time'] = $plan_time;
                $this->model->addOne($post_data);
            }
            $this->success('操作成功！', '/undefined');
        }
        $id2title= [];
        foreach ($list as $item){
            $id2title[$item['goodsId']] = $item['goodsTitle'];
        }
        $data = [];
        $supers = $this->agentM->getField(['super_id', 'title'], ['staff_id' => $this->adminInfo['id']]);
        $last_one = $this->model->getOneByOrder([
            'where' => ['admin_id' => $this->adminInfo['id'], 'bot_id' => $this->bot['id']],
            'field' => 'super_ids',
            'order' => ['id' => 'desc']
        ]);
        if(!empty($last_one['super_ids'])){
            $data['super_ids'] = explode(',', $last_one['super_ids']);
        }else{
            $data['super_ids'] = array_keys($supers);
        }

        //$groups = model('admin/botMember')->getField('wxid,nickname',['uin' => $this->bot['uin']]);
        $builder = new FormBuilder();
        $builder->setPostUrl(url('add'))
            ->addFormItem('media', 'choose_media_multi', '介绍内容', '素材顺序决定推送顺序', ['types' => Media::types()])
            ->addFormItem('goods_id', 'chosen', '选择商品', '选择商品', $id2title, 'required')
            ->addFormItem('super_ids', 'chosen_multi', '选择代理商', '选择代理商', $supers, 'required')
            ->addFormItem('plan_time', 'datetime', '发送时间1', '不填则取当前时间', [], '')
            ->addFormItem('plan_time1', 'datetime', '发送时间2', '不填则不生效', [], '')
            ->addFormItem('plan_time2', 'datetime', '发送时间3', '不填则不生效', [], '')
            ->addFormItem('remark', 'text', '备注', '')
            ->setFormData($data);
        return $builder->show();
    }

    /**
     * 编辑任务
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\Exception
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function edit230530(){
        $id = input('id', 0, 'intval');
        if(!$data = $this->model->getOne($id)){
            $this->error('数据不存在');
        }
        $list = $this->pullGoodsList();
        if($this->request->isPost()){
            $post_data = input('post.');
            $goods_id = $post_data['goods_id'];
            $goods_title = '';
            $goods_cover = '';
            foreach ($list as $item){
                if($item['goodsId'] == $goods_id){
                    $goods_title = $item['goodsTitle'];
                    $goods_cover = $item['shareImage'];
                    break;
                }
            }
            $post_data['goods_title'] = $goods_title;
            $post_data['goods_cover'] = $goods_cover;
            $post_data['plan_time'] = empty($post_data['plan_time']) ? time() : strtotime($post_data['plan_time']);

            if(count($post_data['media_id_type']) > 0){
                $medias = [];
                foreach ($post_data['media_id_type'] as $id_type){
                    list($id, $type) = explode('_', $id_type);
                    $medias[] = ['id' => $id, 'type' => $type];
                }
                $post_data['medias'] = json_encode($medias, JSON_UNESCAPED_UNICODE);
                unset($post_data['media_id_type']);
            }

            return parent::savePost('/undefined', $post_data);
        }
        $id2title= [];
        foreach ($list as $item){
            $id2title[$item['goodsId']] = $item['goodsTitle'];
        }
        $supers = $this->agentM->getField(['super_id', 'title'], ['staff_id' => $this->adminInfo['id']]);
        $data['super_ids'] = explode(',', $data['super_ids']);

        $materials = [];
        if($data['medias']){
            $data['medias'] = json_decode($data['medias'], true);
            foreach ($data['medias'] as $item){
                $m = model('media_' . $item['type'])->getOneByMap([
                    'admin_id' => $data['admin_id'],
                    'id' => $item['id']
                ], true, true);
                $m['type'] = $item['type'];
                $materials[] = $m;
            }
        }

        $builder = new FormBuilder();
        $builder->setPostUrl(url('edit'))
            ->addFormItem('id', 'hidden', 'id', 'id')
            ->addFormItem('media', 'choose_media_multi', '介绍内容', '素材顺序决定推送顺序', ['types' => Media::types(), 'materials' => $materials])
            ->addFormItem('goods_id', 'chosen', '选择商品', '选择商品', $id2title, 'required')
            ->addFormItem('super_ids', 'chosen_multi', '选择代理商', '选择代理商', $supers, 'required')
            ->addFormItem('plan_time', 'datetime', '发送时间', '不填则根据设置的发单间隔时间确定', [], '')
            ->addFormItem('remark', 'text', '备注', '')
            ->setFormData($data);
        return $builder->show();
    }

    /*public function savePost($jump = '', $data = []){
        $data = input('post.');
        $plan_time = empty($data['plan_time']) ? time() : strtotime($data['plan_time']);
        $data['plan_time'] = $plan_time;
        if(empty($data['goods_id'])){
            $this->error('请选择要发送的商品!');
        }
        $data['bot_id'] = $this->bot['id'];
        return parent::savePost($jump, $data);
    }*/

    /**
     * get goods list from third
     * {
    "msg": "操作成功",
    "code": 200,
    "data": {
    "records": [
    {
    "goodsId": "814028828350484480",商品id
    "goodsTitle": "商品名称",
    shareImage：商品分享图
    }
    ],
    "total": 1,
    "size": 300,
    "current": 1,
    "orders": [],
    "optimizeCountSql": true,
    "searchCount": true,
    "countId": null,
    "maxLimit": null,
    "pages": 1
    }
    }
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function pullGoodsList(){
        $url = 'https://appnew.baogefang.cc/houses-backstage/other/public/getAllProduct';
        $data = ['pageNumber' => 1, 'pageSize' => 10000];
        if(($res = http_post($url, $data, 'json')) !== false){
            $res = json_decode($res, true);
            $list = $res['data']['records'];
        }else{
            $list = [];
        }
        return $list;
    }
    private function getGoodsList(){
        $list = $this->pullGoodsList();
        $id2title= [];
        foreach ($list as $item){
            $id2title[$item['goodsId']] = $item['goodsTitle'];
        }
        return $id2title;
        //return $this->agentGoodsM->getIdToTitle();
    }

    /**
     * 商品模板列表
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    private function listGoodsTemplates(){
        return [0 => '选择商品'] + $this->goodsM->getFieldByOrder([
            'field' => ['id','title'],
            'order' => ['id' => 'desc'],
            'status' => 1
        ]);
    }
}