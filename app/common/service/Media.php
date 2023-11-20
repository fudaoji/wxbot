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
}