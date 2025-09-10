<?php

namespace app\admin\controller;

use app\common\model\MediaFile as FileM;
use app\common\service\Media as MediaService;
use app\common\service\MediaGroup as GroupService;
use app\constants\Media;

class Mediafile extends Bbase
{
    /**
     * @var FileM
     */
    protected $model;
    /**
     * @var array
     */
    private $tabList;


    /**
     * 初始化
     */
    public function initialize()
    {
        parent::initialize();
        $this->model = new FileM();
        $this->tabList = [
            'add' => ['title' => '单个上传', 'href' => url('add')],
            'addMulti' => ['title' => '批量上传', 'href' => url('addMulti')]
        ];
    }

    public function index()
    {
        if (request()->isPost()) {
            $post_data = input('post.');
            $where = ['admin_id' => $this->adminInfo['id']];
            !empty($post_data['search_key']) && $where['title'] = ['like', '%' . $post_data['search_key'] . '%'];
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
        $builder->setTabNav($this->mediaTabs(), Media::FILE)
            ->setSearch([
                ['type' => 'select', 'name' => 'group_id', 'title' => '分组', 'options' => [0=>'全部'] + GroupService::getIdToTitle()],
            ['type' => 'text', 'name' => 'search_key', 'title' => '关键词', "tip" => '名称']
        ])
            ->addTopButton('addnew')
            ->addTopButton('addnew', ['text' => '批量上传', 'href' => url('addMulti')])
            ->addTableColumn(['title' => '名称', 'field' => 'title', 'minWidth' => 80])
            ->addTableColumn(['title' => '文件链接', 'field' => 'url', 'minWidth' => 250])
            ->addTableColumn(['title' => '标签', 'field' => 'tags', 'minWidth' => 100])
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
            ->setTabNav($this->tabList, __FUNCTION__)
            ->setPostUrl(url('savePost'))
            ->addFormItem('group_id', 'chosen', '分组', '分组', GroupService::getIdToTitle())
            ->addFormItem('file', 'file_detail', '上传文件', '上传文件')
            ->addFormItem('tags', 'text', '标签', '多个标签用半角,');

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
        $builder->setMetaTitle('编辑文件')
            ->setPostUrl(url('savePost'))
            ->addFormItem('id', 'hidden', 'ID', 'ID')
            ->addFormItem('group_id', 'chosen', '分组', '分组', GroupService::getIdToTitle())
            ->addFormItem('file', 'file_detail', '上传文件', '上传文件', $data)
            ->addFormItem('tags', 'text', '标签', '多个标签用半角,')
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
            //refresh
            invoke('\\app\\common\\event\\TaskQueue')->push([
                'delay' => 2,
                'params' => [
                    'do' => ['\\app\\common\\event\\Media', 'refresh'],
                    'bot' => $this->bot,
                    'media' => $res,
                    'media_type' => 'file'
                ]
            ]);
            $this->success('数据保存成功', $jump_to);
        }else{
            $this->error('数据保存出错');
        }
    }

    /**
     * 批量添加
     * @return mixed
     */
    public function addMulti()
    {
        if($this->request->isPost()){
            $post_data = input('post.');
            $post_data['admin_id'] = $this->adminInfo['id'];
            if(empty($post_data['files'])){
                $this->error('请上传文档!');
            }
            $images = explode('|', $post_data['files']);
            unset($post_data['files']);
            foreach ($images as $item){
                [$post_data['title'], $post_data['url']] = explode('::', $item);
                $res = $this->model->addOne($post_data);
                if($res){
                    //refresh
                    invoke('\\app\\common\\event\\TaskQueue')->push([
                        'delay' => 2,
                        'params' => [
                            'do' => ['\\app\\common\\event\\Media', 'refresh'],
                            'bot' => $this->bot,
                            'media' => $res,
                            'media_type' => 'file'
                        ]
                    ]);
                }
            }
            $this->success('数据保存成功', '/undefined');
        }
        // 使用FormBuilder快速建立表单页面
        $builder = new FormBuilder();
        $builder->setMetaTitle('批量新增文档')
            ->setTabNav($this->tabList, __FUNCTION__)
            ->setPostUrl(url('addMulti'))
            ->addFormItem('group_id', 'chosen', '分组', '分组', GroupService::getIdToTitle())
            ->addFormItem('files', 'files_detail', '上传文档', '一次性不超过10个', ['maxCount' => 10], 'required')
            ->addFormItem('tags', 'text', '标签', '多个标签用半角,');

        return $builder->show();
    }
}