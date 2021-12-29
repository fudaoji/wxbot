<?php

namespace app\admin\controller;

use app\common\model\MediaFile as FileM;
use app\constants\Media;

class Mediafile extends Bbase
{
    /**
     * @var FileM
     */
    protected $model;


    /**
     * 初始化
     */
    public function initialize()
    {
        parent::initialize();
        $this->model = new FileM();
    }

    public function index()
    {
        if (request()->isPost()) {
            $post_data = input('post.');
            $where = ['admin_id' => $this->adminInfo['id']];
            !empty($post_data['search_key']) && $where['title'] = ['like', '%' . $post_data['search_key'] . '%'];
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
        $builder->setTabNav($this->mediaTabs(), Media::FILE)
            ->setSearch([
            ['type' => 'text', 'name' => 'search_key', 'title' => '关键词', "tip" => '名称']
        ])
            ->addTopButton('addnew')
            ->addTableColumn(['title' => '名称', 'field' => 'title', 'minWidth' => 80])
            ->addTableColumn(['title' => '文件链接', 'field' => 'url', 'minWidth' => 250])
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
        $builder->setMetaTitle('新增文件')
            ->setPostUrl(url('savePost'))
            ->addFormItem('file', 'file_detail', '上传文件', '上传文件');

        return $builder->show();
    }

    public function edit()
    {
        $id = input('id', null);
        $data = $this->model->getOne($id);

        if (!$data) {
            $this->error('参数错误');
        }

        // 使用FormBuilder快速建立表单页面
        $builder = new FormBuilder();
        $builder->setMetaTitle('编辑文件')
            ->setPostUrl(url('savePost'))
            ->addFormItem('id', 'hidden', 'ID', 'ID')
            ->addFormItem('file', 'file_detail', '上传文件', '上传文件', $data)
            ->setFormData($data);

        return $builder->show();
    }
}