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
            $where = ['pid' => 0];
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
            ->addTableColumn(['title' => '序号', 'type' => 'index'])
            ->addTableColumn(['title' => '账号', 'field' => 'username'])
            ->addTableColumn(['title' => '手机号', 'field' => 'mobile'])
            ->addTableColumn(['title' => '姓名', 'field' => 'realname'])
            ->addTableColumn(['title' => '状态', 'field' => 'status', 'type' => 'switch', 'options' => Common::status()])
            ->addTableColumn(['title' => '操作', 'width' => 220, 'type' => 'toolbar'])
            ->addRightButton('edit', ['href' => url('admin/edit', ['id' => '__data_id__'])])
            ->addRightButton('self', ['title' => '修改密码','class' => 'layui-btn layui-btn-warm layui-btn-xs','href' => url('admin/setPassword', ['id' => '__data_id__'])]);
        return $builder->show();
    }
}