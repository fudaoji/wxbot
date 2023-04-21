<?php
/**
 * Created by PhpStorm.
 * Script Name: Tenant.php
 * Create: 2023/2/16 14:37
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\controller;

use app\admin\model\Admin as AdminM;
use app\common\model\bgf\Agent as AgentM;
use app\constants\Common;

class Bgfagent extends Base
{
    /**
     * @var AdminM
     */
    protected $model;
    /**
     * @var AgentM
     */
    private $agentM;

    public function initialize(){
        parent::initialize();
        $this->model = new AdminM();
        $this->agentM = new AgentM();
    }

    /**
     * 客户管理（自主注册）
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function index(){
        if(request()->isPost()){
            $post_data = input('post.');
            $where = ['pid' => 0];
            !empty($post_data['search_key']) && $where['username|mobile|realname'] = ['like', '%'.$post_data['search_key'].'%'];
            !empty($post_data['super_id']) && $where['super_id'] = $post_data['super_id'];

            //非超管
            if($this->adminInfo['id'] != 1) {
                $where['admin.id'] = ['>', 1];
            }
            $params = [
                'alias' => 'admin',
                'join' => [
                    ['bgf_agent agent', 'agent.admin_id=admin.id', 'left']
                ],
                'refresh' => true
            ];
            $total = $this->model->totalJoin($params);
            if ($total) {
                $list = $this->model->getListJoin(array_merge($params, [
                    'limit' => [$post_data['page'], $post_data['limit']],
                    'order' => ['admin.id' => 'desc'],
                    'field' => ['admin.*', 'agent.super_id', 'agent.remark']
                ]));
            } else {
                $list = [];
            }

            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $builder = new ListBuilder();
        $builder->setSearch([
            ['type' => 'text', 'name' => 'search_key', 'title' => '搜索词', 'placeholder' => '账号、手机号、姓名'],
            ['type' => 'text', 'name' => 'super_id', 'title' => 'SuperId', 'placeholder' => 'SuperId']
        ])
            ->addTableColumn(['title' => 'ID', 'field' => 'id'])
            ->addTableColumn(['title' => '账号', 'field' => 'username'])
            ->addTableColumn(['title' => '手机号', 'field' => 'mobile'])
            ->addTableColumn(['title' => '姓名', 'field' => 'realname'])
            ->addTableColumn(['title' => 'SuperId', 'field' => 'super_id'])
            ->addTableColumn(['title' => '备注', 'field' => 'remark'])
            ->addTableColumn(['title' => '状态', 'field' => 'status', 'type' => 'switch', 'options' => Common::status()])
            ->addTableColumn(['title' => '操作', 'minWidth' => 120, 'type' => 'toolbar'])
            ->addRightButton('edit', ['href' => url('edit', ['id' => '__data_id__'])]);
        return $builder->show();
    }

    /**
     * 编辑
     */
    public function edit(){
        $id = input('id');
        $data = $this->model->getOneJoin([
            'alias' => 'admin',
            'join' => [
                ['bgf_agent agent', 'agent.admin_id=admin.id', 'left']
            ],
            'where' => ['admin.id' => $id],
            'field' => ['admin.*', 'agent.super_id', 'agent.remark']
        ]);
        if(! $data){
            $this->error('id参数错误');
        }

        //使用FormBuilder快速建立表单页面。
        $builder = new FormBuilder();
        $builder->setMetaTitle('编辑')  //设置页面标题
            ->setPostUrl(url('savepost')) //设置表单提交地址
            ->addFormItem('id', 'hidden', 'id', 'id')
            ->addFormItem('super_id', 'number', 'SuperId', 'SuperId', [], 'required min=1')
            ->addFormItem('remark', 'textarea', '备注', '200字内', [], 'maxlength=200')
            ->setFormData($data);

        return $builder->show();
    }

    public function savePost($jump_to = '/undefined', $data = [])
    {
        $post_data = input('post.');
        if($data = $this->agentM->getOneByMap(['admin_id' => $post_data['id']])){
            $this->agentM->updateOne([
                'id' => $data['id'],
                'super_id' => $post_data['super_id'],
                'remark' => $post_data['remark']
            ]);
        }else{
            $this->agentM->addOne([
                'admin_id' => $post_data['id'],
                'super_id' => $post_data['super_id'],
                'remark' => $post_data['remark']
            ]);
        }
        $this->success('操作成功!', $jump_to);
    }
}