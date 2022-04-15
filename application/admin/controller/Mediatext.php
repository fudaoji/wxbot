<?php

namespace app\admin\controller;

use app\common\model\MediaText as TextM;
use app\constants\Media;

class Mediatext extends Bbase
{
    /**
     * @var TextM
     */
    protected $model;


    /**
     * 初始化
     */
    public function initialize()
    {
        parent::initialize();
        $this->model = new TextM();
    }

    public function index()
    {
        if (request()->isPost()) {
            $post_data = input('post.');
            $where = ['admin_id' => $this->adminInfo['id']];
            !empty($post_data['search_key']) && $where['content|title'] = ['like', '%' . $post_data['search_key'] . '%'];
            $total = $this->model->total($where, true);
            if ($total) {
                $list = $this->model->getList(
                    [$post_data['page'], $post_data['limit']], $where,
                    [], true, true
                );
            } else {
                $list = [];
            }
            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $builder = new ListBuilder();
        $builder->setTabNav($this->mediaTabs(), Media::TEXT)
            ->setSearch([
            ['type' => 'text', 'name' => 'search_key', 'title' => '关键词']
        ])
            ->addTopButton('addnew')
            ->addTableColumn(['title' => '备注', 'field' => 'title', 'minWidth' => 100])
            ->addTableColumn(['title' => '文本内容', 'field' => 'content', 'minWidth' => 400])
            ->addTableColumn(['title' => '创建时间', 'field' => 'create_time', 'type' => 'datetime', 'minWidth' => 200])
            ->addTableColumn(['title' => '修改时间', 'field' => 'update_time', 'type' => 'datetime', 'minWidth' => 200])
            ->addTableColumn(['title' => '操作', 'minWidth' => 150, 'type' => 'toolbar'])
            ->addRightButton('edit')
            ->addRightButton('delete');

        return $builder->show();
    }

    /**
     * 添加
     * @return mixed
     */
    public function add()
    {
        // 使用FormBuilder快速建立表单页面
        $builder = new FormBuilder();
        $builder->setMetaTitle('新增文本')
            ->setPostUrl(url('savePost'))
            ->addFormItem('title', 'text', '备注', '30字内', [], 'maxlength=30')
            ->addFormItem('content', 'textarea', '文本内容', '1000字内', [], 'required maxlength=1000');

        return $builder->show();
    }

    public function edit()
    {
        $id = input('id', null);
        $data = $this->model->getOneByMap(['id' => $id, 'admin_id' => $this->adminInfo['id']]);

        if (!$data) {
            $this->error('参数错误');
        }

        // 使用FormBuilder快速建立表单页面
        $builder = new FormBuilder();
        $builder->setMetaTitle('编辑文本')
            ->setPostUrl(url('savePost'))
            ->addFormItem('id', 'hidden', 'ID', 'ID')
            ->addFormItem('title', 'text', '备注', '30字内', [], 'maxlength=30')
            ->addFormItem('content', 'textarea', '文本内容', '1000字内', [], 'required maxlength=1000')
            ->setFormData($data);

        return $builder->show();
    }
}