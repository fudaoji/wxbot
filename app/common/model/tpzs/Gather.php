<?php
/**
 * Created by PhpStorm.
 * Script Name: Gather.php
 * Create: 2022/3/29 15:52
 * Description: 采品群
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model\tpzs;


class Gather extends Tpzs
{
    protected $table = 'gather';

    /**
     * 获取采品群
     * @param array $params
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getGather($params = []){
        $group_wxid = $params['group_wxid'];
        $bot_wxid = $params['bot_wxid'];
        $from_wxid = $params['from_wxid'];
        $refresh = isset($params['refresh']) ? $params['refresh'] : 0;
        $cache_key = $group_wxid.$bot_wxid;
        $data = cache($cache_key);
        if($refresh || empty($data)){
            $data = model('admin/BotMember')->getOneJoin([
                'alias' => 'm',
                'join' => [
                    ['tpzs_gather gather', 'gather.group_id=m.id']
                ],
                'where' => ['gather.status' => 1, 'm.wxid' => $group_wxid, 'gather.officer' => ['like', "%".$from_wxid."%"], 'm.uin' => $bot_wxid],
                'field' => ['gather.*', 'm.wxid'],
                'refresh' => $refresh
            ]);
            cache($cache_key, $data, 7*86400);
        }
        return $data;
    }
}