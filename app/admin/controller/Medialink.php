<?php

namespace app\admin\controller;

use app\common\model\MediaLink as LinkM;
use app\common\service\MediaGroup as GroupService;
use app\constants\Media;

class Medialink extends Bbase
{
    /**
     * @var LinkM
     */
    protected $model;


    /**
     * 初始化
     */
    public function initialize()
    {
        parent::initialize();
        $this->model = new LinkM();
    }

    public function index()
    {
        if (request()->isPost()) {
            $post_data = input('post.');
            $where = ['admin_id' => $this->adminInfo['id']];
            !empty($post_data['search_key']) && $where['title|desc'] = ['like', '%' . $post_data['search_key'] . '%'];
            !empty($post_data['group_id']) && $where['group_id'] = $post_data['group_id'];
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
        $builder->setTabNav($this->mediaTabs(), Media::LINK)
            ->setSearch([
                ['type' => 'select', 'name' => 'group_id', 'title' => '分组', 'options' => [0=>'全部'] + GroupService::getIdToTitle()],
            ['type' => 'text', 'name' => 'search_key', 'title' => '关键词', "tip" => '标题、描述']
        ])
            ->addTopButton('addnew')
            ->addTableColumn(['title' => '标题', 'field' => 'title', 'minWidth' => 100])
            ->addTableColumn(['title' => '描述', 'field' => 'desc', 'minWidth' => 200])
            ->addTableColumn(['title' => '封面', 'field' => 'image_url', 'minWidth' => 100, "type" => 'picture'])
            ->addTableColumn(['title' => '跳转链接', 'field' => 'url', 'minWidth' => 200])
            ->addTableColumn(['title' => '创建时间', 'field' => 'create_time', 'type' => 'datetime', 'minWidth' => 180])
            ->addTableColumn(['title' => '修改时间', 'field' => 'update_time', 'type' => 'datetime', 'minWidth' => 180])
            ->addTableColumn(['title' => '操作', 'minWidth' => 130, 'type' => 'toolbar'])
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
        $builder->setMetaTitle('新增分享链接')
            ->setPostUrl(url('savePost'))
            ->addFormItem('group_id', 'chosen', '分组', '分组', GroupService::getIdToTitle())
            ->addFormItem('title', 'text', '标题', '100字内', [], 'required maxlength=150')
            ->addFormItem('desc', 'textarea', '描述', '200字内', [], 'required maxlength=200')
            ->addFormItem('image_url', 'choose_picture', '图片', '图片比例1:1', [], 'required')
            ->addFormItem('url', 'text', '跳转链接', '跳转链接', [], 'required');

        return $builder->show();
    }

    public function edit()
    {
        $id = input('id', null);
        $data = $this->model->getOneByMap(['id' => $id, 'admin_id' => $this->adminInfo['id']], true, true);

        if (!$data) {
            $this->error('参数错误');
        }

        // 使用FormBuilder快速建立表单页面
        $builder = new FormBuilder();
        $builder->setMetaTitle('编辑文本')
            ->setPostUrl(url('savePost'))
            ->addFormItem('id', 'hidden', 'ID', 'ID')
            ->addFormItem('group_id', 'chosen', '分组', '分组', GroupService::getIdToTitle())
            ->addFormItem('title', 'text', '标题', '100字内', [], 'required maxlength=100')
            ->addFormItem('desc', 'textarea', '描述', '200字内', [], 'required maxlength=150')
            ->addFormItem('image_url', 'choose_picture', '图片', '图片比例1:1', [], 'required')
            ->addFormItem('url', 'text', '跳转链接', '跳转链接', [], 'required')
            ->setFormData($data);

        return $builder->show();
    }

    public function savePost($jump_to = '/undefined', $data = [])
    {
        $post_data = input('post.');
        $post_data['admin_id'] = $this->adminInfo['id'];
        if(empty($post_data[$this->pk])){
            $res = $this->model->addOne($post_data);
        }else {
            $res = $this->model->updateOne($post_data);
        }
        if($res){
            $this->refreshMedia($res['id']);
            $this->success('数据保存成功', $jump_to);
        }else{
            $this->error('数据保存出错');
        }
    }
}