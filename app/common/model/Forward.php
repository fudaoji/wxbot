<?php
/**
 * Created by PhpStorm.
 * Script Name: Gather.php
 * Create: 2022/3/29 15:52
 * Description: 采品群
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model;


class Forward extends Base
{
    protected $isCache = true;

    /**
     * 获取转播数据
     * @param array $params
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DbException
     */
    public function getGather($params = []){
        $group_wxid = $params['group_wxid'];
        $bot_wxid = $params['bot_wxid'];
        $from_wxid = $params['from_wxid'];
        $refresh = isset($params['refresh']) ? $params['refresh'] : 0;
        $where = ['f.status' => 1, 'f.officer' => $from_wxid, 'bot.uin' => $bot_wxid];
        if($group_wxid){
            $where['g.wxid'] = $group_wxid;
        }else{
            $where['f.group_id'] = 0;
        }
        return $this->getOneJoin([
            'alias' => 'f',
            'join' => [
                ['bot', 'bot.id=f.bot_id'],
                ['bot_member g', 'g.id=f.group_id', 'left']
            ],
            'where' => $where,
            'refresh' => $refresh,
            'field' => 'f.*'
        ]);
    }
}