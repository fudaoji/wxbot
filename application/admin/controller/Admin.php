<?php
/**
 * SCRIPT_NAME: Admin.php
 * Created by PhpStorm.
 * Time: 2020/9/6 23:23
 * Description: 管理员
 * @author: fudaoji <fdj@kuryun.cn>
 */

namespace app\admin\controller;

class Admin extends Base
{
    /**
     * @var \app\admin\model\Admin
     */
    protected $model;
    protected $groupM;
    protected $shopM;

    public function initialize(){
        parent::initialize();
        $this->model = model('Admin');
        $this->groupM = model('AdminGroup');
    }
    
    /**
     * 管理员列表
     * Author: Jason<dcq@kuryun.cn>
     */
    public function index(){
        if(request()->isPost()){
            $post_data = input('post.');
            $where = [];
            !empty($post_data['search_key']) && $where['username|mobile|realname'] = ['like', '%'.$post_data['search_key'].'%'];
            if(!empty($post_data['group_id'])) {
                $where['group_id'] = $post_data['group_id'];
            }
            //非超管
            if($this->adminInfo['id'] != 1) {
                $where['id'] = ['gt', 1];
            }
            $total = $this->model->total($where, true);
            if ($total) {
                $list = $this->model->getList([$post_data['page'], $post_data['limit']], $where, ['id' => 'desc'], true, 1);
            } else {
                $list = [];
            }

            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $group_list = $this->groupM->getField('id,title', ['status' => 1]);
        $builder = new ListBuilder();
        $builder->setSearch([
            ['type' => 'text', 'name' => 'search_key', 'title' => '搜索词','placeholder' => '账号、手机号、姓名'],
            ['type' => 'select', 'name' => 'group_id', 'title' => '角色', 'options' => [0 => '全部角色'] + $group_list]
        ])
            ->addTopButton('addnew')
            ->addTableColumn(['title' => '序号', 'type' => 'index'])
            ->addTableColumn(['title' => '账号', 'field' => 'username'])
            ->addTableColumn(['title' => '邮箱', 'field' => 'email'])
            ->addTableColumn(['title' => '手机号', 'field' => 'mobile'])
            ->addTableColumn(['title' => '姓名', 'field' => 'realname'])
            ->addTableColumn(['title' => '角色', 'field' => 'group_id', 'type' => 'enum', 'options' => $group_list])
            ->addTableColumn(['title' => '状态', 'field' => 'status', 'type' => 'enum', 'options' => [0 => '禁用', 1 => '启用']])
            ->addTableColumn(['title' => '操作', 'width' => 220, 'type' => 'toolbar'])
            ->addRightButton('edit')
            ->addRightButton('self', ['title' => '修改密码','class' => 'layui-btn layui-btn-warm layui-btn-xs','href' => url('admin/setPassword', '', '') . '?id=__data_id__'])
            ->addRightButton('delete');
        return $builder->show();
    }

    /**
     * 添加
     */
    public function add(){
        if($this->adminInfo['group_id'] == 1) {
            $groups = model('Admingroup')->getField('id, title', ['status' => 1]);
        }else {
            $groups = model('AdminGroup')->getField('id, title', ['status' => 1, 'id' => ['gt', 1]]);
        }
        //使用FormBuilder快速建立表单页面。
        $builder = new FormBuilder();
        $builder->setMetaTitle('新增')  //设置页面标题
        ->setPostUrl(url('savepost')) //设置表单提交地址
        ->addFormItem('group_id', 'select', '角色', '角色', $groups, 'required')
            ->addFormItem('username', 'text', '账号', '4-20位', [], 'required minlength="4" maxlength="20"')
            ->addFormItem('password', 'password', '密码', '6-20位', [], 'required')
            ->addFormItem('email', 'text', '邮箱', '邮箱')
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
        if($this->adminInfo['group_id'] == 1) {
            $groups = model('Admingroup')->getField('id, title', ['status' => 1]);
        }else {
            $groups = model('AdminGroup')->getField('id, title', ['status' => 1, 'id' => ['gt', 1]]);
        }
        //使用FormBuilder快速建立表单页面。
        $builder = new FormBuilder();
        $builder->setMetaTitle('编辑')  //设置页面标题
            ->setPostUrl(url('savepost')) //设置表单提交地址
            ->addFormItem('id', 'hidden', 'id', 'id')
            ->addFormItem('group_id', 'select', '角色', '角色', $groups, 'required')
            ->addFormItem('username', 'text', '账号', '4-20位', [], 'required minlength="4" maxlength="20"')
            ->addFormItem('email', 'text', '邮箱', '邮箱')
            ->addFormItem('mobile', 'text', '手机', '手机')
            ->addFormItem('realname', 'text', '姓名', '姓名')
            ->setFormData($data);

        return $builder->show();
    }

    /**
     * 编辑
     */
    public function setPassword(){
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
            ->addFormItem('password', 'password', '新密码', '6-20位', [], 'required')
            ->setFormData($data);

        return $builder->show();
    }

    /**
     * 保存数据
     * @param string $url
     * @param array $data
     * @return mixed
     * @Author  Doogie<461960962@qq.com>
     */
    public function savePost($url='', $data=[]){
        $post_data = input('post.');
        if(!empty($post_data['password'])){
            $post_data['password'] = ky_generate_password($post_data['password']);
        }
        return parent::savePost(url('index'), $post_data);
    }

    /**
     * 修改个人密码
     * @return mixed
     * Author: Doogie<fdj@kuryun.cn>
     * @throws \think\Exception
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function setPersonPw(){
        if(request()->isPost()){
            $post_data = ['password' => input('post.password')];
            if(!empty($post_data['password'])){
                $post_data['password'] = ky_generate_password($post_data['password']);
            }
            $post_data['id'] = $this->aid;

            $res = $this->model->updateOne($post_data);
            if($res){
                $this->model->getOneByMap(['username' => $res['username']], 1);
                session('aid', null);
                return $this->success('密码修改成功', url('auth/login'));
            }else{
                return $this->error('系统出错');
            }
        }
        //使用FormBuilder快速建立表单页面。
        $builder = new FormBuilder();
        $builder->setMetaTitle('修改个人密码')  //设置页面标题
            ->setPostUrl(url('setPersonPw')) //设置表单提交地址
            ->addFormItem('password', 'password', '新密码', '6-20位', [], 'required minlength=6 maxlength=20');

        return $builder->show();
    }

}