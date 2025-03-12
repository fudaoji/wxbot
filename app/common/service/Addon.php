<?php
/**
 * Created by PhpStorm.
 * Script Name: ${FILE_NAME}
 * Create: 2023/5/24 15:41
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\service;

use app\common\model\Addon as AppM;
use app\common\service\File as FileService;
use think\facade\Db;

class Addon
{
    /**
     * 获取{id:title, ...}
     * @param array $where
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function getIdToTitle($where = []){
        $list = (new AppM())->getField(['id','title'], $where);
        return $list;
    }

    /**
     * 启用的应用
     * @param string $type
     * @param bool $refresh
     * @return AppM[]|array|mixed|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function listOpenApps($type = Platform::WECHAT, $refresh = false){
        $key = md5(__CLASS__.__FUNCTION__);
        $list = cache($key);
        if(empty($list) || $refresh){
            $query = AppM::where('status', 1);
            $type && $query = $query->where('type', 'like', '%'.$type.'%');
            $list = $query->field(['id','title','name','logo'])
                ->order('sort_reply', 'desc')
                ->select()
                ->toArray();
        }
        cache($key, $list);
        return $list;
    }

    /**
     * 执行应用中的Install::update
     * @param string $name
     * Author: fudaoji<fdj@kuryun.cn>
     * @param string $from_version
     * @param string $to_version
     */
    static function runUpdate($name = '', $from_version = '', $to_version = ''){
        if(is_file(addon_path($name, 'Install.php'))){
            $class = "\\addons\\$name\\Install";
            $install_handler = new $class();
            if(method_exists($install_handler, 'update')){
                $install_handler->update($from_version, $to_version);
            }
        }
    }

    /**
     * 执行应用中的Install::install
     * @param string $name
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function runInstall($name = ''){
        if(is_file(addon_path($name, 'Install.php'))){
            $class = "\\addons\\$name\\Install";
            $install_handler = new $class();
            if(method_exists($install_handler, 'install')){
                $install_handler->install();
            }
        }
    }

    /**
     * 执行应用中的Install::uninstall
     * @param string $name
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function runUninstall($name = ''){
        if(is_file(addon_path($name, 'Install.php'))){
            $class = "\\addons\\$name\\uninstall";
            $install_handler = new $class();
            if(method_exists($install_handler, 'uninstall')){
                $install_handler->uninstall();
            }
        }
    }

    /**
     * 获取应用基本信息
     * @param string $name
     * @param bool $refresh
     * @return array|mixed|\think\db\Query|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function getApp($name = '', $refresh = false){
        $key = md5(__CLASS__.__FUNCTION__.$name);
        $data = cache($key);
        if(empty($data) || $refresh){
            if(intval($name)){
                $where = [['id','=', $name]];
            }else{
                $where = [['name','=', $name]];
            }
            $data = AppM::where($where)->find();
        }
        cache($key, $data);
        return  $data;
    }

    /**
     * 获取未安装应用
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public static function listUninstallApp()
    {
        $app_folder = opendir(addon_path(''));
        $apps = []; //存放未安装的插件
        if ($app_folder) {
            while (($file = readdir($app_folder)) !== false) {
                if (is_dir(addon_path($file)) && $file != '.' && $file != '..') {
                    if (($app_local = get_addon_info($file)) && empty(self::getApp($file, true))) {
                        $apps[] = $app_local;
                    }
                }
            }
        }
        return $apps;
    }

    /**
     * 清空应用数据
     * @param array $params
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function clearAppData($params = []){
        $name = $params['name'];
        $app = self::getApp($name, true);
        Db::startTrans();
        try {
            $install_sql = addon_path($name, 'install.sql');
            if (is_readable($install_sql)) {
                $sql = file_get_contents($install_sql);
                $sql = str_replace("\r", "\n", $sql);
                $sql = explode(";\n", $sql);
                $original = '`__PREFIX__';
                $prefix = '`'.Database::getTablePrefix();
                $sql = str_replace("{$original}", "{$prefix}", $sql);

                foreach ($sql as $value) {
                    $value = trim($value);
                    if (!empty($value)) {
                        if (strpos($value, 'CREATE TABLE') !== false) {
                            $table_name = '';
                            preg_match('|EXISTS `(.*?)`|', $value, $table_name1);
                            preg_match('|TABLE `(.*?)`|', $value, $table_name2);

                            !empty($table_name1[1]) && $table_name = $table_name1[1];
                            empty($table_name) && !empty($table_name2[1]) && $table_name = $table_name2[1];
                            if ($table_name) {//如果存在表名
                                Db::execute("DROP TABLE IF EXISTS `{$table_name}`;"); //删除数据库中存在着表，
                            }
                        }
                    }
                }
            }

            AppM::where('id', $app['id'])->delete();
            Db::commit();
            $res = true;
        }catch (\Exception $e){
            Db::rollback();
            $res = false;
        }
        return $res;
    }

    /**
     * 彻底删除文件包
     * @param $name
     * @return bool|string
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public static function removePackage($name)
    {
        try {
            $path= addon_path($name);
            if(!file_exists($path)){
                return $path.'目录不存在';
            }
            if(!is_writable($path)){
                return $path.'目录没有删除权限';
            }
            //删除主目录
            if(($res = FileService::delDirRecursively($path, true)) !== true){
                return '删除应用目录失败:' . $res;
            }
            //删除静态文件夹
            if(is_dir(public_path(config('addon.pathname')) . $name)){
                if(($res = FileService::delDirRecursively(public_path(config('addon.pathname')) . $name, true)) !== true){
                    return '删除静态资源目录失败:' . $res;
                }
            }
        }catch (\Exception $e){
            return  '安装包删除出错：'.$e->getMessage();
        }

        return true;
    }

    /**
     * 快速创建应用
     * @param array $params
     * @return bool|string
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function buildAddon($params = []){
        $addon_name = strtolower($params['name']);
        $addon_title = $params['title'];
        $addon_version = $params['version'];
        $addon_depend_wxbot = $params['depend_wxbot'];
        $addon_author = $params['author'];
        $addon_desc = $params['desc'];
        $addon_logo = $params['logo'];
        $addon_path = addon_path($addon_name);
        $addon_admin_url_type = $params['admin_url_type'];
        if($addon_admin_url_type == 1){
            $zip_name = '__addon__.zip';
        }else{
            $zip_name = '__addon2__.zip';
        }

        try {
            if(file_exists($addon_path)){
                return "应用{$addon_name}已存在";
            }
            $pattern = '/^([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)$/';
            if (! preg_match($pattern, $addon_name)) {
                return '应用名不合法。 应用名称只支持小写字母、数字和下划线，且不能以数字开头！';
            }

            //1、解压应用模板
            if(! file_exists(addon_path($zip_name))){
                return addon_path($zip_name) . "不存在";
            }
            $zip = new \ZipArchive;
            $res = $zip->open(addon_path($zip_name));
            if ($res === true) {
                $zip->extractTo($addon_path);
                $zip->close();
            } else {
                return  "解压".addon_path($zip_name)."失败，请检查是否有写入权限!";
            }
            $logo_name = 'logo.png';
            file_put_contents(addon_path($addon_name, 'public'.DS.$logo_name), file_get_contents($addon_logo));

            //2、批量替换应用信息参数
            if(($res = replace_in_files(addon_path($addon_name),
                    ['__ADDON_NAME__', '__ADDON_TITLE__','__ADDON_DESC__', '__ADDON_VERSION__',
                        '__ADDON_DEPEND_WXBOT__', '__ADDON_AUTHOR__', '__ADDON_LOGO__'],
                    [$addon_name, $addon_title, $addon_desc, $addon_version, $addon_depend_wxbot, $addon_author, $logo_name],
                    ['public']
                )) !== true){
                return $res;
            }
            return true;
        }catch (\Exception $e){
            @unlink($addon_path);
            return '应用创建错误：' . (string)$e->getMessage();
        }
    }
}