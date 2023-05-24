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
}