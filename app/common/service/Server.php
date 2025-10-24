<?php
/**
 * Created by PhpStorm.
 * Script Name: Blog.php
 * Create: 2025/4/20 下午12:32
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\service;


use app\admin\model\Admin;
use app\common\model\Server as M;
use app\constants\Bot as BotConst;
use app\common\service\Bot;


class Server
{
    const LOCAL = 'local';
    const CLOUD = 'cloud';

    static $model = null;

    static function model(){
        if(is_null(self::$model)){
            self::$model = new M();
        }
        return self::$model;
    }

    /**
     * 把客户之前的服务器导入
     * @param null $admin_info
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function initServer($admin_info = null){
        empty($admin_info) && $admin_info = Admin::find(session(SESSION_AID));
        if($config = Bot::model()->getOneByOrder([
            'where' => ['admin_id' => $admin_info['id'], 'protocol' => BotConst::PROTOCOL_EXTIAN],
            'order' => ['alive' => 'desc', 'update_time' => 'desc'],
            'field' => ['app_key', 'url']
        ])){
            $url = $config['url'];
            if(strpos($url, 'frp.') === false){
                $name = explode(':', $url)[0];
                $type = self::LOCAL;
            }else{
                $name = explode('.', $url)[0];
                $type = self::CLOUD;
            }
            $res = self::model()->addOne([
                'name' => $name,
                'url' => $url,
                'app_key' => $config['app_key'],
                'admin_id' => $admin_info['id'],
                'type' => $type
            ]);
        }
        return $res;
    }

    /**
     * 生成随机name
     * @param null $admin_info
     * @return string
     * @throws \think\db\exception\DbException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function generateName($admin_info = null){
        empty($admin_info) && $admin_info = Admin::find(session(SESSION_AID));
        $count = self::model()
            ->where(['admin_id' => $admin_info['id'], 'type' => self::LOCAL])
            ->count('id');
        return $count < 1 ? $admin_info['username'] : ($admin_info['username'] . '-'.$count);
    }

    /**
     * 生成隧道地址
     * @param string $name
     * @return string
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public static function generateDefaultUrl(string $name)
    {
        return $name.".frp.kuryun.cn:8203";
    }

    /**
     * 获取商户可用的设备
     * @param null $admin_info
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getServersByAdmin($admin_info = null)
    {
        empty($admin_info) && $admin_info = Admin::find(session(SESSION_AID));
        return self::model()->getField(['name', 'url'], ['admin_id' => $admin_info['id']], true);
    }

    /**
     * 获取设备
     * @param $name
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getServerByName($name)
    {
        return self::model()->where('name', $name)->find();
    }
}