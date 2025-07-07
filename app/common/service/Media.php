<?php
/**
 * Created by PhpStorm.
 * Script Name: Media.php
 * Create: 2023/11/20 17:08
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\service;


use app\constants\Bot as BotConst;
use app\constants\Media as MediaConst;
use ky\Logger;

class Media
{
    /**
     * 根据消息类型获取模型
     * @param null $type
     * @return mixed|null
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function msgTypeToModel($type = null){
        $list = [
            BotConst::MSG_TEXT => model('MediaText'),
            BotConst::MSG_APP => model('MediaXml'),
            BotConst::MSG_IMG => model('MediaImage'),
            BotConst::MSG_FILE => model('MediaFile'),
            BotConst::MSG_VIDEO => model('MediaVideo'),
        ];
        return isset($list[$type]) ? $list[$type] : null;
    }

    static function types($type = null){
        $list = [
            MediaConst::IMAGE => model('MediaImage'),
            MediaConst::TEXT => model('MediaText'),
            MediaConst::FILE => model('MediaFile'),
            MediaConst::VIDEO => model('MediaVideo'),
            MediaConst::LINK => model('MediaLink'),
            MediaConst::XML => model('MediaXml'),
        ];
        return isset($list[$type]) ? $list[$type] : $list;
    }

    /**
     * @param array $params
     * @param bool $refresh
     * @return array|false|mixed|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function getMedia($params = [], $refresh = false){
        ksort($params);
        $params['media_id'] = intval($params['media_id']);
        $cache_key = serialize($params);
        $data = cache($cache_key);
        if(empty($data) || $refresh){
            $data = model('media_' . $params['media_type'])->getOneByMap([
                'admin_id' => $params['staff_id'],
                'id' => $params['media_id']
            ], true, true);
            if(empty($data)){
                $data = model('media_' . $params['media_type'])->getOneByMap([
                    'admin_id' => $params['admin_id'],
                    'id' => $params['media_id']
                ], true, true);
            }
        }
        cache($cache_key, $data);
        return $data;
    }

    /**
     * @param array $params
     * @param bool $refresh
     * @return array|false|mixed|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function searchByTag($params = [], $refresh = false){
        $medias = [];
        foreach (self::types() as $type => $model){
            $data = $model->getOneByMap([
                'admin_id' => ['in', [$params['staff_id'], $params['admin_id']]],
                'tags' => ['like', "%".$params['tag']."%"]
            ]);
            if(!empty($data)){
                $medias[] = ['media_id' => $data['id'], 'media_type' => $type];
            }
        }
        return $medias;
    }
}