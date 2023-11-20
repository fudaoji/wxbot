<?php
/**
 * Created by PhpStorm.
 * Script Name: MsgGather.php
 * Create: 2023/11/20 10:09
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\service;
use app\common\model\MsgGather as GatherM;
use app\constants\Bot as BotConst;
use ky\Logger;

class MsgGather
{
    static $model = null;

    static function model(){
        if(is_null(self::$model)){
            self::$model = new GatherM();
        }
        return self::$model;
    }

    static function searchData($params, $refresh = false){
        $where = [
            'bot_id' => $params['bot_id'],
            'status' => 1
        ];
        ksort($where);
        $cache_key = serialize($where);
        $data = cache($cache_key);
        if(empty($data) || $refresh){
            $data = self::model()->getAll([
                'where' => $where,
                'refresh' => $refresh
            ]);
        }
        cache($cache_key, $data);
        return $data;
    }

    /**
     * 判断是否保存
     * @param array $params
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function check($params = []){
        $data = self::searchData([
            'bot_id' => $params['bot_id']
        ]);
        if($data){
            $wxid = $params['wxid'];
            foreach ($data as $k => $rule){
                $wxids = explode(',', $rule['wxids']);
                if(empty($wxids) && !empty($rule['member_tags'])){
                    $tags = explode(',', $rule['member_tags']);
                    foreach ($tags as $tag){
                        $wxids = array_merge($wxids, model('admin/botMember')->getField('wxid', ['tags' => ['like', '%'.$tag.'%']]));
                    }
                }
                $wxids = array_unique($wxids);
                if(!empty($wxids) && !in_array($wxid, $wxids)) {
                    unset($data[$k]);
                    continue;
                }
                $msg_types = explode(',', $rule['msg_types']);
                if(!empty($msg_types) && !in_array($params['type'], $msg_types)) {
                    unset($data[$k]);
                    continue;
                }
            }
        }
        return $data;
    }
}