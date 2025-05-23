<?php

namespace app\admin\controller;

use app\admin\model\Bot as BotM;
use app\admin\model\Admin as AdminM;
use app\common\model\Upload;
use app\common\service\AdminLog as AdminLogService;
use app\constants\Common;
use app\constants\Bot as BotConst;
use ky\Logger;
use ky\WxBot\Driver\Extian;
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
    private $xbotTip;

    /**
     * 初始化
     */
    public function initialize()
    {
        $this->needAid = false;
        $this->needStaffId = true;

        parent::initialize();
        $this->model = new BotM();
        $this->tabs = $this->getActiveDrivers();
        $customDomains = $this->adminInfo['username'].".frp.kuryun.cn";
        $this->tip = " <ul><li>【重要】： 本地主机部署e小天，需要安装内网穿透服务（具体教程点击下方新手必看教程）。内网穿透服务的配置为：<br> name=<bold>".$this->adminInfo['username']."</bold>, customDomains=".$customDomains."</li>
 <li>本地主机部署e小天:  下方服务器地址填写".$customDomains.":8203。如果是云服务，则下方服务器地址填写 云服务器ip:8203。</li>
 <li>e小天的接口回调地址: <a href='javascript:;' id='url-extian' class='js-clipboard' data-clipboard-target='#url-extian'>".request()->domain()."/bot/api/extian</a>。</li>
<li>新手必看教程：<i class='fa fa-hand-o-right'></i><a target='_blank' href='https://doc.kuryun.com/web/#/642350114/229559988'>点击查看</a></li>。</ul>";
        $this->xbotTip = "<!--<p>若选择扫码登陆，请先在服务器上完成框架设置。加载二维码需要几秒钟，请耐心等待。</p>--> 
<li>xbot企微的接口回调地址: <a href='javascript:;' id='url-xbotcom' class='js-clipboard' data-clipboard-target='#url-xbotcom'>".request()->domain()."/bot/api/xbotcom</a></li>
<li>新手必看教程：<i class='fa fa-hand-o-right'></i><a target='_blank' href='https://doc.kuryun.com/web/#/642350114/229559988'>点击查看</a></li></ul>";
    }

    private function getActiveDrivers(){
        $drivers = explode(',', config('system.bot.drivers'));
        foreach ($drivers as $driver){
            $this->tabs[$driver] = [
                'title' => BotConst::hooks($driver),
                'href' => url('index', ['tab' => $driver])
            ];
        }
        return $this->tabs;
    }

    /**
     * 同步数据
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function syncBots(){
        $bots = \app\common\service\Bot::model()->getAll([
            'where' => ['staff_id' => $this->adminInfo['id'], 'status' => 1]
        ])->toArray();
        foreach ($bots as $bot){
            $res = $this->model->getRobotList($bot);
            if(is_string($res)){
                continue;
            }
            foreach ($res as $item){
                $bot = $this->model->getOneByMap(['uin' => $item['uin']], true, true);
                if($bot){
                    $res = $this->model->updateOne([
                        'id' => $bot['id'],
                        'username' => $item['username'],
                        'nickname' => $item['nickname'],
                        'headimgurl' => $item['headimgurl'],
                        'alive' => 1,
                        'uuid' => $item['uuid'] ?? 0
                    ]);
                }else{
                    $res = $this->model->addOne(array_merge(['protocol' => BotConst::PROTOCOL_EXTIAN], [
                        'uin' => $item['uin'],
                        'admin_id' => AdminM::getCompanyId($this->adminInfo),
                        'staff_id' => $this->adminInfo['id'],
                        'username' => $item['username'],
                        'title' => $item['nickname'],
                        'nickname' => $item['nickname'],
                        'headimgurl' => $item['headimgurl'],
                        'alive' => 1,
                        'uuid' => $item['uuid'] ?? 0
                    ]));
                }
            }
        }
        $this->success('同步成功！');
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
        $tab = input('tab', BotConst::PROTOCOL_EXTIAN);
        if (request()->isPost()) {
            $post_data = input('post.');
            $where = array_merge($this->staffWhere(), [
                'protocol' => $tab,
                //'status' => 1
            ]);

            !empty($post_data['search_key']) && $where['nickname|title|uin'] = ['like', '%' . $post_data['search_key'] . '%'];
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
            ['type' => 'text', 'name' => 'search_key', 'title' => '关键词', 'placeholder' => '名称|昵称|wxid']
        ])
            ->setDataUrl(url('index', ['tab' => $tab]))
            ->setTabNav($this->tabs, $tab)
            ->setTip("当前操作机器人：" . ($bot ? $bot['title'] : '无'))
            ->addTopButton('addnew', ['title' => '拉取已登录微信', 'href' => url('add', ['tab' => $tab]),'class' => "layui-btn-warm"])
            ->addTopButton('addnew', ['title' => '扫码登录微信', 'href' => url('hookadd')])
            ->addTableColumn(['title' => 'PID|client_id', 'field' => 'uuid', 'minWidth' => 90])
            ->addTableColumn(['title' => 'Wxid', 'field' => 'uin', 'minWidth' => 150])
            ->addTableColumn(['title' => '微信号', 'field' => 'username', 'minWidth' => 100])
            ->addTableColumn(['title' => '备注名称', 'field' => 'title', 'minWidth' => 90])
            ->addTableColumn(['title' => '头像', 'field' => 'headimgurl', 'type' => 'picture','minWidth' => 100])
            ->addTableColumn(['title' => '所属员工', 'type' => 'enum', 'field' => 'staff_id', 'options' => AdminM::getTeamIdToName($this->adminInfo), 'minWidth' => 90])
            ->addTableColumn(['title' => '昵称', 'field' => 'nickname', 'minWidth' => 80])
            ->addTableColumn(['title' => '登录状态', 'field' => 'alive', 'type' => 'enum', 'options' => [0 => '离线', 1 => '在线'], 'minWidth' => 70])
            ->addTableColumn(['title' => '启用状态', 'field' => 'status', 'type' => 'enum', 'options' => Common::status(), 'minWidth' => 90])
            ->addTableColumn(['title' => '创建时间', 'field' => 'create_time', 'type' => 'datetime', 'minWidth' => 180])
            ->addTableColumn(['title' => '操作', 'minWidth' => 150, 'type' => 'toolbar'])
            ->addRightButton('self', ['title' => '操作', 'href' => url('console', ['id' => '__data_id__']),'class' => 'layui-btn layui-btn-xs layui-btn-warm', 'minWidth' => 120])
            ->addRightButton('edit', ['title' => '设置', 'href' => url('edit', ['tab' => $tab, 'id' => '__data_id__'])]);
        if(AdminM::isLeader($this->adminInfo)){
            $builder/*->addRightButton('self', ['title' => '清空聊天记录', 'href' => url('cleanChatPost', ['id' => '__data_id__']), 'data-ajax' => 1, 'data-confirm' => 1])*/
                ->addRightButton('delete', ['href' => url('delPost', ['id' => '__data_id__'])]);
        }

        return $builder->show();
    }

    /**
     * e小天机器人
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function loginExtian(){
        $data = cache('botadd' . $this->adminInfo['id']);
        $jump = $data['jump'] ?? '/undefined';
        if (!$data) {
            $this->error('参数错误');
        }

        $data['uuid'] = '';
        /**
         * @var $bot_client Extian
         */
        $bot_client = $this->model->getRobotClient($data);
        if(request()->isPost()){
            $data['uuid'] = session('bot_client_id');
            //获取机器人信息
            $info = $this->model->getRobotInfo($data);

            if(!empty($info) && !is_string($info)){
                if($bot = $this->model->getOneByMap(['uin' => $info['wxid']])){
                    $data = $this->model->updateOne([
                        'id' => $bot['id'],
                        'username' => $info['username'],
                        'nickname' => $info['nickname'],
                        'headimgurl' => $info['headimgurl'],
                        'alive' => 1,
                        'uuid' => $data['uuid'],
                        'protocol' => $data['protocol'],
                        'url' => $data['url'],
                        'app_key' => $data['app_key'],
                    ]);
                }else{
                    $data = $this->model->addOne([
                        'uin' => $info['wxid'],
                        'admin_id' => AdminM::getCompanyId($this->adminInfo),
                        'staff_id' => $this->adminInfo['id'],
                        'title' => $info['nickname'],
                        'username' => $info['username'],
                        'app_key' => $data['app_key'],
                        'nickname' => $info['nickname'],
                        'headimgurl' => $info['headimgurl'],
                        'protocol' => $data['protocol'],
                        'url' => $data['url'],
                        'alive' => 1,
                        'uuid' => $data['uuid']
                    ]);
                }
                //把此机器人之前登录的状态下线
                $this->model->updateByMap(['uin' => $data['uin'], 'id' => ['<>', $data['id']]],
                    ['alive' => 0]
                );
                $jump = ($jump == '/undefined' ? url('index/index', ['id' => $data['id']]) : $jump);
            }else{
                $this->error($info);
            }

            AdminLogService::addLog(['year' => (int)date('Y'), 'type' => AdminLogService::BOT_LOGIN, 'desc' => $this->adminInfo['username'] . '扫码登录微信'.$info['nickname']]);
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

        $res = $bot_client->injectWechat();
        if(empty($res['code'])){
            $this->error($res['errmsg']);
        }
        sleep(1);
        $login_code = $bot_client->getLoginCode(['client_id' => $res['pid']]);
        if(empty($login_code['code'])){
            $this->error($login_code['errmsg']);
        }
        session('bot_client_id', $login_code['pid']);
        //$data['code'] = generate_qr(['text' => 'http://weixin.qq.com/x/' . $login_code['data']]);
        $data['code'] = 'http://weixin.qq.com/x/'. $login_code['data'];
        return $this->show($data);
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
                case  BotConst::PROTOCOL_EXTIAN:
                    $action = 'loginextian';
                    break;
                case  BotConst::PROTOCOL_XBOT:
                    $action = 'loginxbot';
                    break;
                default:
                    $action = 'loginmy';
                    break;
            }

            $this->success('请等待加载登录二维码！', url($action, input('post.')));
        }

        $data = array_merge([
            'protocol' => BotConst::PROTOCOL_EXTIAN,
            'app_key' => get_rand_char(32)
        ], $this->getConfig());

        $tip = "<p>微信二维码加载需要几秒钟，点击提交后请耐心等待！</p>
<p>app_key和url读取顺序：上个机器人配置 > 系统配置值 </p>
<p>如果无法正常通信，请检查key和接口地址是否和机器人框架上的一致</p>";
        // 使用FormBuilder快速建立表单页面
        $builder = new FormBuilder();
        $builder->setMetaTitle('扫码登录微信')
            ->setTip($tip)
            ->setPostUrl(url('hookAdd'))
            ->addFormItem('protocol', 'radio', '类型', '机器人类型', BotConst::canScan())
            ->addFormItem('app_key', 'text', 'Key', '请保证当前key与机器人框架上的配置相同', [], 'required')
            ->addFormItem('url', 'text', '接口地址', '机器人框架所在服务器的IP:端口', [], 'required')
            ->setFormData($data);
        return $builder->show();
    }

    public function xbotcomAdd()
    {
        $data = array_merge([
            'protocol' => BotConst::PROTOCOL_XBOTCOM,
            //'app_key' => get_rand_char(32)
        ], $this->getConfig());

        // 使用FormBuilder快速建立表单页面
        $builder = new FormBuilder();
        $builder->setMetaTitle('新增机器人')
            ->setTip($this->xbotTip)
            ->setPostUrl(url('xbotSavePost'))
            ->addFormItem('protocol', 'hidden', '类型', '机器人类型')
            ->addFormItem('uuid', 'text', 'client_id', '从xbot打开的窗口获取', [], 'required')
            ->addFormItem('title', 'text', '备注名称', '30字内', [], 'required maxlength=30')
            ->addFormItem('uin', 'text', 'Wxid', '从xbot打开的窗口获取，也就是消息体中的user_id', [], 'required maxlength=30')
            //->addFormItem('app_key', 'text', 'AppKey', '请保证当前appkey与机器人框架上的配置相同', [], 'required')
            ->addFormItem('url', 'text', '接口地址', '请从机器人框架上获取', [], 'required')
            ->setFormData($data);

        return $builder->show();
    }

    public function xbotcomEdit()
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
            ->setTip($this->xbotTip)
            ->setPostUrl(url('xbotSavePost'))
            ->addFormItem('id', 'hidden', 'ID', 'ID')
            ->addFormItem('uuid', 'text', 'client_id', '从xbot打开的窗口获取')
            ->addFormItem('title', 'text', '备注名称', '30字内', [], 'required maxlength=30')
            ->addFormItem('uin', 'text', 'Wxid', '从xbot打开的窗口获取，也就是消息体中的user_id', [], 'required maxlength=30')
            ->addFormItem('url', 'text', '接口地址', '请从机器人框架上获取', [], 'required')
            ->setFormData($data);

        return $builder->show();
    }

    public function xbotSavePost($jump_to = "/undefined", $data = []){
        $post_data = input('post.');
        $post_data['admin_id'] = AdminM::getCompanyId($this->adminInfo);

        $post_data['protocol'] = BotConst::PROTOCOL_XBOTCOM;
        $client = $this->model->getRobotClient($post_data);
        $pong = $client->ping($post_data);
        $msg = '保存成功！';
        if($pong['code'] && $pong['data'] == 'PONG'){
            $post_data['alive'] = 1;
        }else{
            $post_data['alive'] = 0;
            $msg .= '但系统检测到您的企微未登录';
        }

        if (empty($post_data[$this->pk])) {
            $post_data['staff_id'] = $this->adminInfo['id'];
            $res = $this->model->addOne($post_data);
        } else {
            $res = $this->model->updateOne($post_data);
        }

        if ($res) {
            $this->success($msg, $jump_to);
        } else {
            $this->error('保存出错');
        }
    }

    /**
     * 添加
     * @return mixed
     * @throws \think\db\exception\DbException
     */
    public function add()
    {
        $tab = input('tab', BotConst::PROTOCOL_EXTIAN);
        if($tab != BotConst::PROTOCOL_EXTIAN){
            $this->redirect(url($tab.'Add'));
        }
        if($this->request->isPost()){
            $params = input('post.');
            $res = $this->model->getRobotList($params);
            if(is_string($res)){
                $this->error($res);
            }
            foreach ($res as $item){
                $bot = $this->model->getOneByMap(['uin' => $item['uin']], true, true);
                if($bot){
                    $res = $this->model->updateOne(array_merge($params, [
                        'id' => $bot['id'],
                        'username' => $item['username'],
                        'nickname' => $item['nickname'],
                        'headimgurl' => $item['headimgurl'],
                        'alive' => 1,
                        'uuid' => $item['uuid'] ?? 0
                    ]));
                }else{
                    $res = $this->model->addOne(array_merge($params, [
                        'uin' => $item['uin'],
                        'admin_id' => AdminM::getCompanyId($this->adminInfo),
                        'staff_id' => $this->adminInfo['id'],
                        'username' => $item['username'],
                        'title' => $item['nickname'],
                        'nickname' => $item['nickname'],
                        'headimgurl' => $item['headimgurl'],
                        'alive' => 1,
                        'uuid' => $item['uuid'] ?? 0
                    ]));
                }
                AdminLogService::addLog(['year' => (int)date('Y'), 'type' => AdminLogService::BOT_LOGIN, 'desc' => $this->adminInfo['username'] . '新增微信机器人']);
                //同步好友任务
                invoke('\\app\\common\\event\\TaskQueue')->push([
                    'delay' => 3,
                    'params' => [
                        'do' => ['\\app\\crontab\\task\\Bot', 'pullMembers'],
                        'bot' => $res
                    ]
                ]);
            }
            $this->success("操作成功！", '/undefined');
        }
        $data = array_merge([
            'login_code' => 0,
            'protocol' => BotConst::PROTOCOL_EXTIAN,
            //'app_key' => get_rand_char(32)
        ], $this->getConfig());

        // 使用FormBuilder快速建立表单页面
        $builder = new FormBuilder();
        $builder->setMetaTitle('新增机器人')
            ->setTip($this->tip)
            ->setPostUrl(url('add'))
            ->addFormItem('protocol', 'radio', '类型', '机器人类型', BotConst::hooks())
            //->addFormItem('uuid', 'text', 'client_id', 'e小天、xbot类型的必填')
            //->addFormItem('title', 'text', '备注名称', '30字内', [], 'required maxlength=30')
            //->addFormItem('uin', 'text', 'Wxid', '微信在机器人框架登陆后可获取', [], 'required maxlength=30')
            ->addFormItem('app_key', 'text', 'e小天的KEY', '请到e小天管理面板中获取', [], 'required')
            ->addFormItem('url', 'text', '服务器地址', 'ip:port', [], 'required')
            //->addFormItem('login_code', 'radio', '扫码登录', '是否扫码登录', [0 => '否', 1 => '是'])
            ->setFormData($data);

        return $builder->show();
    }

    /**
     * 编辑
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function edit()
    {
        $id = input('id', null);
        $tab = input('tab', BotConst::PROTOCOL_EXTIAN);
        if($tab != BotConst::PROTOCOL_EXTIAN){
            $this->redirect(url($tab.'Edit', ['id' => $id]));
        }
        $data = $this->model->getOne($id);

        if (!$data) {
            $this->error('参数错误');
        }
        if($this->request->isPost()){
            AdminLogService::addLog(['year' => (int)date('Y'), 'type' => AdminLogService::BOT_LOGIN, 'desc' => $this->adminInfo['username'] . '编辑微信机器人'.$data['nickname'].'的信息']);
            parent::savePost();
        }

        // 使用FormBuilder快速建立表单页面
        $builder = new FormBuilder();
        $builder->setMetaTitle('编辑机器人')
            ->setTabNav($this->botConfigTabs($id), __FUNCTION__)
            ->setTip($this->tip)
            ->setPostUrl(url('edit'))
            ->addFormItem('id', 'hidden', 'ID', 'ID')
            ->addFormItem('uuid', 'text', 'PID', '从e小天页面的微信列表中查看',[], 'required')
            ->addFormItem('title', 'text', '备注名称', '30字内', [], 'required maxlength=30')
            ->addFormItem('uin', 'text', 'Wxid', '微信在机器人框架登陆后可获取', [], 'required maxlength=30')
            ->addFormItem('app_key', 'text', 'ext的KEY', '请到ext管理面板中获取', [], 'required')
            ->addFormItem('url', 'text', '服务器地址', 'ip:port', [], 'required')
            ->addFormItem('status', 'radio', '启用', '是否启用', Common::yesOrNo())
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
        if($res['protocol'] == BotConst::PROTOCOL_XBOTCOM){
            $this->success('操作成功！', $jump_to);
        }
        if ($res) {
            if($login_code){
                switch ($post_data['protocol']){
                    case  BotConst::PROTOCOL_EXTIAN:
                        $action = 'reloginextian';
                        break;
                    case  BotConst::PROTOCOL_XBOT:
                        $action = 'reloginxbot';
                        break;
                    default:
                        $action = 'reloginmy';
                        break;
                }
                $this->success('保存成功，请等待加载登录二维码！', url($action, ['id' => $res['id']]));
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
                    $jump_to = url('index/index', ['id' => $res['id']]);
                    AdminLogService::addLog(['year' => (int)date('Y'), 'type' => AdminLogService::BOT_LOGIN, 'desc' => $this->adminInfo['username'] . '登录'.$info['username'].'微信']);
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
     * 分配员工
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function allocate()
    {
        $id = input('id', null);
        $data = $this->model->getOneByMap(['admin_id' => $this->adminInfo['id'], 'id' => $id]);

        if (!$data) {
            $this->error('参数错误');
        }
        if($this->request->isPost()){
            $post_data = input('post.');
            $this->model->updateOne([
                'id' => $data['id'],
                'staff_id' => $post_data['staff_id']
            ]);
            $this->success('操作成功！', '/undefined');
        }

        // 使用FormBuilder快速建立表单页面
        $builder = new FormBuilder();
        $builder->setMetaTitle('分配员工')
            ->setTabNav($this->botConfigTabs($id), __FUNCTION__)
            ->setPostUrl(url('allocate'))
            ->addFormItem('id', 'hidden', 'ID', 'ID')
            ->addFormItem('staff_id', 'select', '选择员工', '将当前微信号'.$data['nickname'].'分配给哪个员工', AdminM::getTeamIdToName(), 'required')
            ->setFormData($data);

        return $builder->show();
    }

    /**
     * 删除机器人
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function delPost(){
        if(request()->isPost()){
            $id = input('id', 0);
            $map = ['admin_id' => $this->adminInfo['id'], 'id' => $id];
            if($this->model->delByMap($map)){
                AdminLogService::addLog(['year' => (int)date('Y'), 'type' => AdminLogService::DEL, 'desc' => $this->adminInfo['username'] . '删除数据'.$this->model->getName().':'.$id]);
                $this->success('删除成功');
            }else{
                $this->error('删除失败');
            }
        }
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
     * 快速获取app_key和url
     * @return array|false|mixed|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DbException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getConfig(){
        if($config = $this->model->getOneByOrder([
            'where' => ['admin_id' => $this->adminInfo['id']],
            'order' => ['alive' => 'desc', 'update_time' => 'desc'],
            'field' => ['app_key', 'url']
        ])){
            $config = $config->toArray();
        /*}else{
            $config = config('system.bot');*/
        }
        if(empty($config['app_key'])){
            $key = '';
            try{
                //针对win系统有效
                $ext_ini = "C:\Users\Public\Documents\wxext.cn\WxExt.ini";
                if(file_exists($ext_ini)){
                    $content = file_get_contents($ext_ini);
                    $content = json_decode($content, true);
                    ($key = !empty($content['key'])) && $key = $content['key'];
                }
            }catch (\Exception $e){}
            $config = [
                'url' => '127.0.0.1:8203',
                'app_key' => $key
            ];
        }
        return empty($config) ? [] : $config;
    }

    /**
     * 清空聊天记录
     * @throws \think\Exception
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


    /**
     * 编辑情况下的扫码登录
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function reLoginExtian(){
        $id = input('id', null);
        $data = $this->model->getOne($id);

        if (!$data) {
            $this->error('参数错误');
        }
        //获取机器人信息
        $info = $this->model->getRobotInfo($data);
        if(request()->isPost()){
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
            $this->success('登录成功', url('index/index', ['id' => $data['id']]));
        }

        if(!empty($info) && !is_string($info)){
            $this->error('该微信已登录，无需重复登录', url('index/index', ['id' => $data['id']]));
        }

        $bot_client = $this->model->getRobotClient($data);
        $res = $bot_client->injectWechat();
        if(empty($res['code'])){
            $this->error($res['errmsg']);
        }
        sleep(2);
        $login_code = $bot_client->getLoginCode(['client_id' => $res['pid']]);
        if(empty($login_code['code'])){
            $this->error($login_code['errmsg']);
        }
        $data['code'] = generate_qr(['text' => 'http://weixin.qq.com/x/' . $login_code['data']]);
        return $this->show($data, 'bot/reloginmy');
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
        $jump = $data['jump'] ?? '/undefined';
        if (!$data) {
            $this->error('参数错误');
        }
        $data['uuid'] = '';
        $bot_client = $this->model->getRobotClient($data);
        if(request()->isPost()){
            $do = input('post.do', 'confirm');
            if($do == 'getcode'){
                $res = $bot_client->getLoginCode();
                // Log::write("获取微信二维码：".json_encode($res));
                if($res['code'] == 0){
                    $this->error($res['errmsg']);
                }
                if(empty($res['data'])){
                    // $bot_client->exitLoginCode();
                }
                $data['code'] = base64_to_pic($res['data']);
                $this->success('请打开微信扫码!', null, $data);
            }
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
                            'alive' => 1,
                            'url' => $data['url'],
                            'app_key' => $data['app_key'],
                            'protocol' => $data['protocol'],
                        ]);
                    }else{
                        $data = $this->model->addOne([
                            'uin' => $v['wxid'],
                            'admin_id' => AdminM::getCompanyId($this->adminInfo),
                            'staff_id' => $this->adminInfo['id'],
                            'title' => $v['nickname'],
                            'username' => $v['username'],
                            'nickname' => $v['nickname'],
                            'headimgurl' => $v['headimgurl'],
                            'protocol' => $data['protocol'],
                            'url' => $data['url'],
                            'app_key' => $data['app_key'],
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
                $this->success('登录成功', $jump);
            }else{
                $this->success('登录失败：' . $bot_client->getError());
            }
        }
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
            $do = input('post.do', 'confirm');
            if($do == 'getcode'){
                $res = $bot_client->getLoginCode();
                // Log::write("获取微信二维码：".json_encode($res));
                if($res['code'] == 0){
                    $this->error($res['errmsg']);
                }
                if(empty($res['data'])){
                    // $bot_client->exitLoginCode();
                }
                $data['code'] = base64_to_pic($res['data']);
                $this->success('请打开微信扫码!', null, $data);
            }
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
                $this->success('登录成功', '/undefined');
            }
        }

        return $this->show($data->toArray());
    }
}