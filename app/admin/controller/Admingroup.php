<?php
/**
 * Created by PhpStorm.
 * Script Name: Admingroup.php
 * Create: 2:52 下午
 * Description:
 * Author: Jason<dcq@kuryun.cn>
 */

namespace app\admin\controller;

use app\constants\Common;
use app\admin\model\Admin as AdminM;
use ky\Tree;

class Admingroup extends Base
{
    /**
     * @var \app\admin\model\AdminRule
     */
    private $ruleM;

    public function initialize(){
        parent::initialize();
        $this->model = new \app\admin\model\AdminGroup();
        $this->ruleM = new \app\admin\model\AdminRule();
    }

    /**
     * 分组列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index(){
        if(request()->isPost()){
            $post_data = input('post.');
            $where = ['admin_id' => $this->adminInfo['id']];
            !empty($post_data['search_key']) && $where['title'] = ['like', '%'.$post_data['search_key'].'%'];
            $total = $this->model->total($where, true);
            if($total){
                $list = $this->model->getList(
                    [$post_data['page'], $post_data['limit']], $where,
                    ['sort' => 'asc'], true, true
                );
            }else{
                $list = [];
            }
            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $builder = new ListBuilder();
        $builder->setSearch([
            ['type' => 'text', 'name' => 'search_key', 'title' => '搜索词','placeholder' => '部门名称']
        ]);
        //商户拥有新增权限
        if(AdminM::isLeader($this->adminInfo)) {
            $builder->addTopButton('addnew');
        }
            $builder->addTableColumn(['title' => 'ID', 'field' => 'id'])
                ->addTableColumn(['title' => '权限名称', 'field' => 'title'])
            ->addTableColumn(['title' => '备注信息', 'field' => 'remark'])
            ->addTableColumn(['title' => '状态', 'field' => 'status', 'type' => 'switch', 'options' => Common::status()])
            ->addTableColumn(['title' => '操作', 'width' => 180, 'type' => 'toolbar'])
            ->addRightButton('edit')
            ->addRightButton('self', ['title' => '授权','class' => 'layui-btn layui-btn-success layui-btn-xs','lay-event' => 'auth', 'href' => url('admingroup/auth', ['group_id' => '__data_id__'])]);
        //超管拥有删除权限
        if(AdminM::isLeader($this->adminInfo)) {
            $builder->addRightButton('delete');
        }

        return $builder->show();
    }

    /**
     * 添加
     * Author: Doogie <461960962@qq.com>
     */
    public function add(){
        $data = ['admin_id' => $this->adminInfo['id']];
        //使用FormBuilder快速建立表单页面。
        $builder = new FormBuilder();
        $builder->setMetaTitle('新增')  //设置页面标题
            ->setPostUrl(url('savepost')) //设置表单提交地址
            ->addFormItem('admin_id', 'hidden', 'admin_id', 'admin_id')
            ->addFormItem('title', 'text', '角色名称', '角色名称', [], 'required')
            ->addFormItem('remark', 'textarea', '备注', '备注');
        if(AdminM::isFounder($this->adminInfo)){
            $data['tenant_group'] = 0;
            $builder->addFormItem('tenant_group', 'radio', '客户角色', '是否客户角色，客户角色只能有一个', Common::yesOrNo());
        }
        return $builder->setFormData($data)
            ->show();
    }

    /**
     * 编辑
     * Author: Doogie <461960962@qq.com>
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
            ->addFormItem('title', 'text', '角色名称', '角色名称', [], 'required')
            ->addFormItem('remark', 'textarea', '备注', '备注')
            ->setFormData($data);
        if(AdminM::isFounder($this->adminInfo)){
            $builder->addFormItem('tenant_group', 'radio', '客户角色', '是否客户角色', Common::yesOrNo());
        }
        return $builder->show();
    }

    /**
     * 授权
     * Author: Jason<dcq@kuryun.cn>
     */
    public function auth() {
        if(request()->isPost()) {
            $post_data = input('post.');
            $update_data = [
                'id' => $post_data['id'],
                'rules' => $post_data['rules']
            ];
            $result = $this->model->updateOne($update_data);
            if($result) {
                $this->success('授权成功', url('index'), ['result' => $result]);
            }else {
                $this->error('授权失败');
            }
        }
        $group_id = input('group_id');
        $data = $this->model->getOne($group_id);
        if(! $data) {
            $this->error('id非法');
        }
        $data['rules'] = json_encode(explode(',', $data['rules']));
        return $this->show(['data' => $data]);
    }

    /**
     * 节点树
     * Author: Jason<dcq@kuryun.cn>
     */
    public function getRulesTree() {
        if(request()->isPost()){
            $post_data = input('post.');
            if(AdminM::isFounder($this->adminInfo)){
                $rules = $this->ruleM->getAll([
                    'where' => ['status' => 1],
                    'order' => ['sort' => 'desc'],
                    'field' => 'id, pid, title, href'
                ]);
            }else{
                $rules = $this->ruleM->getGroupRules($this->adminInfo['group_id']);
            }

            $group_rules = array_values($this->ruleM->getGroupRules($post_data['group_id'], 'id'));

            //插入layui展开参数
            foreach ($rules as $k => &$item) {
                $item['spread'] = true;
                if($item['href']) {
                    $item['title'] = $item['title'] . '【' . $item['href'] . '】';
                }
                //设置数据源中勾选的叶子节点checked属性为true
                $total = $this->ruleM->total(['pid' => $item['id'], 'status' => 1]);
                if(in_array($item['id'], $group_rules) && !$total) {
                    $item['checked'] = true;
                }else {
                    $item['checked'] = false;
                }
                /*$item['name'] = $item['title'];
                $item['value'] = $item['id'];*/
                $rules[$k] = $item;
            }
            $Tree = new Tree();
            //$rules_tree = $Tree->listToTree($rules);
            $rules_tree = $rules;
            $this->success('success', '', ['rules_tree' => $rules_tree]);
        }
    }
}