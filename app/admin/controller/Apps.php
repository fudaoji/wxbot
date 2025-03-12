<?php
/**
 * Created by PhpStorm.
 * Script Name: Apps.php
 * Create: 2022/12/15 8:14
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\controller;

use app\common\service\AdminGroup as GroupService;
use app\common\service\DACommunity;
use app\common\service\Platform as PlatformService;
use app\constants\Common;
use app\common\model\Addon;
use app\common\model\AdminAddon;
use app\common\service\Addon as AppService;
use think\facade\Db;


class Apps extends Base
{
    /**
     * @var Addon
     */
    protected $model;
    /**
     * @var AdminAddon
     */
    private $adminAppM;

    public function initialize(){
        parent::initialize();
        $this->model = new Addon();
        $this->adminAppM = new AdminAddon();
    }

    static function tabList(){
        return [
            'installed' => ['title' => '已安装', 'href' => url('installedList')],
            'uninstall' => ['title' => '未安装', 'href' => url('uninstallList')]
        ];
    }

    /**
     * 操作台
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function index(){
        if(request()->isPost()){ //开启关闭
            $id = input('post.id');
            if(empty($ta = $this->model->getOne($id))){
                $this->error('数据不存在');
            }
            $this->model->update(['id' => $id, 'status' => abs($ta['status'] - 1)]);
            $this->success('操作成功');
        }

        $page_size = 12;
        $type = input('type', '');
        $search_key = input('search_key', '');
        $where = array_merge(['status' => 1], GroupService::getGroupAppsWhere($this->adminInfo));
        $type && $where['type'] = ['like', '%'.$type.'%'];

        $search_key && $where['title|desc'] = ['like', '%'.$search_key.'%'];

        $data_list = $this->model->page($page_size, $where, ['sort_reply' => 'desc', 'id' => 'desc'], true, true);
        $page = $data_list->appends(['search_key' => $search_key])->render();

        $assign = [
            'data_list' => $data_list,
            'search_key' => $search_key,
            'page' => $page,
            'type' => $type,
            'types' => ['' => '全部平台'] + PlatformService::types()
        ];
        return $this->show($assign);
    }

    public function edit()
    {
        $id = input('id', 0);
        $data = $this->model->getOne($id);

        if (!$data) {
            $this->error('参数错误');
        }

        // 使用FormBuilder快速建立表单页面
        $builder = new FormBuilder();
        $builder->setMetaTitle('编辑')
            ->setPostUrl(url('savePost'))
            ->addFormItem('id', 'hidden', 'ID', 'ID')
            ->addFormItem('status', 'radio', '状态', '状态', Common::status(), 'required')
            ->addFormItem('sort_reply', 'number', '应答顺序', '数字越大越靠前', [], 'required min=0')
            ->setFormData($data);
        return $builder->show();
    }

    /**
     * 删除应用包
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function removePost()
    {
        if (request()->isPost()) {
            $name = input('name', '');
            if ($name == '') {
                $this->error('参数错误');
            }
            $res = AppService::removePackage($name);
            if($res === true){
                $this->success('安装包删除成功');
            }else{
                $this->error($res);
            }
        }
    }

    /**
     * 卸载
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function uninstallPost()
    {
        if (request()->isPost()) {
            $name = input('name', '');
            if ($name == '') {
                $this->error('参数错误');
            }

            if(AppService::clearAppData(['name' => $name])){
                AppService::runUninstall();
                $this->success('应用卸载成功，数据已删除');
            }else{
                $this->error('应用卸载失败，请自行手动删除数据');
            }
        }
    }

    /**
     * 安装
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function installPost()
    {
        if (request()->isPost()) {
            $name = input('name', '');
            if ($name == '') {
                $this->error('name参数错误');
            }

            if ($app = AppService::getApp($name, true)) {
                $this->error('应用已安装!');
            }

            $cf = get_addon_info($name);
            //检查框架版本依赖
            if(!empty($cf['depend_wxbot']) && $cf['depend_wxbot'] > config('app.version')){
                $this->error('请先升级wxbot！');
            }
            $data = [
                'name' => $cf['name'],
                'title' => $cf['title'],
                'desc' => isset($cf['desc']) ? $cf['desc'] : '',
                'version' => isset($cf['version']) ? $cf['version'] : '',
                'author' => isset($cf['author']) ? $cf['author'] : '',
                'logo' => isset($cf['logo']) ? addon_logo_url($cf['name']) : '',
                'admin_url' => $cf['admin_url'] ?? '',
                'admin_url_type' => $cf['admin_url_type'] ?? 1,
                'status' => 1,
                'type' => isset($cf['type']) ? $cf['type'] : PlatformService::WECHAT,
                'create_time' => time(),
                'update_time' => time()
            ];

            $result = $this->validate($data, '\\app\\common\\validate\\App.add');
            if ($result !== true) {
                $this->error($result);
            }

            Db::startTrans();
            try {
                $install_sql = addon_path($name, 'install.sql');
                if (is_file($install_sql) && is_readable($install_sql)) {
                    execute_sql($install_sql);
                }
                //入库
                $res = $this->model->addOne($data);

                //执行应用中的Install::install
                AppService::runInstall($name);

                //refresh apps
                AppService::listOpenApps(PlatformService::WECHAT, true);
                Db::commit();
            }catch (\Exception $e){
                Db::rollback();
                $res = json_encode($e->getMessage(), JSON_UNESCAPED_UNICODE);
            }
            if(is_string($res)){
                $this->error('安装应用失败:' . $res);
            }else{
                $this->success('安装应用成功!');
            }
        }
    }

    /**
     * 未安装列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function uninstallList(){
        if(request()->isPost()){
            $list = AppService::listUninstallApp();
            $total = count($list);
            foreach ($list as &$item){
                $item['logo'] = import_addon_public($item['logo'], $item['name']);
            }
            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $builder = new ListBuilder();
        $builder->setTabNav(self::tabList(), 'uninstall')
            ->addTopButton('addnew', ['text' => '采购应用', 'href' => url('appstore/index')])
            ->addTopButton('addnew', ['text' => '创建应用', 'href' => url('build'), 'class' => 'layui-btn-default'])
            ->addTableColumn(['title' => 'logo', 'field' => 'logo', 'type' => 'picture'])
            ->addTableColumn(['title' => '标识', 'field' => 'name'])
            ->addTableColumn(['title' => '名称', 'field' => 'title'])
            ->addTableColumn(['title' => '版本', 'field' => 'version'])
            ->addTableColumn(['title' => '简介', 'field' => 'desc'])
            ->addTableColumn(['title' => '操作', 'width' => 150, 'type' => 'toolbar'])
            ->addRightButton('self', ['text' => '安装', 'href' => url('installpost', ['name' => '__data_name__']), 'data-ajax' => true, 'data-confirm' => '确认安装吗？'])
            ->addRightButton('delete', ['title' => '删除包', 'href' => url('removepost', ['name' => '__data_name__'])]);
        return $builder->show();
    }

    /**
     * 已安装列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function installedList(){
        if(request()->isPost()){
            $post_data = input('post.');
            $where = [];
            !empty($post_data['search_key']) && $where[] = ['title|name', 'like', '%'.$post_data['search_key'].'%'];
            $query = $this->model->where($where);
            $total = $query->count();
            if($total){
                $list = $query->page($post_data['page'], $post_data['limit'])
                    ->order(['sort_reply' => 'desc', 'id' => 'desc'])
                    ->select();
            }else{
                $list = [];
            }
            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $builder = new ListBuilder();
        $builder->setTabNav(self::tabList(), 'installed')
            ->setSearch([
                ['type' => 'text', 'name' => 'search_key', 'title' => '搜索词','placeholder' => '应用名称或标识']
            ])
            ->addTopButton('addnew', ['text' => '采购应用', 'href' => url('appstore/index')])
            ->addTopButton('addnew', ['text' => '创建应用', 'href' => url('build'), 'class' => 'layui-btn-default'])
            ->addTableColumn(['title' => 'logo', 'field' => 'logo', 'type' => 'picture'])
            ->addTableColumn(['title' => '标识', 'field' => 'name'])
            ->addTableColumn(['title' => '名称', 'field' => 'title'])
            ->addTableColumn(['title' => '版本', 'field' => 'version'])
            ->addTableColumn(['title' => '应答顺序', 'field' => 'sort_reply'])
            ->addTableColumn(['title' => '状态', 'field' => 'status', 'type' => 'switch', 'text' => '上架|下架'])
            ->addTableColumn(['title' => '操作', 'width' => 120, 'type' => 'toolbar'])
            ->addRightButton('edit')
            ->addRightButton('delete', ['title' => '卸载', 'href' => url('uninstallpost', ['name' => '__data_name__'])]);
        return $builder->show();
    }

    /**
     * 设置一条或者多条数据的状态
     * @Author  fudaoji<fdj@kuryun.cn>
     */
    public function setStatus() {
        $ids = input('ids/a');
        $status = input('status');

        if (empty($ids)) {
            $this->error('请选择要操作的数据');
        }

        $ids = (array) $ids;
        if($status == 'delete'){
            if($this->model->delByMap([[$this->pk, 'in', $ids]])){
                foreach ($ids as $id){
                    AppService::getApp($id, true);
                }
                $this->success('删除成功');
            }else{
                $this->error('删除失败');
            }
        }else{
            $arr = [];
            $msg = [
                'success' => '操作成功！',
                'error'   => '操作失败！',
            ];
            switch ($status) {
                case 'forbid' :  // 禁用条目
                    $data['status'] = 0;
                    break;
                case 'resume' :  // 启用条目
                    $data['status'] = 1;
                    break;
                default:
                    $this->error('参数错误');
                    break;
            }
            foreach($ids as $id){
                $data[$this->pk] = $id;
                $arr[] = $data;
            }
            if($this->model->saveAll($arr)){
                //refresh apps
                AppService::listOpenApps(PlatformService::WECHAT, true);

                foreach ($ids as $id){
                    AppService::getApp($id, true);
                }
                $this->success($msg['success']);
            }else{
                $this->error($msg['error']);
            }
        }
    }

    /**
     * 创建应用
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function build(){
        if($this->request->isPost()){
            $post_data = input('post.');
            if(($res = AppService::buildAddon($post_data)) === true){
                $this->success('创建成功, 请前往安装！', url('uninstallList'));
            }else{
                $this->error($res);
            }
        }
        $version_list = DACommunity::listFrameWorkVersions(['current_page' => 1, 'page_size' => 100]);
        $versions = [];
        foreach ($version_list['list'] as $item){
            $versions[$item['version']] = $item['version'];
        }

        $default_data = [
            'version' => '1.0.0',
            'author' => $this->adminInfo['realname'],
            'depend_wxbot' => config('app.version'),
            'admin_url_type' => 1
        ];
        $builder = new FormBuilder();
        $builder->setPostUrl(url('build'))
            ->addFormItem('name', 'text', '应用标识', '请输入唯一应用标识，支持小写字母、数字和下划线，且不能以数字开头', [], 'required minlength=2 maxlength=20')
            ->addFormItem('title', 'text', '应用名称', '请输入应用名称，2-50长度', [], 'required minlength=2 maxlength=50')
            ->addFormItem('version', 'text', '应用版本', '例如1.0.0', [], 'required')
            ->addFormItem('depend_wxbot', 'select', '依赖wxbot版本', '至少需要哪个版本的wxbot', $versions, 'required')
            ->addFormItem('logo', 'picture_url', '应用LOGO', '请上传比例为1:1的应用LOGO', [], 'required')
            ->addFormItem('author', 'text', '作者', '应用作者', [], 'required maxlength=100')
            ->addFormItem('desc', 'textarea', '应用描述', '200字内', [], 'maxlength=200')
            ->addFormItem('admin_url_type', 'radio', '是否独立后台', '是否独立后台', [1=>'否', 2=>'是'], 'required')
            ->setFormData($default_data);
        return $builder->show();
    }
}