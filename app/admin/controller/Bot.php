<?php

namespace app\admin\controller;

use app\admin\model\Bot as BotM;
use app\admin\model\Admin as AdminM;
use app\constants\Common;
use app\constants\Bot as BotConst;
use ky\Logger;
use ky\WxBot\Driver\Xbot;

class Bot extends Bbase
{
    /**
     * @var BotM
     */
    protected $model;
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
        $this->tip = "<p>若选择扫码登陆，请先在服务器上完成框架设置。加载二维码需要几秒钟，请耐心等待。</p> 
<ul><li>西瓜框架的接口回调地址: ".request()->domain()."/bot/api/my</li>
<li>可爱猫的接口回调地址: ".request()->domain()."/bot/api/cat</li>
<!--<li>XBot框架的接口回调地址: ".request()->domain()."/bot/api/xbot</li>  
<li>vlw的接口回调地址: ".request()->domain()."/bot/api/vlw</li> 
<li>千寻的接口回调地址: ".request()->domain()."/bot/api/qianxun</li>-->
<li>详细接入教程：<i class='fa fa-hand-o-right'></i><a target='_blank' href='http://kyphp.kuryun.com/home/guide/bot/id/74/v/1.x.html'>点击查看</a></li></ul>";
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
            $where = array_merge($this->staffWhere(), [
                'protocol' => ['<>', BotConst::PROTOCOL_WEB],
                'status' => 1
            ]);

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

        $bot = $this->getCurrentBot();
        $builder = new ListBuilder();
        $builder->setSearch([
            ['type' => 'text', 'name' => 'search_key', 'title' => '关键词', 'placeholder' => '名称|昵称|微信号']
        ])
            ->setTabNav($this->tabs, 'index')
            ->setTip("当前操作机器人：" . ($bot ? $bot['title'] : '无'))
            ->addTopButton('addnew', ['title' => '扫码登录', 'href' => url('hookadd')])
            ->addTopButton('addnew', ['title' => '手动添加'])
            ->addTableColumn(['title' => 'Wxid', 'field' => 'uin', 'minWidth' => 150])
            ->addTableColumn(['title' => '微信号', 'field' => 'username', 'minWidth' => 100])
            ->addTableColumn(['title' => '类型', 'field' => 'protocol', 'type' => 'enum', 'options' => BotConst::protocols(), 'minWidth' => 90])
            ->addTableColumn(['title' => '备注名称', 'field' => 'title', 'minWidth' => 90])
            ->addTableColumn(['title' => '头像', 'field' => 'headimgurl', 'type' => 'picture','minWidth' => 100])
            ->addTableColumn(['title' => 'appKey', 'field' => 'app_key', 'minWidth' => 200])
            ->addTableColumn(['title' => '昵称', 'field' => 'nickname', 'minWidth' => 80])
            ->addTableColumn(['title' => '登录状态', 'field' => 'alive', 'type' => 'enum', 'options' => [0 => '离线', 1 => '在线'], 'minWidth' => 70])
            ->addTableColumn(['title' => '创建时间', 'field' => 'create_time', 'type' => 'datetime', 'minWidth' => 180])
            ->addTableColumn(['title' => '操作', 'minWidth' => 240, 'type' => 'toolbar'])
            ->addRightButton('self', ['title' => '操作', 'href' => url('console', ['id' => '__data_id__']),'class' => 'layui-btn layui-btn-xs layui-btn-warm', 'minWidth' => 120])
            ->addRightButton('edit')
            ->addRightButton('self', ['title' => '清空聊天记录', 'href' => url('cleanChatPost', ['id' => '__data_id__']), 'data-ajax' => 1, 'data-confirm' => 1])
            ->addRightButton('delete');

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
        $data = $this->model->where(array_merge($this->staffWhere(), ['status' => 1]))
            ->find($id);

        if (!$data) {
            $this->error('参数错误');
        }
        session(SESSION_BOT, $data);
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
            $where = array_merge($this->staffWhere(), [
                'protocol' => BotConst::PROTOCOL_WEB,
                'status' => 1
            ]);
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

        $bot = $this->getCurrentBot();
        $builder = new ListBuilder();
        $builder->setSearch([
            ['type' => 'text', 'name' => 'search_key', 'title' => '名称|昵称']
        ])
            ->setTabNav($this->tabs, 'web')
            ->setTip("当前操作机器人：" . ($bot ? $bot['title'] : '无'))
            ->addTopButton('addnew', ['href' => url('webadd')])
            ->addTableColumn(['title' => 'id', 'field' => 'uin', 'minWidth' => 170])
            ->addTableColumn(['title' => '类型', 'field' => 'protocol', 'type' => 'enum', 'options' => BotConst::protocols(), 'minWidth' => 100])
            ->addTableColumn(['title' => '备注名称', 'field' => 'title', 'minWidth' => 100])
            ->addTableColumn(['title' => '头像', 'field' => 'headimgurl', 'type' => 'picture','minWidth' => 120])
            ->addTableColumn(['title' => 'appKey', 'field' => 'app_key', 'minWidth' => 120])
            ->addTableColumn(['title' => '昵称', 'field' => 'nickname', 'minWidth' => 120])
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
            ->addFormItem('protocol', 'radio', '驱动', '驱动', BotConst::webs())
            ->addFormItem('title', 'text', '备注名称', '30字内', [], 'required maxlength=30')
            ->addFormItem('app_key', 'text', 'AppKey', '请保证当前appkey与机器人框架上的配置相同', [], 'required')
            ->addFormItem('url', 'text', '接口地址', '接口地址', [], 'required')
            ->setFormData(['protocol' => BotConst::PROTOCOL_WEB]);

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
     * @throws \think\db\exception\DbException
     */
    public function add()
    {
        $data = array_merge([
            'login_code' => 0,
            'protocol' => BotConst::PROTOCOL_MY,
            'app_key' => get_rand_char(32)
        ], $this->getConfig());

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
            ->addFormItem('uuid', 'text', 'client_id', 'xbot类型的必填')
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
        $post_data['admin_id'] = AdminM::getCompanyId($this->adminInfo);
        $login_code = $post_data['login_code'];
        unset($post_data['login_code']);
        if (empty($post_data[$this->pk])) {
            $post_data['staff_id'] = $this->adminInfo['id'];
            $res = $this->model->addOne($post_data);
        } else {
            $res = $this->model->updateOne($post_data);
        }
        if ($res) {
            if($login_code){
                switch ($post_data['protocol']){
                    case  BotConst::PROTOCOL_XBOT:
                        $action = 'reloginxbot';
                        break;
                    default:
                        $action = 'reloginmy';
                        break;
                }
                $this->success('保存成功，请继续扫码登录', url($action, ['id' => $res['id']]));
            }
            $msg = '数据保存成功';
            try{
                $info = $this->model->getRobotInfo($res);
                if(is_string($info)){
                    $msg .= "，但是绑定机器人错误：".$info;
                }else if(!empty($info)){
                    $this->model->updateByMap(['uin' => $info['wxid'], 'id' => ['<>', $res['id']]],
                        ['alive' => 0]
                    );

                    $this->model->updateOne([
                        'id' => $res['id'],
                        'uin' => $info['wxid'],
                        'username' => $info['username'],
                        'nickname' => $info['nickname'],
                        'headimgurl' => $info['headimgurl'],
                        'alive' => 1
                    ]);
                }else{
                    $msg .= '，但系统检测到您的机器人尚未登录';
                }
            }catch (\Exception $e){
                $msg = "请检查接口地址是否填写正确" . $e->getMessage();
            }
            $this->success($msg, $jump_to);
        } else {
            $this->error('数据保存出错');
        }
    }

    /**
     * 扫码添加
     * @return mixed
     * @throws \think\db\exception\DbException
     */
    public function hookAdd()
    {
        if(request()->isPost()){
            $post_data = input('post.');
            cache('botadd' . $this->adminInfo['id'], $post_data);
            switch ($post_data['protocol']){
                case  BotConst::PROTOCOL_XBOT:
                    $action = 'loginxbot';
                    break;
                default:
                    $action = 'loginmy';
                    break;
            }

            $this->success('请打开微信扫码登录', url($action, input('post.')));
        }

        $data = array_merge([
            'protocol' => BotConst::PROTOCOL_MY,
            'app_key' => get_rand_char(32)
        ], $this->getConfig());

        // 使用FormBuilder快速建立表单页面
        $builder = new FormBuilder();
        $builder->setMetaTitle('新增机器人')
            ->setTip("微信二维码加载需要几秒钟，点击提交后请耐心等待！<br>app_key和url读取顺序：上个机器人 > 设置默认值 <br> 如果需要填写新的，请先在服务器上完成框架设置并获取APPKey和http调用地址")
            ->setPostUrl(url('hookAdd'))
            ->addFormItem('protocol', 'radio', '类型', '机器人类型', BotConst::canScan())
            ->addFormItem('app_key', 'text', 'AppKey', '请保证当前appkey与机器人框架上的配置相同', [], 'required')
            ->addFormItem('url', 'text', '接口地址', '请从机器人框架上获取', [], 'required')
            ->setFormData($data);
        return $builder->show();
    }

    /**
     * 快速获取app_key和url
     * @return array|false|mixed|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DbException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getConfig(){
        if(! $config = $this->model->getOneByOrder([
            'where' => ['admin_id' => $this->adminInfo['id']],
            'order' => ['alive' => 'desc', 'update_time' => 'desc'],
            'field' => ['app_key', 'url']
        ])){
            $config = config('system.bot');
        }
        return empty($config) ? [] : $config;
    }

    /**
     * 编辑情况下的扫码登录
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function reLoginXbot(){
        $id = input('id', null);
        $data = $this->model->getOne($id);

        if (!$data) {
            $this->error('参数错误');
        }
        /**
         * @var $bot_client Xbot
         */
        $bot_client = $this->model->getRobotClient($data);
        if(request()->isPost()){
            if($data['alive']){
                //获取机器人信息
                $info = $this->model->getRobotInfo($data);
                if(!empty($info) && !is_string($info)){
                    $this->model->updateOne([
                        'id' => $data['id'],
                        'nickname' => $info['nickname'],
                        'headimgurl' => $info['headimgurl'],
                        'username' => $info['username'],
                    ]);
                }else{
                    $this->error($info);
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

        $host = explode(':', $data['url'])[0];
        $this->model->cacheLoginCode($data['protocol'], $host, ''); //flush cache
        $res = $bot_client->injectWechat();
        sleep(2);
        $client_id = $this->model->cacheLoginCode($data['protocol'], $host);
        if(empty($client_id['client_id'])){
            $this->error($res['errmsg']);
        }
        $login_code = $bot_client->getLoginCode(['client_id' => $client_id['client_id']]);
        if($login_code['code'] == 0){
            $this->error($login_code['errmsg']);
        }
        session('bot_client_id', $client_id['client_id']);
        $data['code'] = generate_qr(['text' => $login_code['data']['code']]);
        return $this->show($data, 'bot/reloginmy');
    }

    /**
     * xbot扫码登录
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \Exception
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function loginXbot(){
        $data = cache('botadd' . $this->adminInfo['id']);
        $jump = $data['jump'] ?? '';
        if (!$data) {
            $this->error('参数错误');
        }

        $data['uuid'] = '';
        /**
         * @var $bot_client Xbot
         */
        $bot_client = $this->model->getRobotClient($data);
        if(request()->isPost()){
            $data['uuid'] = session('bot_client_id');
            //获取机器人信息
            $info = $this->model->getRobotInfo($data);
            if(!empty($info) && !is_string($info)){
                if($bot = $this->model->getOneByMap(['uin' => $info['wxid'], 'admin_id' => $this->adminInfo['id']])){
                    $data = $this->model->updateOne([
                        'id' => $bot['id'],
                        'username' => $info['username'],
                        'nickname' => $info['nickname'],
                        'headimgurl' => $info['headimgurl'],
                        'alive' => 1,
                        'uuid' => $data['uuid']
                    ]);
                }else{
                    $data = $this->model->addOne([
                        'uin' => $info['wxid'],
                        'admin_id' => $this->adminInfo['id'],
                        'title' => $info['nickname'],
                        'uuid' => $info['username'],
                        'app_key' => $data['app_key'],
                        'nickname' => $info['nickname'],
                        'headimgurl' => $info['headimgurl'],
                        'protocol' => $data['protocol'],
                        'url' => $data['url'],
                        'alive' => 1
                    ]);
                }
            }else{
                $this->error($info);
            }

            //同步好友任务
            invoke('\\app\\common\\event\\TaskQueue')->push([
                'delay' => 3,
                'params' => [
                    'do' => ['\\app\\crontab\\task\\Bot', 'pullMembers'],
                    'bot' => $data
                ]
            ]);
            $this->success('登录成功', $jump);
        }

        $host = explode(':', $data['url'])[0];
        $this->model->cacheLoginCode($data['protocol'], $host, ''); //flush cache
        $res = $bot_client->injectWechat();
        sleep(2);
        $client_id = $this->model->cacheLoginCode($data['protocol'], $host);
        if(empty($client_id['client_id'])){
            $this->error($res['errmsg']);
        }
        $login_code = $bot_client->getLoginCode(['client_id' => $client_id['client_id']]);
        if($login_code['code'] == 0){
            $this->error($login_code['errmsg']);
        }
        session('bot_client_id', $client_id['client_id']);
        $data['code'] = generate_qr(['text' => $login_code['data']['code']]);
        return $this->show($data, 'bot/loginmy');
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
        $data = cache('botadd' . $this->adminInfo['id']);
        $jump = $data['jump'] ?? '';
        if (!$data) {
            $this->error('参数错误');
        }
        $data['uuid'] = '';
        $bot_client = $this->model->getRobotClient($data);
        if(request()->isPost()){
            sleep(2);
            $return = $bot_client->getRobotList();
            if($return['code'] && !empty($return['data'])){
                foreach ($return['data'] as $v){
                    if(! in_array($v['username'], \app\common\model\BotApply::getActiveWx($this->adminInfo['id']))){
                        continue;  //不在白名单中的机器人不拉取
                    }
                    if($bot = $this->model->getOneByMap(['uin' => $v['wxid'], 'staff_id' => $this->adminInfo['id']])){
                        $data = $this->model->updateOne([
                            'id' => $bot['id'],
                            'username' => $v['username'],
                            'nickname' => $v['nickname'],
                            'headimgurl' => $v['headimgurl'],
                            'alive' => 1
                        ]);
                    }else{
                        $data = $this->model->addOne([
                            'uin' => $v['wxid'],
                            'admin_id' => AdminM::getCompanyId($this->adminInfo),
                            'staff_id' => $this->adminInfo['id'],
                            'title' => $v['nickname'],
                            'username' => $v['username'],
                            'app_key' => $data['app_key'],
                            'nickname' => $v['nickname'],
                            'headimgurl' => $v['headimgurl'],
                            'protocol' => $data['protocol'],
                            'url' => $data['url'],
                            'alive' => 1
                        ]);
                    }
                    //把其他机器人下线
                    $this->model->updateByMap(['uin' => $data['uin'], 'id' => ['<>', $data['id']]],
                        ['alive' => 0]
                    );
                    //同步好友任务
                    invoke('\\app\\common\\event\\TaskQueue')->push([
                        'delay' => 3,
                        'params' => [
                            'do' => ['\\app\\crontab\\task\\Bot', 'pullMembers'],
                            'bot' => $data
                        ]
                    ]);
                }
                $this->success('登录成功',$jump);
            }else{
                $this->success('登录失败：' . $bot_client->getError());
            }
        }
        //退掉遗留的弹框
        // $bot_client->exitLoginCode();
        $res = $bot_client->getLoginCode();
        // Log::write("获取微信二维码：".json_encode($res));
        if($res['code'] == 0){
            $this->error($res['errmsg']);
        }
        if(empty($res['data'])){
            // $bot_client->exitLoginCode();
        }
        $data['code'] = base64_to_pic($res['data']);
        return $this->show($data);
    }

    /**
     * 编辑情况下的扫码登录
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function reLoginMy(){
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
                if(!empty($info) && !is_string($info)){
                    $this->model->updateOne([
                        'id' => $data['id'],
                        'username' => $info['username'],
                        'nickname' => $info['nickname'],
                        'headimgurl' => $info['headimgurl']
                    ]);
                }

                //同步好友任he
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

        //退掉遗留的弹框
        //$bot_client->exitLoginCode();
        $res = $bot_client->getLoginCode();
        if($res['code'] == 0){
            $this->error($res['errmsg']);
        }
        if(empty($res['data'])){
            // $bot_client->exitLoginCode();
        }
        $data['code'] = base64_to_pic($res['data']);
        return $this->show($data);
    }

    /**
     * 清空聊天记录
     * @throws \think\Exception
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function cleanChatPost()
    {
        $id = input('id', null);
        $data = $this->model->getOneByMap(['id' => $id, 'admin_id' => $this->adminInfo['id']], true, true);

        if (!$data) {
            $this->error('参数错误');
        }
        $client = $this->model->getRobotClient($data);
        $res = $client->cleanChatHistory([
            'robot_wxid' => $data['uin'],
            'uuid' => $data['uuid']
        ]);
        if($res['code']){
            $this->success('操作成功');
        }
        $this->error('操作失败：' . $res['errmsg']);
    }
}