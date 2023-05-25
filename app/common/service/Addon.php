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
use think\facade\Db;

class Addon
{
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
     * @return array|mixed|\think\db\Query|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function getApp($name = ''){
        if(intval($name)){
            $where = [['id','=', $name]];
        }else{
            $where = [['name','=', $name]];
        }
        return AppM::where($where)->find();
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
                    if (($app_local = get_addon_info($file)) && empty(self::getApp($file))) {
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
        $app = self::getApp($name);
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
}