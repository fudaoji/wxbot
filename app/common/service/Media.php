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
        $cache_key = serialize($params);
        $data = cache($cache_key);
        if(empty($data) || $refresh){
            $data = model('media_' . $params['media_type'])->getOneByMap([
                'admin_id' => ['in', [$params['staff_id'], $params['admin_id']]],
                'id' => $params['media_id']
            ]);
        }

        cache($cache_key, $data);
        return $data;
    }
}