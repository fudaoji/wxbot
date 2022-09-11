<?php
/**
 * Created by PhpStorm.
 * Script Name: Rule.php
 * Create: 2022/9/6 17:57
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model\zdjr;


class Rule extends Zdjr
{
    protected $table = 'rule';

    const INVITE_DIRECT = 'direct';
    const INVITE_LINK = 'link';

    public static function ruleFields($id = null){
        $list = [
            'speed' => '每次添加个数',
            'time_add' => '添加间隔时间/s',
            'time_round' => '每轮间隔时间/min',
            'add_msg' => '验证消息',
            'remark_name' => '备注名称',
            'groups' => '自动拉群',
            'invite_way' => '拉群方式',
            'group_person_limit' => '群自动切换临界值',
            'busy_stop' => '账号频繁不再添加'
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }

    public static function inviteWays($id = null){
        $list = [
            self::INVITE_DIRECT=> '直接拉',
            self::INVITE_LINK => '分享连接'
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }

    /**
     * 添加间隔时间
     * @param array $rules
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sleep($rules = [])
    {
        $time_add = explode('-', $rules['time_add']);
        sleep(rand($time_add[0], $time_add[1]));
    }

    /**
     * 是否开启下一轮
     * @param array $params
     * @return bool
     * @throws \think\db\exception\DbException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function canRun(array $params)
    {
        $log_m = new Log();
        if($data = $log_m->getOneByOrder([
            'where' => ['rule_id' => $params['rule_id']],
            'order' => ['id' => 'desc'],
            'field' => ['create_time']
        ])){
            return (time() - $data['create_time']) >= ($params['time_round'] * 60);
        }
        return  true;
    }
}