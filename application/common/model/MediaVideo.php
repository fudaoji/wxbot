<?php


namespace app\common\model;

class MediaVideo extends Base
{
    protected $isCache = true;
    protected $key = 'admin_id';
    protected $rule = [
        'type' => 'mod', // 分表方式
        'num'  => 5      // 分表数量
    ];
}