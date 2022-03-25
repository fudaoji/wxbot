<?php

namespace app\admin\controller;

use app\admin\model\Bot as BotM;
use app\constants\Common;
use ky\Bot\Vlw;
use ky\Bot\Wx;

class Bot extends Base
{
    /**
     * @var BotM
     */
    protected $model;
    protected $insertAdminId = true;
    private $tabs = [];

    /**
     * 初始化
     */
    public function initialize()
    {
        parent::initialize();
        $this->model = new BotM();
        $this->tabs = [
            'index' => ['title' => '个人微信', 'href' => url('index')]
        ];
    }

    /**
     * xp协议机器人
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function index()
    {
        if (request()->isPost()) {
            $post_data = input('post.');
            $where = ['admin_id' => $this->adminInfo['id']];
            !empty($post_data['search_key']) && $where['nickname|title|uuid'] = ['like', '%' . $post_data['search_key'] . '%'];
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
        $builder->setSearch([
            ['type' => 'text', 'name' => 'search_key', 'title' => '关键词', 'placeholder' => '名称|昵称|微信号']
        ])
            //->setTabNav($this->tabs, 'index')
            ->addTopButton('addnew')
            ->addTableColumn(['title' => 'id', 'field' => 'uin', 'minWidth' => 170])
            ->addTableColumn(['title' => '类型', 'field' => 'protocol', 'type' => 'enum', 'options' => \app\constants\Bot::protocols(), 'minWidth' => 60])
            ->addTableColumn(['title' => '备注名称', 'field' => 'title', 'minWidth' => 90])
            ->addTableColumn(['title' => '头像', 'field' => 'headimgurl', 'type' => 'picture','minWidth' => 120])
            ->addTableColumn(['title' => 'appKey', 'field' => 'app_key', 'minWidth' => 90])
            ->addTableColumn(['title' => '昵称', 'field' => 'nickname', 'minWidth' => 120])
            ->addTableColumn(['title' => '操作中', 'field' => 'is_current', 'type' => 'enum', 'options' => Common::yesOrNo(), 'minWidth' => 70])
            ->addTableColumn(['title' => '登录状态', 'field' => 'alive', 'type' => 'enum', 'options' => [0 => '离线', 1 => '在线'], 'minWidth' => 70])
            ->addTableColumn(['title' => '创建时间', 'field' => 'create_time', 'type' => 'datetime', 'minWidth' => 180])
            ->addTableColumn(['title' => '操作', 'minWidth' => 150, 'type' => 'toolbar'])
            ->addRightButton('self', ['title' => '操作', 'href' => url('console', ['id' => '__data_id__']),'class' => 'layui-btn layui-btn-xs layui-btn-warm', 'minWidth' => 120])
            ->addRightButton('edit');

        return $builder->show();
    }

    /**
     * web协议机器人
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function web()
    {
        if (request()->isPost()) {
            $post_data = input('post.');
            $where = ['admin_id' => $this->adminInfo['id'], 'protocol' => \app\constants\Bot::PROTOCOL_WEB];
            !empty($post_data['search_key']) && $where['name'] = ['like', '%' . $post_data['search_key'] . '%'];
            $total = $this->model->total($where, true);
            if ($total) {
                $list = $this->model->getList(
                    [$post_data['page'], $post_data['limit']], $where,
                    [], true, true
                );
                $bot = new Wx();
                foreach ($list as $k => $v){
                    $v['alive'] = 0;
                    if($v['uuid']){
                        $res = $bot->setAppKey($v['app_key'])->getCurrentUser(['uuid' => $v['uuid']]);
                        $v['alive'] = $res['code'];
                    }
                    $list[$k] = $v;
                }
            } else {
                $list = [];
            }
            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $builder = new ListBuilder();
        $builder->setSearch([
            ['type' => 'text', 'name' => 'search_key', 'title' => '名称']
        ])
            ->setTabNav($this->tabs, 'web')
            ->addTopButton('addnew')
            ->addTableColumn(['title' => '备注名称', 'field' => 'title'])
            ->addTableColumn(['title' => 'appKey', 'field' => 'app_key'])
            ->addTableColumn(['title' => '微信昵称', 'field' => 'nickname'])
            ->addTableColumn(['title' => '操作中', 'field' => 'is_current', 'type' => 'enum', 'options' => Common::yesOrNo()])
            ->addTableColumn(['title' => '登录状态', 'field' => 'alive', 'type' => 'enum', 'options' => [0 => '离线', 1 => '在线']])
            ->addTableColumn(['title' => '最后登录时间', 'field' => 'login_time', 'type' => 'datetime', 'minWidth' => 180])
            ->addTableColumn(['title' => '创建时间', 'field' => 'create_time', 'type' => 'datetime', 'minWidth' => 180])
            ->addTableColumn(['title' => '操作', 'minWidth' => 150, 'type' => 'toolbar'])
            ->addRightButton('self', ['title' => '操作', 'href' => url('console', ['id' => '__data_id__']),'class' => 'layui-btn layui-btn-xs layui-btn-warm'])
            ->addRightButton('edit', ['title' => '登录', 'href' => url('login', ['id' => '__data_id__']),'class' => 'layui-btn layui-btn-xs'])
            ->addRightButton('edit');

        return $builder->show();
    }

    /**
     * 操作机器人
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function console(){
        $id = input('id', null);
        $data = $this->model->getOne($id);

        if (!$data) {
            $this->error('参数错误');
        }

        if($data['protocol'] == \app\constants\Bot::PROTOCOL_WEB){
            if($data['uuid']) {
                $bot = new Wx();
                $res = $bot->setAppKey($data['app_key'])->getCurrentUser(['uuid' => $data['uuid']]);
            }
            if(empty($res['code']))
                $this->error('当前机器人未登录，请先登录', url('login', ['id' => $id]));
        }
        $this->model->updateByMap(['is_current' => 1, 'admin_id' => $this->adminInfo['id']],
            ['is_current' => 0]
        );
        $this->model->updateOne(['id' => $data['id'], 'is_current' => 1]);
        $this->redirect(url('botfriend/index'));
    }

    /**
     * 机器人登录
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\Exception
     */
    public function login(){
        $id = input('id', null);
        $data = $this->model->getOne($id);

        if (!$data) {
            $this->error('参数错误');
        }
        if(request()->isPost()){
            $post_data = input('post.');
            $bot_client = new Wx(['appKey' => $data['app_key']]);
            $res = $bot_client->checkLogin([
                'uuid' => $post_data['uuid'],
                'data' => ['webhook' => request()->domain() . url('onmessage/botCallback')]
            ]);
            if(!empty($res['code'])){
                $bot = $this->model->updateOne([
                    'id' => $id,
                    'uuid' => $post_data['uuid'],
                    'nickname' => $res['data']['nick_name'],
                    'uin' => $res['data']['uin'],
                    'login_time' => time()
                ]);
                //同步好友任务
                controller('common/TaskQueue', 'event')->push([
                    'delay' => 3,
                    'params' => [
                        'do' => ['\\app\\admin\\task\\Bot', 'pullMembers'],
                        'bot' => $bot
                    ]
                ]);
                $this->success('登录成功');
            }
            $this->error("登录失败:" . $res['msg']);
            //$this->success('登录成功');
        }
        $bot = new Wx(['appKey' => $data['app_key']]);
        $res = $bot->getLoginCode();
        if(isset($res['code']) && $res['code'] == 0){
            $this->error($res['msg']);
        }
        $data['code'] = $res['data']['url'];
        $data['uuid'] = $res['data']['uuid'];
        return $this->show($data);
    }

    /**
     * 添加
     * @return mixed
     */
    public function add()
    {
        // 使用FormBuilder快速建立表单页面
        $builder = new FormBuilder();
        $builder->setMetaTitle('新增机器人')
            //->setTip("机器人添加教程：<a target='_blank' href='".(request()->domain() . url('/home/tutorial/index/?cate_id=8&help_id=23'))."'>点击查看</a>")
            ->setPostUrl(url('savePost'))
            ->addFormItem('protocol', 'radio', '类型', '机器人类型', \app\constants\Bot::protocols())
            ->addFormItem('free', 'radio', '是否免费版', '是否免费', [1 => '是', 0 => '否'])
            ->addFormItem('title', 'text', '备注名称', '30字内', [], 'required maxlength=30')
            ->addFormItem('uin', 'text', 'Wxid', 'Wxid', [], 'required maxlength=30')
            ->addFormItem('app_key', 'text', 'AppKey', 'AppKey', [], 'required')
            ->addFormItem('url', 'text', '接口地址', '接口地址', [], 'required')
            ->setFormData(['protocol' => \app\constants\Bot::PROTOCOL_VLW, 'app_key' => get_rand_char(32), 'free' => 1]);

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
        $builder->setMetaTitle('编辑机器人')
            ->setPostUrl(url('savePost'))
            ->addFormItem('id', 'hidden', 'ID', 'ID')
            ->addFormItem('protocol', 'radio', '类型', '机器人类型', \app\constants\Bot::protocols())
            ->addFormItem('free', 'radio', '是否免费版', '是否免费', [1 => '是', 0 => '否'])
            ->addFormItem('title', 'text', '备注名称', '30字内', [], 'required maxlength=30')
            ->addFormItem('uin', 'text', 'Wxid', 'Wxid', [], 'required maxlength=30')
            ->addFormItem('app_key', 'text', 'AppKey', 'AppKey', [], 'required')
            ->addFormItem('url', 'text', '接口地址', '接口地址', [], 'required')
            ->setFormData($data);

        return $builder->show();
    }

    public function savePost($jump_to = "", $data = []){
        $post_data = input('post.');
        $post_data['admin_id'] = $this->adminInfo['id'];
        if (empty($post_data[$this->pk])) {
            $res = $this->model->addOne($post_data);
        } else {
            $res = $this->model->updateOne($post_data);
        }
        if ($res) {
            $msg = '数据保存成功';
            try{
                $info = $this->model->getRobotInfo($res);
                if(is_string($info)){
                    $msg .= "，但是绑定机器人错误：".$info;
                }else if(!empty($info)){
                    $this->model->updateOne([
                        'id' => $res['id'],
                        'uin' => $info['wxid'],
                        'uuid' => $info['username'],
                        'nickname' => $info['nickname'],
                        'headimgurl' => $info['headimgurl'],
                        'alive' => 1
                    ]);
                }else{
                    $msg .= '，但系统检测到您的机器人尚未登录';
                }
            }catch (\Exception $e){
                $msg = "请检查接口地址是否填写正确";
            }
            $this->success($msg, $jump_to);
        } else {
            $this->error('数据保存出错');
        }
    }
}