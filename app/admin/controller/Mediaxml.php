<?php

namespace app\admin\controller;

use app\common\model\MediaXml as XmlM;
use app\common\service\Media as MediaService;
use app\constants\Media;
use app\common\service\MediaGroup as GroupService;

class Mediaxml extends Bbase
{
    /**
     * @var XmlM
     */
    protected $model;
    /**
     * @var string
     */
    private $tip;

    /**
     * 初始化
     */
    public function initialize()
    {
        parent::initialize();
        $this->model = new XmlM();
    }

    public function index()
    {
        if (request()->isPost()) {
            $post_data = input('post.');
            $where = ['admin_id' => $this->adminInfo['id']];
            !empty($post_data['search_key']) && $where['content|title'] = ['like', '%' . $post_data['search_key'] . '%'];
            !empty($post_data['group_id']) && $where['group_id'] = $post_data['group_id'];
            $total = $this->model->total($where, true);
            if ($total) {
                $list = $this->model->getList(
                    [$post_data['page'], $post_data['limit']], $where,
                    ['id' => 'desc'], true, true
                );
                foreach ($list as $k => $item){
                    $item['content'] = '<xmp>'.$item['content'].'</xmp>';
                    $list[$k] = $item;
                }
            } else {
                $list = [];
            }
            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $builder = new ListBuilder();
        $builder->setTabNav($this->mediaTabs(), Media::XML)
            ->setSearch([
                ['type' => 'select', 'name' => 'group_id', 'title' => '分组', 'options' => [0=>'全部'] + GroupService::getIdToTitle()],
                ['type' => 'text', 'name' => 'search_key', 'title' => '关键词'],
        ])
            ->addTopButton('addnew')
            ->addTableColumn(['title' => '备注', 'field' => 'title', 'minWidth' => 100])
            ->addTableColumn(['title' => '内容', 'field' => 'content', 'type' => 'article','minWidth' => 400])
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
        $builder->setMetaTitle('新增xml')
            ->setPostUrl(url('savePost'))
            ->addFormItem('group_id', 'chosen', '分组', '分组', GroupService::getIdToTitle())
            ->addFormItem('title', 'text', '备注', '30字内', [], 'maxlength=30')
            ->addFormItem('content', 'textarea', 'xml内容', '查看消息体中的xml', [], 'required maxlength=1000000');

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
        $builder->setMetaTitle('编辑xml')
            ->setPostUrl(url('savePost'))
            ->addFormItem('id', 'hidden', 'ID', 'ID')
            ->addFormItem('group_id', 'chosen', '分组', '分组', GroupService::getIdToTitle())
            ->addFormItem('title', 'text', '备注', '30字内', [], 'maxlength=30')
            ->addFormItem('content', 'textarea', 'xml内容', '查看消息体中的xml', [], 'required maxlength=1000000')
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
            //$this->refreshMedia($res['id']);
            MediaService::getMedia([
                'media_type' => 'text',
                'staff_id' => $this->bot['staff_id'],
                'admin_id' => $this->bot['admin_id'],
                'media_id' => $res['id']
            ], true);
            $this->success('数据保存成功', $jump_to);
        }else{
            $this->error('数据保存出错');
        }
    }
}