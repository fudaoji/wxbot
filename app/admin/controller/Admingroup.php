<?php
/**
 * Created by PhpStorm.
 * Script Name: Admingroup.php
 * Create: 2:52 下午
 * Description:
 * Author: Jason<dcq@kuryun.cn>
 */

namespace app\admin\controller;

use ky\ErrorCode;
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
            $where = [];
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
        //超管拥有新增权限
        if($this->adminInfo['group_id'] == 1) {
            $builder->addTopButton('addnew');
        }
            $builder->addTableColumn(['title' => '权限名称', 'field' => 'title'])
            ->addTableColumn(['title' => '备注信息', 'field' => 'remark'])
            ->addTableColumn(['title' => '状态', 'field' => 'status', 'type' => 'enum', 'options' => [1 => '启用', 0 => '禁用']])
            ->addTableColumn(['title' => '操作', 'width' => 120, 'type' => 'toolbar'])
            ->addRightButton('edit')
            ->addRightButton('self', ['title' => '授权','class' => 'layui-btn layui-btn-success layui-btn-xs','lay-event' => 'auth', 'href' => url('admingroup/auth', ['group_id' => '__data_id__'])]);
        //超管拥有删除权限
        if($this->adminInfo['group_id'] == 1) {
            $builder->addRightButton('delete');
        }

        return $builder->show();
    }

    /**
     * 添加
     * Author: Doogie <461960962@qq.com>
     */
    public function add(){
        //使用FormBuilder快速建立表单页面。
        $builder = new FormBuilder();
        $builder->setMetaTitle('新增')  //设置页面标题
            ->setPostUrl(url('savepost')) //设置表单提交地址
            ->addFormItem('title', 'text', '角色名称', '角色名称', [], 'required')
            ->addFormItem('remark', 'textarea', '备注', '备注');

        return $builder->show();
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
        $data['rules'] = explode(',', $data['rules']);

        return $this->show(['data' => $data]);
    }

    /**
     * 节点树
     * Author: Jason<dcq@kuryun.cn>
     */
    public function getRulesTree() {
        if(request()->isPost()){
            $post_data = input('post.');
            $rules = $this->ruleM->getAll([
                'where' => ['status' => 1],
                'order' => ['sort' => 'asc'],
                'field' => 'id, pid, title, href'
            ]);
            $group = $this->model->getOne($post_data['group_id']);
            $group_rules = explode(',', $group['rules']);

            //插入layui展开参数
            foreach ($rules as &$item) {
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
            }
            $Tree = new Tree();
            $rules_tree = $Tree->listToTree($rules);
            $this->success('success', '', ['rules_tree' => $rules_tree]);
        }
    }
}