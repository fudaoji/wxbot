<?php
/**
 * Created by PhpStorm.
 * Script Name: Botapply.php
 * Create: 2023/2/22 9:16
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\controller;
use app\common\model\BotApply as ApplyM;
use app\constants\Common;

class Botapply extends Bbase
{
    /**
     * @var ApplyM
     */
    protected $model;

    public function initialize(){
        parent::initialize();
        $this->model = new ApplyM();
    }

    /**
     * 客户端申请列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function index(){
        if(request()->isPost()){
            $post_data = input('post.');
            $where = ['admin_id' => $this->adminInfo['id']];
            !empty($post_data['search_key']) && $where['wx_num'] = ['like', '%'.$post_data['search_key'].'%'];

            $total = $this->model->total($where, true);
            if ($total) {
                $list = $this->model->getList([$post_data['page'], $post_data['limit']], $where, ['id' => 'desc'], true, 1);
            } else {
                $list = [];
            }

            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $builder = new ListBuilder();
        $builder->setSearch([
            ['type' => 'text', 'name' => 'search_key', 'title' => '搜索词','placeholder' => '微信号']
        ])
            ->addTopButton('addnew')
            ->addTableColumn(['title' => '序号', 'type' => 'index'])
            ->addTableColumn(['title' => '微信号', 'field' => 'wx_num'])
            ->addTableColumn(['title' => '有效时间(个月)', 'field' => 'month'])
            ->addTableColumn(['title' => '到期时间', 'field' => 'deadline', 'type' => 'datetime'])
            ->addTableColumn(['title' => '状态', 'field' => 'status', 'type' => 'enum', 'options' => Common::verifies()])
            ->addTableColumn(['title' => '申请时间', 'field' => 'create_time', 'type' => 'datetime'])
            ->addTableColumn(['title' => '操作', 'width' => 100, 'type' => 'toolbar'])
            ->addRightButton('edit');
        return $builder->show();
    }

    public function add(){
        //使用FormBuilder快速建立表单页面。
        $builder = new FormBuilder();
        $builder->setMetaTitle('新增')  //设置页面标题
            ->setPostUrl(url('savepost')) //设置表单提交地址
            ->addFormItem('wx_num', 'text', '微信号', '请输入微信号', [], 'required minlength="4" maxlength="50"')
            ->addFormItem('month', 'number', '要开几个月', '请输入要开通的时间，单位月。例如： 3', [], 'required min=1');

        return $builder->show();
    }

    public function edit(){
        $id = input('id');
        $data = $this->model->getOneByMap([
            'id' => $id,
            'admin_id' => $this->adminInfo['id']
        ]);
        if(! $data){
            $this->error('id参数错误');
        }
        //使用FormBuilder快速建立表单页面。
        $builder = new FormBuilder();
        $builder->setMetaTitle('新增')  //设置页面标题
            ->setPostUrl(url('savepost')) //设置表单提交地址
        ->addFormItem('id', 'hidden', 'id', 'id')
            ->addFormItem('wx_num', 'text', '微信号', '请输入微信号', [], 'required minlength="4" maxlength="50"')
        ->setFormData($data);
        if($data['status'] != Common::VERIFY_SUCCESS){
            $builder->addFormItem('month', 'number', '要开几个月', '请输入要开通的时间，单位月。例如： 3', [], 'required min=1');
        }
        return $builder->show();
    }

    /**
     * 站长端申请列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function applyList(){
        if(request()->isPost()){
            $post_data = input('post.');
            $where = ['apply.admin_id' => $this->adminInfo['id']];
            !empty($post_data['search_key']) && $where['apply.wx_num|admin.username|admin.mobile'] = ['like', '%'.$post_data['search_key'].'%'];

            $params = [
                'alias' => 'apply',
                'join' => [
                    ['admin', 'admin.id=apply.admin_id']
                ],
                'where' => $where,
                'refresh' => true
            ];
            $total = $this->model->totalJoin($params);
            if ($total) {
                $list = $this->model->getListJoin(array_merge($params, [
                    'limit' => [$post_data['page'], $post_data['limit']],
                    'order' => ['apply.id' => 'desc'],
                    'field' => ['admin.username', 'admin.mobile', 'apply.*']
                ]));
                foreach ($list as $k => $v){
                    $v['admin_info'] = '账号：'.$v['username'] . '<br>手机号：'.$v['mobile'];
                    $list[$k] = $v;
                }
            } else {
                $list = [];
            }

            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $builder = new ListBuilder();
        $builder->setSearch([
            ['type' => 'text', 'name' => 'search_key', 'title' => '搜索词','placeholder' => '微信号/客户手机号/客户账号']
        ])
            ->addTopButton('addnew')
            ->addTableColumn(['title' => '序号', 'type' => 'index'])
            ->addTableColumn(['title' => '客户信息', 'field' => 'admin_info'])
            ->addTableColumn(['title' => '微信号', 'field' => 'wx_num'])
            ->addTableColumn(['title' => '有效时间(个月)', 'field' => 'month'])
            ->addTableColumn(['title' => '到期时间', 'field' => 'deadline', 'type' => 'datetime'])
            ->addTableColumn(['title' => '状态', 'field' => 'status', 'type' => 'enum', 'options' => Common::verifies()])
            ->addTableColumn(['title' => '申请时间', 'field' => 'create_time', 'type' => 'datetime'])
            ->addTableColumn(['title' => '操作', 'width' => 100, 'type' => 'toolbar'])
            ->addRightButton('edit', ['title' => '审核', 'href' => url('verify', ['id' => '__data_id__'])]);
        return $builder->show();
    }

    /**
     * 审核操作
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function verify(){
        $id = input('id');
        $data = $this->model->getOne($id);
        if(! $data){
            $this->error('id参数错误');
        }

        if(request()->isPost()){
            $post_data = input('post.');
            if($post_data['status'] == Common::VERIFY_SUCCESS){
                $post_data['deadline'] = strtotime("+" . $post_data['month'] . ' months', max($data['deadline'], time()));
            }
            return parent::savePost('', $post_data);
        }

        if(! \app\admin\model\Admin::isFounder($this->adminInfo)){
            $this->error('非法操作');
        }

        //使用FormBuilder快速建立表单页面。
        $builder = new FormBuilder();
        $builder->setMetaTitle('新增')  //设置页面标题
            ->setPostUrl(url('verify')) //设置表单提交地址
        ->addFormItem('id', 'hidden', 'id', 'id')
            ->addFormItem('wx_num', 'text', '微信号', '请输入微信号', [], 'required minlength="4" maxlength="50"')
            ->addFormItem('month', 'number', '要开几个月', '从当前时间开始算', [], 'required min=1')
            ->addFormItem('status', 'radio', '状态', '请选择审核状态', Common::verifies(), 'required')
            ->setFormData($data);
        return $builder->show();
    }
}