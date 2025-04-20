<?php
/**
 * Script name: Blogcolumn.php
 * Created by PhpStorm.
 * Create: 2020/09/17 17:20
 * Description: 博客分类
 * Author: fudaoji<fdj@kuryun.cn>
 */
namespace app\admin\controller;
use app\common\service\BlogColumn as BlogService;
use app\common\model\Blog as M;
use ky\SEOGenerator;

class Blog extends Base
{
    /**
     * @var M
     */
    protected $model;

    /**
     * 初始化
     * @return mixed
     */
    public function initialize()
    {
        parent::initialize();
        $this->model = new M();
    }

    /**
     * 列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        if(request()->isPost()){
            $post_data = input('post.');
            $where = [];
            $total = $this->model->total($where, true);
            if($total){
                $list = $this->model->getList(
                    [$post_data['page'], $post_data['limit']], $where,
                    ['id' => 'desc'], true, true
                );
            }else{
                $list = [];
            }
            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $builder = new ListBuilder();
        $builder
            ->addTopButton('addnew')
            ->addTableColumn(['title' => 'ID', 'field' => 'id', 'minWidth' => 60])
            ->addTableColumn(['title' => '标题', 'field' => 'title', 'minWidth' => 100])
            ->addTableColumn(['title' => '简介', 'field' => 'desc', 'minWidth' => 200])
            ->addTableColumn(['title' => '分类', 'field' => 'columns', 'minWidth' => 100])
            ->addTableColumn(['title' => 'SEO Keywords', 'field' => 'seo_keywords', 'minWidth' => 100])
            ->addTableColumn(['title' => 'SEO Description', 'field' => 'seo_description', 'minWidth' => 150])
            ->addTableColumn(['title' => '发布时间', 'field' => 'create_time', 'type' => 'datetime', 'minWidth' => 170])
            ->addTableColumn(['title' => '更新时间', 'field' => 'update_time', 'type' => 'datetime', 'minWidth' => 170])
            ->addTableColumn(['title' => '是否显示', 'field' => 'status', 'type' => 'switch', 'minWidth' => 100])
            ->addTableColumn(['title' => '操作', 'minWidth' => 140, 'type' => 'toolbar'])
            ->addRightButton('edit')
            ->addRightButton('delete');
        return $builder->show();
    }

    /**
     * edit
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit()
    {
        $id = input('id', null);
        $data = $this->model->getOne($id, true);

        if(!$data) {
            $this->error('参数错误');
        }
        if($data['columns']){
            $data['columns'] = explode(',', $data['columns']);
        }

        // 使用FormBuilder快速建立表单页面
        $builder = new FormBuilder();
        $builder->setMetaTitle('编辑')      //设置页面标题
            ->setPostUrl(url('savepost'))
            ->addFormItem('id', 'hidden', 'ID', 'ID', [], 'required')
            ->addFormItem('title', 'text', '名称', '50字内', [], 'required minlength=2 maxlength=50')
            ->addFormItem('desc', 'textarea', '简介', '200字以内', [], 'maxlength=200')
            ->addFormItem('columns', 'chosen_multi', '标签', '标签', BlogService::getTitleToTitle(), 'required')
            ->addFormItem('status', 'radio', '前台显示', '前台是否显示', [1 => '显示', 0 => '隐藏'], 'required')
            ->addFormItem('seo_keywords', 'text', 'SEO Keywords', '多个关键词用,分割', [], 'maxlength=200')
            ->addFormItem('seo_description', 'textarea', 'SEO Description', '200字以内', [], 'maxlength=200')
            ->addFormItem('content', 'ueditor', '内容', '内容', [], 'required')
            ->setFormData($data);

        return $builder->show();
    }

    /**
     * edit
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function add()
    {
        // 使用FormBuilder快速建立表单页面
        $builder = new FormBuilder();
        $builder->setMetaTitle('新增')      //设置页面标题
            ->setPostUrl(url('savepost'))
            ->addFormItem('title', 'text', '名称', '50字内', [], 'required minlength=2 maxlength=50')
            ->addFormItem('desc', 'textarea', '简介', '200字以内', [], 'maxlength=200')
            ->addFormItem('columns', 'chosen_multi', '标签', '标签', BlogService::getTitleToTitle(), 'required')
            ->addFormItem('status', 'radio', '前台显示', '前台是否显示', [1 => '显示', 0 => '隐藏'], 'required')
            ->addFormItem('content', 'ueditor', '内容', '内容', [], 'required')
            ->setFormData(['status' => 1]);

        return $builder->show();
    }

    public function savePost($jump_to = '/undefined', $data = [])
    {
        $post_data = $data ? $data : input('post.');
        if(empty($post_data[$this->pk])){
            $res = $this->model->addOne($post_data);
        }else {
            $res = $this->model->updateOne($post_data);
        }

        if($res){
            //延迟生成seo
            invoke('\\app\\common\\event\\TaskQueue')->push([
                'delay' => 3,
                'params' => [
                    'do' => ['\\app\\common\\event\\Blog', 'generateSEO'],
                    'blog' => $res
                ]
            ]);
            $this->success('数据保存成功', $jump_to);
        }else{
            $this->error('数据保存出错');
        }
    }
}