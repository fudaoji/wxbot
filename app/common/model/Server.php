<?php
/**
 * Created by PhpStorm.
 * Script Name: Server.php
 * Create: 2023/2/17 18:08
 * Description: 服务器
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model;

class Server extends Base
{

    /**
     * 获取可用server
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function getServer(){
        //设定额度
        $servers = self::where('status', 1)->column(['num','app_key'], 'url');
        //实际使用
        $bots = model('admin/bot')->getGroupAll([
            'where' => ['alive' => 1],
            'group' => 'url',
            'field' => ['url', 'count(id) as num']
        ]);
        foreach ($bots as $conf){
            if(isset($servers[$conf['url']]['num']) && $conf['num'] < $servers[$conf['url']]['num']){
                return $servers[$conf['url']];
            }
        }
        return [];
    }
}