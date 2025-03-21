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
use app\common\model\MsgGatherGroup;
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
            ])->toArray();
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
        $list = [];
        if($data){
            $content = $params['content'];
            $wxid = $params['wxid'];
            foreach ($data as $k => $rule){
                //去掉过期的
                if(!empty($rule['expire_time']) && $rule['expire_time'] < time()){
                    continue;
                }

                //指定用户筛选
                $wxids = [];
                if(empty($wxids) && !empty($rule['member_tags'])){
                    $tags = explode(',', $rule['member_tags']);
                    $wxids = explode(',', $rule['wxids']);
                    foreach ($tags as $tag){
                        $wxids = array_merge($wxids, model('admin/botMember')->getField('wxid', ['tags' => ['like', '%'.$tag.'%']]));
                    }
                }

                $wxids = array_unique($wxids);

                if(!empty($wxids) && !in_array($wxid, $wxids)) {
                    continue;
                }

                $msg_types = explode(',', $rule['msg_types']);
                //Logger::error('types:'.implode(',', $msg_types));
                if(!empty($msg_types) && !in_array($params['type'], $msg_types)) {
                    continue;
                }

                //关键词判断
                if(!empty($rule['keyword']) && $params['type'] == BotConst::MSG_TEXT){
                    $keyword_arr = explode(',', $rule['keyword']);
                    foreach ($keyword_arr as $keyword){
                        if(strpos($content['msg'], $keyword) !== false){
                            break; //只要有一个命中就记录
                        }
                    }
                }

                //Logger::error(4);
                array_push($list, $rule);
            }
        }
        return $list;
    }

    /**
     *
     * @param int $admin_id
     * @param int $bot_id
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function getSelectList($admin_id = 0,$bot_id=0){
        return self::model()->getField(['id','title'], ['admin_id' => $admin_id,'bot_id' => $bot_id], true);
    }

    /**
     *
     * @param int $admin_id
     * @param int $bot_id
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function getGroupSelectList($admin_id = 0,$bot_id=0){
        return (new MsgGatherGroup())->getField(['id','title'], ['admin_id' => $admin_id,'bot_id' => $bot_id], true);
    }
}