<?php
/**
 * Script name: Blogcolumn.php
 * Created by PhpStorm.
 * Create: 2020/09/17 17:20
 * Description: 博客分类
 * Author: fudaoji<fdj@kuryun.cn>
 */
namespace app\admin\controller;
use app\common\model\BlogColumn as M;
use think\facade\Db;
use \app\common\service\BlogColumn as ColumnService;

class Blogcolumn extends Base
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
                    ['sort' => 'desc'], true, true
                );
            }else{
                $list = [];
            }
            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $builder = new ListBuilder();
        $builder
            ->addTopButton('addnew')
            ->addTableColumn(['title' => 'ID', 'field' => 'id'])
            ->addTableColumn(['title' => '标题', 'field' => 'title'])
            ->addTableColumn(['title' => '是否显示', 'field' => 'status', 'type' => 'switch'])
            ->addTableColumn(['title' => '排序', 'field' => 'sort'])
            ->addTableColumn(['title' => '更新时间', 'field' => 'update_time', 'type' => 'datetime'])
            ->addTableColumn(['title' => '操作', 'width' => 150, 'type' => 'toolbar'])
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
        $data = $this->model->getOne($id);

        if(!$data) {
            $this->error('参数错误');
        }

        // 使用FormBuilder快速建立表单页面
        $builder = new FormBuilder();
        $builder->setMetaTitle('编辑')      //设置页面标题
        ->setPostUrl(url('savepost'))
            ->addFormItem('id', 'hidden', 'ID', 'ID', [], 'required')
            ->addFormItem('title', 'text', '名称', '20字内', [], 'required minlength=2 maxlength=20')
            ->addFormItem('sort', 'number', '排序', '数字越大越靠前', [], 'required min=0')
            ->addFormItem('status', 'radio', '前台显示', '前台是否显示', [1 => '显示', 0 => '隐藏'], 'required')
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
            ->addFormItem('title', 'text', '名称', '20字内', [], 'required minlength=2 maxlength=20')
            ->addFormItem('sort', 'number', '排序', '数字越大越靠前', [], 'required min=0')
            ->addFormItem('status', 'radio', '前台显示', '前台是否显示', [1 => '显示', 0 => '隐藏'], 'required')
            ->setFormData(['status' => 1, 'sort' => 0]);

        return $builder->show();
    }

    public function savePost($jump_to = '/undefined', $data = [])
    {
        $post_data = $data ? $data : input('post.');
        if(empty($post_data[$this->pk])){
            $res = $this->model->addOne($post_data);
        }else {
            $ori = $this->model->getOne($post_data[$this->pk]);
            $res = $this->model->updateOne($post_data);
            if($res && $ori['title'] != $res['title']){
                ColumnService::afterUpdateTitle($ori, $res);
            }
        }

        if($res){
            $this->success('数据保存成功', $jump_to);
        }else{
            $this->error('数据保存出错');
        }
    }
}