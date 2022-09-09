<?php

namespace app\admin\controller;

use app\admin\model\Bot as BotM;
use app\constants\Common;
use app\constants\Bot as BotConst;
use ky\Logger;

class Bot extends Base
{
    /**
     * @var BotM
     */
    protected $model;
    protected $insertAdminId = true;
    private $tabs = [];
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
        $this->model = new BotM();
        $this->tabs = [
            'index' => ['title' => 'PC机器人', 'href' => url('index')],
            'web' => ['title' => 'Web机器人', 'href' => url('web')]
        ];
        $this->tip = "<p>若选择扫码登陆，请先在服务器上完成框架设置</p> 
<ul><li>我的框架的接口回调地址: ".request()->domain()."/bot/api/my</li> 
<li>vlw的接口回调地址: ".request()->domain()."/bot/api/vlw</li> 
<li>可爱猫的接口回调地址: ".request()->domain()."/bot/api/cat</li>
<li>千寻的接口回调地址: ".request()->domain()."/bot/api/qianxun</li>
<li>详细接入教程：<a target='_blank' href='http://kyphp.kuryun.com/home/guide/bot/id/74/v/1.x.html'>点击查看</a></li></ul>";
    }

    /**
     * 机器人
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DbException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function index()
    {
        if (request()->isPost()) {
            $post_data = input('post.');
            $where = ['admin_id' => $this->adminInfo['id'],'protocol' => ['<>', \app\constants\Bot::PROTOCOL_WEB]];
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

        $bot = $this->model->getOneByMap(['admin_id' => $this->adminInfo['id'], 'is_current' => 1]);
        $builder = new ListBuilder();
        $builder->setSearch([
            ['type' => 'text', 'name' => 'search_key', 'title' => '关键词', 'placeholder' => '名称|昵称|微信号']
        ])
            ->setTabNav($this->tabs, 'index')
            ->setTip("当前操作机器人：" . ($bot ? $bot['title'] : '无'))
            ->addTopButton('addnew')
            //->addTopButton('addnew', ['title' => '新增Web机器', 'href' => url('webadd')])
            ->addTableColumn(['title' => 'id', 'field' => 'uin', 'minWidth' => 170])
            ->addTableColumn(['title' => '类型', 'field' => 'protocol', 'type' => 'enum', 'options' => \app\constants\Bot::protocols(), 'minWidth' => 100])
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
     * 操作机器人
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function console(){
        $id = input('id', null);
        $data = $this->model->getOne($id);

        if (!$data) {
            $this->error('参数错误');
        }

        $this->model->updateByMap(['is_current' => 1, 'admin_id' => $this->adminInfo['id']],
            ['is_current' => 0]
        );
        $this->model->updateOne(['id' => $data['id'], 'is_current' => 1]);
        $this->redirect(url('botfriend/index'));
    }

    /**
     * web协议机器人
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DbException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function web()
    {
        if (request()->isPost()) {
            $post_data = input('post.');
            $where = ['admin_id' => $this->adminInfo['id'], 'protocol' => \app\constants\Bot::PROTOCOL_WEB];
            !empty($post_data['search_key']) && $where['nickname|title|uuid'] = ['like', '%' . $post_data['search_key'] . '%'];
            $total = $this->model->total($where, true);
            if ($total) {
                $list = $this->model->getList(
                    [$post_data['page'], $post_data['limit']], $where,
                    [], true, true
                );
                foreach ($list as $k => $v){
                    $bot_client = $this->model->getRobotClient($v);
                    $v['alive'] = 0;
                    if($v['uuid']){
                        $res = $bot_client->getCurrentUser(['uuid' => $v['uuid']]);
                        $v['alive'] = $res['code'];
                    }
                    $list[$k] = $v;
                }
            } else {
                $list = [];
            }
            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $bot = $this->model->getOneByMap(['admin_id' => $this->adminInfo['id'], 'is_current' => 1]);
        $builder = new ListBuilder();
        $builder->setSearch([
            ['type' => 'text', 'name' => 'search_key', 'title' => '名称|昵称']
        ])
            ->setTabNav($this->tabs, 'web')
            ->setTip("当前操作机器人：" . ($bot ? $bot['title'] : '无'))
            ->addTopButton('addnew', ['href' => url('webadd')])
            ->addTableColumn(['title' => 'id', 'field' => 'uin', 'minWidth' => 170])
            ->addTableColumn(['title' => '类型', 'field' => 'protocol', 'type' => 'enum', 'options' => \app\constants\Bot::protocols(), 'minWidth' => 100])
            ->addTableColumn(['title' => '备注名称', 'field' => 'title', 'minWidth' => 100])
            ->addTableColumn(['title' => '头像', 'field' => 'headimgurl', 'type' => 'picture','minWidth' => 120])
            ->addTableColumn(['title' => 'appKey', 'field' => 'app_key', 'minWidth' => 120])
            ->addTableColumn(['title' => '昵称', 'field' => 'nickname', 'minWidth' => 120])
            ->addTableColumn(['title' => '操作中', 'field' => 'is_current', 'type' => 'enum', 'options' => Common::yesOrNo(), 'minWidth' => 70])
            ->addTableColumn(['title' => '登录状态', 'field' => 'alive', 'type' => 'enum', 'options' => [0 => '离线', 1 => '在线'], 'minWidth' => 70])
            ->addTableColumn(['title' => '创建时间', 'field' => 'create_time', 'type' => 'datetime', 'minWidth' => 180])
            ->addTableColumn(['title' => '操作', 'minWidth' => 150, 'type' => 'toolbar'])
            ->addRightButton('self', ['title' => '操作', 'href' => url('console', ['id' => '__data_id__']),'class' => 'layui-btn layui-btn-xs layui-btn-warm'])
            ->addRightButton('edit', ['title' => '登录', 'href' => url('login', ['id' => '__data_id__']),'class' => 'layui-btn layui-btn-xs'])
            ->addRightButton('edit', ['href' => url('webEdit', ['id' => '__data_id__'])]);

        return $builder->show();
    }

    /**
     * 添加web微信
     * @return mixed
     * @throws \think\Exception
     */
    public function webAdd()
    {
        if(request()->isPost()){
            $post_data = input('post.');
            if($this->model->total(['protocol' => BotConst::PROTOCOL_WEB, 'app_key' => $post_data['app_key']])){
                $this->error('Appkey已被占用，请更换');
            }
            $post_data['admin_id'] = $this->adminInfo['id'];
            $res = $this->model->addOne($post_data);
            $this->success('保存成功，请继续扫码登录', url('login', ['id' => $res['id']]));
        }

        $tip = "请先从对应驱动的服务端获取appkey和接口地址";
        // 使用FormBuilder快速建立表单页面
        $builder = new FormBuilder();
        $builder->setMetaTitle('新增web微信机器人')
            ->setTip($tip)
            ->setPostUrl(url('webAdd'))
            ->addFormItem('protocol', 'radio', '驱动', '驱动', \app\constants\Bot::webs())
            ->addFormItem('title', 'text', '备注名称', '30字内', [], 'required maxlength=30')
            ->addFormItem('app_key', 'text', 'AppKey', '请保证当前appkey与机器人框架上的配置相同', [], 'required')
            ->addFormItem('url', 'text', '接口地址', '接口地址', [], 'required')
            ->setFormData(['protocol' => \app\constants\Bot::PROTOCOL_WEB]);

        return $builder->show();
    }

    public function webEdit()
    {
        $id = input('id', null);
        $data = $this->model->getOne($id);

        if (!$data) {
            $this->error('参数错误');
        }
        if(request()->isPost()){
            $post_data = input('post.');
            if($this->model->total(['id' => ['<>', $id], 'protocol' => BotConst::PROTOCOL_WEB, 'app_key' => $post_data['app_key']])){
                $this->error('Appkey已被占用，请更换');
            }
            $this->model->updateOne($post_data);
            $this->success('保存成功', '/undefined');
        }

        $tip = "请先从对应驱动的服务端获取appkey和接口地址";
        // 使用FormBuilder快速建立表单页面
        $builder = new FormBuilder();
        $builder->setMetaTitle('编辑web微信机器人')
            ->setTip($tip)
            ->setPostUrl(url('webEdit'))
            ->addFormItem('id', 'hidden', 'ID', 'ID')
            ->addFormItem('title', 'text', '备注名称', '30字内', [], 'required maxlength=30')
            ->addFormItem('app_key', 'text', 'AppKey', '请保证当前appkey与机器人框架上的配置相同', [], 'required')
            ->addFormItem('url', 'text', '接口地址', '接口地址', [], 'required')
            ->setFormData($data);

        return $builder->show();
    }

    /**
     * 机器人登录
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\Exception
     */
    public function login(){
        $id = input('id', null);
        $data = $this->model->getOne($id);

        if (!$data) {
            $this->error('参数错误');
        }
        $bot_client = $this->model->getRobotClient($data);
        if(request()->isPost()){
            $post_data = input('post.');
            $res = $bot_client->checkLogin([
                'uuid' => $post_data['uuid'],
                'webhook' => request()->domain() . url('/bot/webgo')
            ]);

            if(!empty($res['code'])){
                $bot = $this->model->updateOne([
                    'id' => $id,
                    'uuid' => $post_data['uuid'],
                    'nickname' => $res['data']['nick_name'],
                    'uin' => $res['data']['uin'],
                    'login_time' => time(),
                    'alive' => 1
                ]);
                //同步好友任务
                invoke('\\app\\common\\event\\TaskQueue')->push([
                    'delay' => 3,
                    'params' => [
                        'do' => ['\\app\\crontab\\task\\Bot', 'pullMembers'],
                        'bot' => $bot
                    ]
                ]);
                $this->success('登录成功');
            }
            $this->error("登录失败:" . $res['msg']);
        }

        $res = $bot_client->getLoginCode();
        if($res['code'] == 0){
            $this->error($res['errmsg']);
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
        $data = [
            'login_code' => 0,
            'protocol' => \app\constants\Bot::PROTOCOL_VLW,
            'app_key' => get_rand_char(32)
        ];
        // 使用FormBuilder快速建立表单页面
        $builder = new FormBuilder();
        $builder->setMetaTitle('新增机器人')
            ->setTip($this->tip)
            ->setPostUrl(url('savePost'))
            ->addFormItem('protocol', 'radio', '类型', '机器人类型', BotConst::hooks())
            ->addFormItem('title', 'text', '备注名称', '30字内', [], 'required maxlength=30')
            ->addFormItem('uin', 'text', 'Wxid', '微信在机器人框架登陆后可获取', [], 'required maxlength=30')
            ->addFormItem('app_key', 'text', 'AppKey', '请保证当前appkey与机器人框架上的配置相同', [], 'required')
            ->addFormItem('url', 'text', '接口地址', '请从机器人框架上获取', [], 'required')
            ->addFormItem('login_code', 'radio', '扫码登录', '是否扫码登录', [0 => '否', 1 => '是'])
            ->setFormData($data);

        return $builder->show();
    }

    public function edit()
    {
        $id = input('id', null);
        $data = $this->model->getOne($id);

        if (!$data) {
            $this->error('参数错误');
        }
        $data['login_code'] = 0;
        // 使用FormBuilder快速建立表单页面
        $builder = new FormBuilder();
        $builder->setMetaTitle('编辑机器人')
            ->setTip($this->tip)
            ->setPostUrl(url('savePost'))
            ->addFormItem('id', 'hidden', 'ID', 'ID')
            ->addFormItem('protocol', 'radio', '类型', '机器人类型', BotConst::hooks())
            ->addFormItem('title', 'text', '备注名称', '30字内', [], 'required maxlength=30')
            ->addFormItem('uin', 'text', 'Wxid', '微信在机器人框架登陆后可获取', [], 'required maxlength=30')
            ->addFormItem('app_key', 'text', 'AppKey', '请保证当前appkey与机器人框架上的配置相同', [], 'required')
            ->addFormItem('url', 'text', '接口地址', '请从机器人框架上获取', [], 'required')
            ->addFormItem('login_code', 'radio', '扫码登录', '是否扫码登录', [0 => '否', 1 => '是'])
            ->setFormData($data);

        return $builder->show();
    }

    public function savePost($jump_to = "/undefined", $data = []){
        $post_data = input('post.');
        $post_data['admin_id'] = $this->adminInfo['id'];
        $login_code = $post_data['login_code'];
        unset($post_data['login_code']);
        if (empty($post_data[$this->pk])) {
            $res = $this->model->addOne($post_data);
        } else {
            $res = $this->model->updateOne($post_data);
        }
        if ($res) {
            if($login_code){
                $this->success('保存成功，请继续扫码登录', url('loginmy', ['id' => $res['id']]));
            }
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

    /**
     * My扫码登录
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\Exception
     */
    public function loginMy(){
        $id = input('id', null);
        $data = $this->model->getOne($id);

        if (!$data) {
            $this->error('参数错误');
        }
        $bot_client = $this->model->getRobotClient($data);
        if(request()->isPost()){
            if($data['alive']){
                //获取机器人信息
                $info = $this->model->getRobotInfo($data);
                Logger::error($info);
                if(!empty($info) && !is_string($info)){
                    $this->model->updateOne([
                        'id' => $data['id'],
                        'uuid' => $info['username'],
                        'nickname' => $info['nickname'],
                        'headimgurl' => $info['headimgurl']
                    ]);
                }

                //同步好友任务
                invoke('\\app\\common\\event\\TaskQueue')->push([
                    'delay' => 3,
                    'params' => [
                        'do' => ['\\app\\crontab\\task\\Bot', 'pullMembers'],
                        'bot' => $data
                    ]
                ]);
                $this->success('登录成功');
            }
        }

        $res = $bot_client->getLoginCode();
        if($res['code'] == 0){
            $this->error($res['errmsg']);
        }
        $data['code'] = base64_to_pic($res['data']);
        return $this->show($data);
    }
}