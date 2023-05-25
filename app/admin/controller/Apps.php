<?php
/**
 * Created by PhpStorm.
 * Script Name: Apps.php
 * Create: 2022/12/15 8:14
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\controller;

use app\admin\model\Admin as AdminM;
use app\common\service\Platform as PlatformService;
use app\constants\Common;
use app\common\model\Addon;
use app\common\model\AdminAddon;
use app\common\service\Addon as AppService;
use app\common\service\DACommunity;
use app\common\service\File as FileService;
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
    private $tabList;

    public function __construct(){
        parent::__construct();
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
     * 我的应用
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
        $status = input('status', -1);
        $type = input('type', '');
        $search_key = input('search_key', '');
        $where = [];
        $type && $where[] = ['type', 'like', '%'.$type.'%'];
        $status != -1 && $where[] = ['status', '=', $status];
        $search_key && $where[] = ['title|desc', 'like', '%'.$search_key.'%'];
        $query = $this->model->where($where);
        $data_list = $query->order('id', 'desc')
            ->paginate($page_size);
        $page = $data_list->appends(['status' => $status, 'search_key' => $search_key])->render();

        $assign = [
            'data_list' => $data_list,
            'search_key' => $search_key,
            'page' => $page,
            'status' => $status,
            'type' => $type,
            'types' => ['' => '全部平台'] + PlatformService::types()
        ];
        return $this->show($assign);
    }
    public function adminapp(){
        $company_id = AdminM::getCompanyId($this->adminInfo);
        if(request()->isPost()){ //开启关闭
            $id = input('post.id');
            if(empty($ta = $this->adminAppM->getOneByMap(['id' => $id, 'company_id' => $company_id]))){
                $this->error('数据不存在');
            }
            $this->adminAppM->update(['id' => $id, 'status' => abs($ta['status'] - 1)]);
            $this->success('操作成功');
        }

        $page_size = 12;
        $status = input('status', -1);
        $search_key = input('search_key', '');
        $where = [
            ['ta.deadline', '>', time()],
            ['ta.company_id', '=', $company_id],
        ];

        $status != -1 && $where[] = ['ta.status', '=', $status];
        $search_key && $where[] = ['app.title|app.desc', 'like', '%'.$search_key.'%'];
        $query = $this->adminAppM->alias('ta')
            ->where($where)
            ->join('addon app', 'app.name=ta.app_name')
            ->join('admin admin', 'admin.id=ta.company_id');
        $data_list = $query->order('ta.update_time', 'desc')
            ->field([
                'ta.*','app.logo','app.desc','app.name','app.title','app.admin_url','app.admin_url_type',
                'admin.realname', 'admin.mobile','admin.username'
            ])
            ->paginate($page_size);
        $page = $data_list->appends(['status' => $status, 'search_key' => $search_key])->render();

        $assign = [
            'data_list' => $data_list,
            'search_key' => $search_key,
            'page' => $page,
            'status' => $status
        ];
        return $this->show($assign);
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
            $path= addon_path($name);
            if(!file_exists($path)){
                $this->error($path.'目录不存在');
            }
            if(!is_writable($path)){
                $this->error($path.'目录没有删除权限');
            }

            if(($res = FileService::delDirRecursively($path, true)) === true){
                $this->success('安装包删除成功');
            }else{
                $this->error('删除应用目录失败:' . $res);
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

            if ($app = AppService::getApp($name)) {
                $this->error('应用已安装!');
            }

            $cf = get_addon_info($name);
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
                //入库
                $res = $this->model->addOne($data);

                $install_sql = addon_path($name, 'install.sql');
                if (is_file($install_sql) && is_readable($install_sql)) {
                    execute_sql($install_sql);
                }
                //todo public文件移到框架的public/addons/下，并命名为应用标识
                FileService::renameFile(addon_path($name, 'public'), public_path(config('addon.pathname')) . $name);

                //执行应用中的Install::install
                AppService::runInstall($name);
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
                $item['logo'] = addon_logo_url($item['name']);
            }
            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $builder = new ListBuilder();
        $builder->setTabNav(self::tabList(), 'uninstall')
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
                    ->order('id', 'desc')
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
            ->addTableColumn(['title' => 'logo', 'field' => 'logo', 'type' => 'picture'])
            ->addTableColumn(['title' => '标识', 'field' => 'name'])
            ->addTableColumn(['title' => '名称', 'field' => 'title'])
            ->addTableColumn(['title' => '版本', 'field' => 'version'])
            ->addTableColumn(['title' => '状态', 'field' => 'status', 'type' => 'switch', 'text' => '上架|下架'])
            ->addTableColumn(['title' => '操作', 'width' => 70, 'type' => 'toolbar'])
            ->addRightButton('delete', ['title' => '卸载', 'href' => url('uninstallpost', ['name' => '__data_name__'])]);
        return $builder->show();
    }

    /**
     * 编辑应用信息
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function edit(){
        if(request()->isPost()){
            $post_data = input('post.');
            unset($post_data['__token__']);
            $this->model->update([
                'id' => $post_data['id'],
                'cates' => $post_data['cates'],
                'version' => $post_data['version'],
                'status' => $post_data['status']
            ]);
            unset($post_data['version'], $post_data['status'], $post_data['cates']);
            $this->appInfoM->update($post_data);
            return $this->success('保存成功');
        }
        if(! $data = $this->model->find(input('id', 0))){
            return $this->error('数据不存在');
        }

        $data = array_merge($data->toArray(), $this->appInfoM->find($data['id'])->toArray());
        $data['cates'] = empty($data['cates']) ? [] : explode(',', $data['cates']);

        $cates = $this->cateM->where('status',1)
            ->column('title');

        $builder = new FormBuilder();
        $builder->addFormItem('id', 'hidden', 'id', 'id')
            ->addFormItem('cates', 'chosen_multi', '分类标签', '可多选', array_combine($cates, $cates), 'required')
            ->addFormItem('version', 'text', '版本', '版本', [], 'required')
            ->addFormItem('sale_num_show', 'number', '虚拟销量', '前台显示的数字', [], 'required min=0')
            ->addFormItem('old_price', 'text', '原价', '原价', [], 'required min="0"')
            ->addFormItem('price', 'text', '售价', '每月的费用', [], 'required min="0"')
            ->addFormItem('snapshot', 'pictures_url', '应用快照', '应用典型界面截图', [], 'required')
            ->addFormItem('detail', 'ueditor', '详细介绍', '详细介绍', [], 'required max=50000')
            ->addFormItem('status', 'radio', '上架状态', '上架状态', Common::goodsStatus(), 'required')
            ->setFormData($data);

        return $builder->show();
    }
}