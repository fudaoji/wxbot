<?php
/**
 * Created by PhpStorm.
 * Script Name: BotMember.php
 * Create: 2025/3/27 17:21
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\service;


use app\admin\model\BotGroupmember as GroupMemberM;
use app\constants\Bot as BotConst;

class BotGroupMember
{
    static $model = null;

    static function model(){
        if(is_null(self::$model)){
            self::$model = new GroupMemberM();
        }
        return self::$model;
    }

    /**
     * 获取 [wxid=>nickname, ...] 数据对
     * @param array $where
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public static function getWxidToNickName($where = []){
        return self::model()->getField('wxid,nickname', $where, true);
    }

    /**
     * 新增或更新
     * @param $v
     * @param $group
     * @param $bot
     * @return array|bool|mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function insertOrUpdate($v, $group, $bot){
        $wxid = $v['wxid'];
        $data = [
            'nickname' => filter_emoji($v['nickName'] ?? ''),
            'group_nickname' => filter_emoji($v['nickName2'] ?? ''),
            'username' => $v['alias'] ?? '',
            'wxid' => $wxid,
            'headimgurl' => $v['img'] ?? '',
        ];
        !empty($v['invite']) && $data['invite_wxid'] = $v['invite'];
        !empty($v['inviteName']) && $data['invite_nickname'] = $v['inviteName'];
        !empty($v['inviteName2']) && $data['invite_group_nickname'] = $v['inviteName2'];

        if($res = self::model()->getOneByMap(['group_id' => $group['id'], 'wxid' => $wxid], ['id'])){
            $data['id'] = $res['id'];
            $data = self::model()->updateOne($data);
        }else{
            $data['bot_id'] = $bot['id'];
            $data['group_id'] = $group['id'];
            $data = self::model()->addOne($data);
            self::model()->getOneByMap(['group_id' => $group['id'], 'wxid' => $wxid], ['id'], true);
        }

        return $data;
    }
}