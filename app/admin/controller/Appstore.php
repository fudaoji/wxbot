<?php
/**
 * Created by PhpStorm.
 * Script Name: Appstore.php
 * Create: 2023/1/17 13:39
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\controller;

use app\common\service\DACommunity;
use app\common\service\Addon as AppService;
use app\common\model\Addon as AppM;
use GuzzleHttp\Client;

class Appstore extends Base
{
    /**
     * @var AppM
     */
    private $appM;
    public function __construct(){
        parent::__construct();
        $this->appM = new AppM();
    }

    /**
     * 下载应用
     * @throws \GuzzleHttp\Exception\GuzzleException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function download()
    {
        $post_data = input('post.');
        $params = [
            'id' => $post_data['app_id']
        ];

        if(is_string($res = DACommunity::appDownload($params))){
            $this->error($res);
        }

        $app = $res['app'];
        $app_install_path = addon_path($app['name']);

        if (file_exists($app_install_path))
            $this->error($app['name'] . '目录已经存在或者您已经安装过【' . $app['title'] . '】，如果您要重新安装，请先卸载此应用');

        $addon_path = addon_path('');
        if(!is_writable($addon_path)){
            $this->error("应用包根目录" . $addon_path . "无可写权限，请先修改可写权限！");
        }

        $tem_file = runtime_path('/') . $app['name'].$app['version'].'-'.time(). '.tmp';
        $response = (new Client())->post($app['package'], ['verify' => false]);
        if($response->getStatusCode() === 200){
            $package = $response->getBody()->getContents();
            file_put_contents($tem_file, $package);
        }else{
            $this->error('下载安装包出错: ' . $response->getStatusCode());
        }

        $zip = new \ZipArchive;
        $res = $zip->open($tem_file);
        if ($res === true) {
            $zip->extractTo($addon_path);
            $zip->close();
            @unlink($tem_file); //删除临时压缩包
            $this->success('下载成功，正在跳转安装界面。。。', url('admin/apps/uninstallList'));
        } else {
            $this->error('解压失败，请检查是否有写入权限!');
        }
    }

    /**
     * 应用详情
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function appInfo()
    {
        $params = [
            'id' => input('id', 0)
        ];
        if(is_string($res = DACommunity::getAppInfo($params))){
            $this->error($res);
        }
        if(is_string($cates = DACommunity::getAppCates())){
            $this->error($cates);
        }

        $this->assign('cates', array_merge(['全部' => '全部'], $cates['cates']));
        $this->assign('types', array_merge(['all' => '全部'], $cates['types']));
        $info = $res['info'];
        $info['type'] = explode(',', $info['type']);

        $this->assign('info', $info);
        $this->assign('upgrade_list', $res['upgrade_list']);
        return $this->show();
    }

    /**
     * 应用列表
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function index()
    {
        $type = input('type', 'all');
        $search_key = input('search_key', '');
        $cate = input('cate', '全部');
        $current_page = input('page', 1);
        $page_size = 12;
        $params = [
            'type' => $type,
            'cate' => $cate,
            'search_key' => input('search_key', ''),
            'current_page' => $current_page,
            'page_size' => $page_size
        ];

        if(is_string($res = DACommunity::getApps($params))){
            $this->error($res);
        }
        if(is_string($cates = DACommunity::getAppCates())){
            $this->error($cates);
        }

        $this->assign('cates', array_merge(['全部' => '全部分类'], $cates['cates']));
        $this->assign('types', array_merge(['all' => '全部平台'], $cates['types']));
        $this->assign('total',  $res['total']);
        $this->assign('apps', $res['list']);
        $this->assign('user_addon', $res['user_addon']);
        $this->assign('page_size', $page_size);
        $this->assign('search_key', $search_key);
        $this->assign('page', $current_page);
        $this->assign('type', $type);
        $this->assign('cate', $cate);
        $this->assign('user', DACommunity::getUserInfo());
        return $this->show();
    }

    /**
     * 可升级APP
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function upgrade()
    {
        if (!DACommunity::getUserInfo())
             $this->error('请先登录官方社区!', url('upgrade/login'));

        if (request()->isPost()) {
            if(is_string($res = DACommunity::getUpgradeApps())){
                  $this->error($res);
            }
            $list = $res['list'];
            $data_list = [];
            if (!empty($list)) {
                foreach ($list as $v) {
                    $app = AppService::getApp($v['name']);
                    if(!empty($app) && $v['version'] > $app['version']){
                        $app['new_version'] = $v['version'];
                        $app['update_time'] = $v['update_time'];
                        $data_list[] = $app;
                    }
                }
            }
            $total = count($data_list);
             $this->success('success', '', ['total' => $total, 'list' => $data_list]);
        }

        $builder = new ListBuilder();
        $builder->addTableColumn(['title' => 'logo', 'field' => 'logo', 'type' => 'picture'])
            ->addTableColumn(['title' => '标识', 'field' => 'name'])
            ->addTableColumn(['title' => '名称', 'field' => 'title'])
            ->addTableColumn(['title' => '作者', 'field' => 'author'])
            ->addTableColumn(['title' => '当前版本', 'field' => 'version'])
            ->addTableColumn(['title' => '最新版本', 'field' => 'new_version'])
            ->addTableColumn(['title' => '更新时间', 'field' => 'update_time'])
            ->addTableColumn(['title' => '操作', 'width' => 150, 'type' => 'toolbar'])
            ->addRightButton('self', ['text' => '升级', 'href' => url('upgradepost', ['name' => '__data_name__']), 'data-ajax' => true, 'data-confirm' => '升级应用文件将会被更新(系统自动将应用打包备份在runtime/目录下)，你确定升级应用吗？']);
        return $builder->show();
    }

    public function show($assign = [], $view = '', $app = null)
    {
        $assign['user'] = DACommunity::getUserInfo();
        $assign['token'] = DACommunity::checkLogin();
        $assign['official_href'] = COMMUNITY_URL;
        return parent::show($assign, $view); // TODO: Change the autogenerated stub
    }

    /**
     * 升级操作
     * Author: fudaoji<fdj@kuryun.cn>
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function upgradePost(){
        $post_data = input();
        $app_name = $post_data['name'];
        $app_path = addon_path($app_name);
        if (!file_exists($app_path))
             $this->error($app_name . '目录不存在');

        $app = AppService::getApp($app_name);
        if(is_string($res = DACommunity::getUpgradePackage(['addon' => $app_name, 'version' => $app['version']]))){
             $this->error($res);
        }
        $upgrade = $res['upgrade'];

        $zip = new \ZipArchive;
        //备份
        $back_zip_name = runtime_path(DS) . $app['name'].$app['version'] . '-backup.zip';
        if (!$zip->open($back_zip_name, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
             $this->error('无法创建备份压缩包');
        }
        $this->addFileToZip($app_path, $zip);
        $zip->close();

        $tem_file = runtime_path(DS) . $app['name'].$upgrade['version'] . '.tmp';

        $response = (new Client())->post($upgrade['upgrade_url']);
        if($response->getStatusCode() === 200){
            $package = $response->getBody()->getContents();
            file_put_contents($tem_file, $package);
        }else{
              $this->error('下载升级包出错: ' . $response->getStatusCode());
        }

        $res = $zip->open($tem_file);
        if ($res === true) {
            $zip->extractTo($app_path);
            $zip->close();
        } else {
             $this->error('解压失败，请检查是否有写入权限');
        }
        @unlink($tem_file);

        if (is_file($app_path . 'upgrade.sql')) {
            try {
                execute_sql($app_path . 'upgrade.sql');
                @unlink($app_path . 'upgrade.sql');
            }catch (\Exception $e){
                $this->error('导入upgrade.sql出错：' . $res);
            }
        }
        //执行应用中的Install::update
        AppService::runUpdate($app_name, $app['version'], $upgrade['version']);

        $this->appM->update(['id' => $app['id'], 'version' => $upgrade['version']]);
        $this->success('恭喜您，升级成功!');
    }

    /**
     * 退出
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function logout()
    {
        DACommunity::logout();
        $this->success('成功退出!');
    }

    /**
     * 将文件添加到压缩包中
     * @param $path
     * @param $zip
     * Author: fudaoji<fdj@kuryun.cn>
     */
    private function addFileToZip($path, \ZipArchive $zip)
    {
        $handler = opendir($path);
        while (($filename = readdir($handler)) !== false) {
            if ($filename != "." && $filename != "..") {
                if (is_dir($path . DS . $filename)) {
                    $this->addFileToZip($path . DS . $filename, $zip);
                } else {
                    $zip->addFile($path . DS . $filename);
                }
            }
        }
        @closedir($path);
    }
}