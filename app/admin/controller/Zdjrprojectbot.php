<?php

namespace app\admin\controller;
use app\common\model\zdjr\Project;
use app\common\model\zdjr\ProjectBot;
use app\constants\Common;
use think\facade\Db;

class Zdjrprojectbot extends Botbase
{
    /**
     * @var ProjectBot
     */
    protected $model;
    /**
     * @var \app\admin\model\Bot
     */
    private $botM;
    /**
     * @var array
     */
    private $tabList;
    /**
     * @var Project
     */
    private $projectM;

    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->model = new ProjectBot();
        $this->projectM = new Project();
        $this->botM = new \app\admin\model\Bot();
        $this->tabList = [
            'binded' => ['title' => '已绑', 'href' => url('index')],
            'unbind' => ['title' => '未绑', 'href' => url('unbind')]
        ];
    }

    /**
     * 未绑
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DbException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function unbind()
    {
        if (request()->isPost()) {
            $post_data = input('post.');
            $exist = $this->model->getField('bot_id', ['admin_id' => $this->adminInfo['id']]);
            $where = ['admin_id' => $this->adminInfo['id']];
            count($exist) && $where['id'] = ['notin', $exist];
            !empty($post_data['search_key']) && $where['nickname|title|uuid'] = ['like', '%' . $post_data['search_key'] . '%'];
            $total = $this->botM->total($where, true);
            if ($total) {
                $list = $this->botM->getList(
                    [$post_data['page'], $post_data['limit']], $where,
                    [], true, true
                );
            } else {
                $list = [];
            }
            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $builder = new ListBuilder();
        $builder->setSearch([
            ['type' => 'text', 'name' => 'search_key', 'title' => '关键词', 'placeholder' => '名称|昵称|微信号']
        ])
            ->setTabNav($this->tabList, 'unbind')
            ->addTableColumn(['title' => 'Wxid', 'field' => 'uin', 'minWidth' => 190])
            ->addTableColumn(['title' => '备注名称', 'field' => 'title', 'minWidth' => 90])
            ->addTableColumn(['title' => '头像', 'field' => 'headimgurl', 'type' => 'picture','minWidth' => 120])
            ->addTableColumn(['title' => '昵称', 'field' => 'nickname', 'minWidth' => 100])
            ->addTableColumn(['title' => '登录状态', 'field' => 'alive', 'type' => 'enum', 'options' => [0 => '离线', 1 => '在线'], 'minWidth' => 70])
            ->addTableColumn(['title' => '操作', 'minWidth' => 150, 'type' => 'toolbar'])
            ->addRightButton('edit', ['title' => '绑定项目']);

        return $builder->show();
    }

    /**
     * 列表
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function index(){
        if (request()->isPost()) {
            $post_data = input('post.');
            $where = ['bot.admin_id' => $this->adminInfo['id']];
            !empty($post_data['search_key']) && $where['bot.title|project.title'] = ['like', '%' . $post_data['search_key'] . '%'];

            $params = [
                'alias' => 'bot',
                'join' => [
                    ['zdjr_project_bot pb', 'pb.bot_id=bot.id'],
                    ['zdjr_project project', 'pb.project_id=project.id']
                ],
                'where' => $where,
                'refresh' => true
            ];
            $total = $this->botM->totalJoin($params);
            if ($total) {
                $list = $this->botM->getListJoin(array_merge($params, [
                    'field' => ['bot.*', 'project.title as project_title','pb.create_time', 'pb.id'],
                    'limit' => [$post_data['page'], $post_data['limit']]
                ]));
            } else {
                $list = [];
            }
            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $builder = new ListBuilder();
        $builder->setSearch([
                ['type' => 'text', 'name' => 'search_key', 'title' => '关键词', 'placeholder' => '机器人名称、项目名称']
            ])
            ->setTabNav($this->tabList, 'binded')
            ->addTableColumn(['title' => '机器人名称', 'field' => 'title', 'minWidth' => 100])
            ->addTableColumn(['title' => '绑定项目', 'field' => 'project_title', 'minWidth' => 100])
            ->addTableColumn(['title' => '绑定时间', 'field' => 'create_time', 'minWidth' => 120,'type' => 'datetime'])
            ->addTableColumn(['title' => '操作', 'minWidth' => 200, 'type' => 'toolbar'])
            ->addRightButton('delete', ['title' => '解绑']);
        return $builder->show();
    }

    /**
     * 编辑
     * @return mixed
     * @throws \think\Exception
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function edit(){
        $id = input('id', null);
        $data = $this->botM->getOneByMap(['id' => $id, 'admin_id' => $this->adminInfo['id']], true, true);

        if (!$data) {
            $this->error('参数错误');
        }

        $data['bot_id'] = $id;
        $builder = new FormBuilder();
        $builder->setPostUrl(url('savePost'))
            ->addFormItem('bot_id', 'hidden', 'id', 'id')
            ->addFormItem('bot', 'static', '机器人', $data['title'])
            ->addFormItem('project_id', 'chosen', '选择项目', '选择项目', $this->projectM->getProjects(['admin_id' => $this->adminInfo['id']]), 'required maxlength=50')
            ->setFormData($data);

        return $builder->show();
    }
}