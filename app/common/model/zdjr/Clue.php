<?php
/**
 * Created by PhpStorm.
 * Script Name: Clue.php
 * Create: 2022/9/6 17:56
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model\zdjr;


use app\constants\Bot;

class Clue extends Zdjr
{
    protected $table = 'clue';
    protected $isCache = false;

    const TYPE_QQ = 'QQ';
    const TYPE_MOBILE = 'mobile';
    const TYPE_WXNUM = 'wxnum';
    const TYPE_WXID = 'wxid';

    const STEP_NOT = 0;
    const STEP_APPLIED = 1;
    const STEP_ADDED = 3;
    const STEP_FAILED= 5;

    public static function types($id = null){
        $list = [
            self::TYPE_MOBILE => '手机号',
            self::TYPE_QQ => 'QQ',
            self::TYPE_WXNUM => '微信号',
            self::TYPE_WXID => 'Wxid',
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }

    public static function steps($id = null){
        $list = [
            self::STEP_NOT => '待申请',
            self::STEP_APPLIED => '已发请求',
            self::STEP_ADDED => '已通过',
            self::STEP_FAILED => '添加失败',
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }

    public static function sceneMap(){
        return [
            self::TYPE_MOBILE => Bot::SCENE_CONTACT,
            self::TYPE_WXNUM => Bot::SCENE_WXNUM,
            self::TYPE_QQ => Bot::SCENE_WXNUM
        ];
    }
}