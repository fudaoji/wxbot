<?php

namespace app\admin\controller;
use app\common\model\yhq\Coupon;

class Yhqcoupon extends Botbase
{
    /**
     * @var Coupon
     */
    protected $model;
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->model = new Coupon();
    }

    /**
     * 设置
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function index(){
        if (request()->isPost()) {
            $post_data = input('post.');
            $where = ['bot_id' => $this->bot['id']];
            $total = $this->model->total($where, true);
            if ($total) {
                $list = $this->model->getList(
                    [$post_data['page'], $post_data['limit']], $where,
                    ['id' => 'desc'], true, true
                );
            } else {
                $list = [];
            }
            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $builder = new ListBuilder();
        $builder->addTopButton('addnew')
            ->addTableColumn(['title' => 'ID', 'field' => 'id', 'minWidth' => 80])
            ->addTableColumn(['title' => '名称', 'field' => 'title', 'minWidth' => 80])
            ->addTableColumn(['title' => '状态', 'field' => 'status', 'minWidth' => 80,'type' => 'enum','options' => [0 => '禁用', 1=> '启用']])
            ->addTableColumn(['title' => '上架时间', 'field' => 'create_time', 'minWidth' => 80,'type' => 'datetime'])
            ->addTableColumn(['title' => '操作', 'minWidth' => 150, 'type' => 'toolbar'])
            ->addRightButton('edit', ['title' => '码库', 'href' => url('yhqcode/index', ['coupon_id' => '__data_id__']),'class' => 'layui-btn layui-btn-xs'])
            ->addRightButton('edit')
            ->addRightButton('delete');

        return $builder->show();
    }

    public function edit(){
        $id = input('id', null);
        $data = $this->model->getOneByMap(['id' => $id, 'bot_id' => $this->bot['id']], true, true);

        if (!$data) {
            $this->error('参数错误');
        }

        $builder = new FormBuilder();
        $builder->setPostUrl(url('savePost'))
            ->addFormItem('id', 'hidden', 'id', 'id')
            ->addFormItem('title', 'text', '名称', '50字内', [], 'required maxlength=50')
            ->addFormItem('status', 'radio', '状态', '状态', [1 => '启用', 0 => '禁用'], 'required')
            ->setFormData($data);

        return $builder->show();
    }

    /**
     * 新增
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function add(){

        $builder = new FormBuilder();
        $data = [
            'bot_id' => $this->bot['id'],
            'status' => 1
        ];
        $builder->setPostUrl(url('savePost'))
            ->addFormItem('bot_id', 'hidden', 'botid', 'botid')
            ->addFormItem('title', 'text', '名称', '50字内', [], 'required maxlength=50')
            ->addFormItem('status', 'radio', '状态', '状态', [1 => '启用', 0 => '禁用'], 'required')
            ->setFormData($data);

        return $builder->show();
    }
}