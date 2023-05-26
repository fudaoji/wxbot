<?php
/**
 * Created by PhpStorm.
 * Script Name: DACommunity.php
 * Create: 2023/1/17 13:50
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\service;
use \DACommunity\Client as DaoCommunity;

class DACommunity
{
    const SESSION_KEY = 'DaoAdminToken';

    /**
     * 获取APP升级包
     * @param array $params
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function getUpgradePackage($params = []){
        $params['token'] = self::checkLogin();
        $res = DaoCommunity::instance()->appUpgradeGet($params);
        if($res['code']){
            return $res['data'];
        }
        return $res['msg'];
    }

    /**
     * 获取有权限升级的APP
     * @param array $params
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function getUpgradeApps($params = []){
        $params['token'] = self::checkLogin();
        $res = DaoCommunity::instance()->appUpgradeList($params);
        if($res['code']){
            return $res['data'];
        }
        return $res['msg'];
    }

    /**
     * 应用下载
     * @param array $params
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public static function appDownload(array $params)
    {
        $params['token'] = self::checkLogin();
        $res = DaoCommunity::instance()->appDownload($params);
        if($res['code']){
            return $res['data'];
        }
        return $res['msg'];
    }

    /**
     * 获取登录信息
     * @return mixed|string
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function getUserInfo(){
        if ($token = self::checkLogin()) {
            $res = DaoCommunity::instance()->userGet(['token' => $token]);
            if($res['code'] == 1){
                self::login($token);
                return $res['data']['user'];
            }else{
                return $res['msg'];
            }
        }
        return [];
    }

    static function getApps($params = []){
        $res = DaoCommunity::instance()->appList($params);
        if($res['code']){
            return $res['data'];
        }
        return $res['msg'];
    }

    static function getAppCates(){
        $res = DaoCommunity::instance()->appCateList();
        if($res['code']){
            return $res['data'];
        }
        return $res['msg'];
    }

    /**
     * 是否登录
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function checkLogin(){
        return session(self::SESSION_KEY) ? session(self::SESSION_KEY) : false;
    }

    /**
     * 登录会话
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function getSessionToken(){
        return session(self::SESSION_KEY);
    }

    /**
     * 是否登录
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function logout(){
        session(self::SESSION_KEY, null);
        return  true;
    }

    /**
     * 登录
     * @param string $token
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function login($token = ''){
        session(self::SESSION_KEY, $token);
        return  true;
    }

    /**
     * 获取应用详情
     * @param array $params
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public static function getAppInfo(array $params)
    {
        $res = DaoCommunity::instance()->appGet($params);
        if($res['code']){
            return $res['data'];
        }
        return $res['msg'];
    }

    /**
     * 根据app name获取应用详情
     * @param string $name
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public static function getAppInfoByName($name = '')
    {
        $res = DaoCommunity::instance()->appGetByName(['name' => $name]);
        if($res['code']){
            return $res['data'];
        }
        return $res['msg'];
    }
}