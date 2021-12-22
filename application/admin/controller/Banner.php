<?php
/**
 * Script name: Banner.php
 * Created by PhpStorm.
 * Create: 2020/09/17 17:20
 * Description: 网站轮播图设置
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\controller;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class Banner extends Base
{
    /**
     * @var \app\common\model\Banner
     */
    protected $model;

    /**
     * 初始化
     */
    public function initialize()
    {
        parent::initialize();
        $this->model = model('Banner');
    }

    /**
     * 广告设置
     * @return mixed
     * @throws DataNotFoundException|ModelNotFoundException|DbException
     */
    public function index()
    {
        if (request()->isPost()) {
            $post_data = input('post.');
            $where = [];
            !empty($post_data['search_key']) && $where['name'] = ['like', '%' . $post_data['search_key'] . '%'];
            $total = $this->model->total($where, true);
            if ($total) {
                $list = $this->model->getList(
                    [$post_data['page'], $post_data['limit']], $where,
                    ['sort' => 'asc'], true, true
                );
            } else {
                $list = [];
            }
            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $builder = new ListBuilder();
        $builder->setSearch([
            ['type' => 'text', 'name' => 'search_key', 'title' => '名称']
        ])
            ->addTopButton('addnew')
            ->addTableColumn(['type' => 'checkbox', 'width' => 50])
            ->addTableColumn(['title' => '名称', 'field' => 'name'])
            ->addTableColumn(['title' => '跳转链接', 'field' => 'route'])
            ->addTableColumn(['title' => '排序', 'field' => 'sort'])
            ->addTableColumn(['title' => '状态', 'field' => 'status', 'type' => 'enum', 'options' => [0 => '隐藏', 1 => '显示']])
            ->addTableColumn(['title' => '图片', 'field' => 'images', 'type' => 'picture'])
            ->addTableColumn(['title' => '操作', 'width' => 150, 'type' => 'toolbar'])
            ->addRightButton('edit');

        return $builder->show();
    }

    /**
     * 添加广告
     * @return mixed
     */
    public function add()
    {
        // 使用FormBuilder快速建立表单页面
        $builder = new FormBuilder();
        $builder->setMetaTitle('新增轮播图')
            ->setPostUrl(url('savePost'))
            ->setTip('轮播图图片尺寸：702x290')
            ->addFormItem('name', 'text', '标题', '50字内', [], ' maxlength=50')
            ->addFormItem('images', 'picture_url', '图片', '轮播图图片尺寸：702x290', [], 'required')
            ->addFormItem('route', 'text', '跳转链接', '请到对应板块获取数据链接')
            ->addFormItem('sort', 'text', '排序', '按数字从小到大排序')
            ->addFormItem('status', 'radio', '状态', '状态', [1 => '显示', 0 => '隐藏']);


        return $builder->show();
    }

    /**
     * 编辑轮播图
     * @return mixed
     * @throws DataNotFoundException|ModelNotFoundException|DbException
     */
    public function edit()
    {
        $id = input('id', null);
        $data = $this->model->getOne($id);

        if (!$data) {
            $this->error('参数错误');
        }

        // 使用FormBuilder快速建立表单页面
        $builder = new FormBuilder();
        $builder->setMetaTitle('编辑轮播图')
            ->setPostUrl(url('savePost'))
            ->setTip('轮播图图片尺寸：702x290')
            ->addFormItem('id', 'hidden', 'ID', 'ID')
            ->addFormItem('name', 'text', '标题', '标题', [], 'maxlength=50')
            ->addFormItem('images', 'picture_url', '图片', '轮播图图片尺寸：702x290', [], 'required')
            ->addFormItem('route', 'text', '跳转链接', '请到对应板块获取数据链接')
            ->addFormItem('sort', 'number', '排序', '按数字从小到大排序')
            ->addFormItem('status', 'radio', '状态', '状态', [1 => '显示', 0 => '隐藏'])
            ->setFormData($data);

        return $builder->show();
    }
}