<?php
/**
 * Created by PhpStorm.
 * Script Name: Rule.php
 * Create: 2022/9/6 17:57
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model\zdjr;


use app\constants\Bot;

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
            'add_way' => '添加方式',
            'apply_limit' => '打招呼次数',
            'busy_stop' => '账号频繁不再添加',
            'remark_name' => '备注名称',
            'group_way' => '拉群方式',
            'group_name' => '建群名称',
            'group_members' => '需拉群好友',
            'group_quit' => '是否退群',
            'groups' => '自动拉群',
            'invite_way' => '入群方式',
            'group_person_limit' => '群自动切换临界值',
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }

    public static function groupWays($id = null){
        $list = [
            0 => '不进群',
            1 => '自动建群',
            2 => '自动拉群'
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }

    public static function addWays($id = null){
        $list = [
            Bot::SCENE_WXNUM=> '微信号',
            Bot::SCENE_CONTACT => '手机号',
            Bot::SCENE_SCAN => '扫一扫',
            0 => '随机'
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
        if(empty($time_add[1])){
            sleep(intval($rules['time_add']));
        }else{
            sleep(rand(intval($time_add[0]), intval($time_add[1])));
        }
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
            'field' => ['create_time'],
            'type' => 2
        ])){
            return (time() - $data['create_time']) >= ($params['time_round'] * 60);
        }
        return  true;
    }

    /**
     * 获取策略中的添加方式
     * @param $rules
     * Author: fudaoji<fdj@kuryun.cn>
     * @return mixed
     */
    public function getAddWay($rules)
    {
        if($rules['add_way'] < 1){
            $arr = [Bot::SCENE_WXNUM, Bot::SCENE_SCAN, Bot::SCENE_CONTACT];
            shuffle($arr);
            return $arr[0];
        }
        return $rules['add_way'];
    }
}