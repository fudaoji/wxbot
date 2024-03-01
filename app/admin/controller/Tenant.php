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
use app\admin\model\AdminGroup as GroupM;
use app\constants\Common;

class Tenant extends Base
{
    /**
     * @var AdminM
     */
    protected $model;
    /**
     * @var GroupM
     */
    private $groupM;

    public function initialize(){
        parent::initialize();
        $this->model = new AdminM();
        $this->groupM = new GroupM();
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
            $where = ['pid' => 0, 'id' => ['<>', AdminM::getFounderId()]];
            !empty($post_data['search_key']) && $where['username|mobile|realname'] = ['like', '%'.$post_data['search_key'].'%'];

            //非超管
            if($this->adminInfo['id'] != 1) {
                $where['id'] = ['>', 1];
            }
            $total = $this->model->total($where, true);
            if ($total) {
                $list = $this->model->getList([$post_data['page'], $post_data['limit']], $where, ['id' => 'desc'], true, 1);
            } else {
                $list = [];
            }

            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $group_list = $this->groupM->getGroupsIdToTitle($this->adminInfo['id']);
        $builder = new ListBuilder();
        $builder->setSearch([
            ['type' => 'text', 'name' => 'search_key', 'title' => '搜索词','placeholder' => '账号、手机号、姓名'],
            ['type' => 'select', 'name' => 'group_id', 'title' => '角色', 'options' => [0 => '全部角色'] + $group_list]
        ])
            ->addTopButton('addnew')
            ->addTableColumn(['title' => '序号', 'type' => 'index'])
            ->addTableColumn(['title' => '账号', 'field' => 'username'])
            ->addTableColumn(['title' => '手机号', 'field' => 'mobile'])
            ->addTableColumn(['title' => '姓名', 'field' => 'realname'])
            ->addTableColumn(['title' => '状态', 'field' => 'status', 'type' => 'switch', 'options' => Common::status()])
            ->addTableColumn(['title' => '操作', 'width' => 220, 'type' => 'toolbar'])
            ->addRightButton('edit')
            ->addRightButton('self', ['title' => '修改密码','class' => 'layui-btn layui-btn-warm layui-btn-xs','href' => url('admin/setPassword', ['id' => '__data_id__'])]);
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
            ->addFormItem('username', 'text', '账号', '4-20位', [], 'required minlength=4 maxlength=20')
            ->addFormItem('password', 'password', '密码', '6-20位', [], 'required minlength=6 maxlength=20')
            ->addFormItem('mobile', 'text', '手机', '手机')
            ->addFormItem('realname', 'text', '姓名', '姓名');

        return $builder->show();
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
        unset($data['password']);
        //使用FormBuilder快速建立表单页面。
        $builder = new FormBuilder();
        $builder->setMetaTitle('编辑')  //设置页面标题
        ->setPostUrl(url('savepost')) //设置表单提交地址
        ->addFormItem('id', 'hidden', 'id', 'id')
            ->addFormItem('username', 'text', '账号', '4-20位', [], 'required minlength=4 maxlength=20')
            ->addFormItem('password', 'password', '密码', '留空则不修改', [], 'minlength=6 maxlength=20')
            ->addFormItem('mobile', 'text', '手机', '手机')
            ->addFormItem('realname', 'text', '姓名', '姓名')
            ->setFormData($data);

        return $builder->show();
    }

    public function savePost($url='/undefined', $data=[]){
        $post_data = input('post.');
        if(!empty($post_data['password'])){
            $post_data['password'] = ky_generate_password($post_data['password']);
        }else{
            unset($post_data['password']);
        }
        if(empty($post_data['id'])){
            $post_data['group_id'] = \app\admin\model\AdminGroup::getTenantGroup('id')['id'];
        }
        if(!empty($post_data['username'])){
            $exists_where = ['username' => $post_data['username']];
            if(! empty($post_data[$this->pk])){
                $exists_where['id'] = ['<>', $post_data[$this->pk]];
            }
            if($this->model->total($exists_where)){
                $this->error('账号已被占用，请更换！');
            }
        }
        return parent::savePost($url, $post_data);
    }
}