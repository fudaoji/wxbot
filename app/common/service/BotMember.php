<?php
/**
 * Created by PhpStorm.
 * Script Name: BotMember.php
 * Create: 2025/3/27 17:21
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\service;


use app\admin\model\BotMember as MemberM;
use app\constants\Bot as BotConst;
use app\common\service\Bot as BotService;

class BotMember
{
    static $model = null;

    static function model(){
        if(is_null(self::$model)){
            self::$model = new MemberM();
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
     * 根据wxid获取信息
     * @param $wxid
     * @param $bot
     * @return array|bool|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function getInfoByWxid($wxid, $bot){
        if(empty($wxid)){
            return false;
        }
        $data = self::model()->getOneByMap(['uin' => $bot['uin'], 'wxid' => $wxid], true, true);
        if(empty($data)){
            $client = BotService::model()->getRobotClient($bot);
            $res = $client->getMemberInfo(['to_wxid' => $wxid]);

            if(!empty($res['code'])){
                $nickname = '';
                $remark_name = '';
                $username = '';
                $headimgurl = '';
                switch ($bot['protocol']){
                    case BotConst::PROTOCOL_EXTIAN:
                        $nickname = filter_emoji($res['data']['nickName'] ?? '');
                        $remark_name = filter_emoji($res['data']['reMark']??'');
                        $username = $res['data']['alias'] ?? '';
                        $headimgurl = $res['data']['headImg'] ?? '';
                        break;
                }

                $insert = [
                    'uin' => $bot['uin'],
                    'nickname' => $nickname,
                    'remark_name' => $remark_name,
                    'username' => $username,
                    'wxid' => $wxid,
                    'type' => BotConst::FRIEND,
                    'headimgurl' => $headimgurl
                ];
                if(strpos($wxid, '@chatroom') !== false){
                    $insert['type'] = BotConst::GROUP;
                }
                $data = self::model()->addOne($insert);
            }

        }
        return $data;

    }
}