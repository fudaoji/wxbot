<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------
use think\facade\Env;

return [
    'default'     => 'redis',
    'connections' => [
        'sync'     => [
            'type' => 'sync',
        ],
        'redis'    => [
            'type'       => 'redis',
            'queue'      => Env::get('queue.name', 'wxbot'),
            'host'       => Env::get('redis.host', 'localhost'),	// redis 主机ip
            'port'       => Env::get('redis.port', '6379'),		// redis 端口
            'password'   => '',
            'select'     => 0,
            'timeout'    => 0,
            'persistent' => false,
        ],
    ],
];
