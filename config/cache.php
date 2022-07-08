<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 缓存设置
// +----------------------------------------------------------------------
use think\facade\Env;

$configs = [
    'file' => [
        // 驱动方式
        'type' => 'file',
        // 缓存前缀
        'prefix' => Env::get('app_prefix', 'ky_'),
        // 设置不同的缓存保存目录
        'path'   => env('runtime_path').'cache/',
    ],
    'memcache' => [
        // 缓存前缀
        'prefix' => Env::get('app_prefix', 'ky_'),
        'type'  => 'memcached',
        'host'  => Env::get('memcached.host', 'localhost'),
        'port'  => Env::get('memcached.port', 11211)
    ],
    'redis' => [
        // 缓存前缀
        'prefix' => Env::get('app_prefix', 'ky_'),
        // 驱动方式
        'type' => 'redis',
        // 服务器地址
        'host' => Env::get('redis.host', 'localhost'),
        'port' => Env::get('redis.port', '6379')
    ]
];
return [
    // 使用复合缓存类型
    'type'  =>  'complex',
    // 缓存有效期 0表示永久缓存
    'expire' => 0,

    // 默认使用的缓存
    'default' => $configs[Env::get('cache_type', 'file')],
    // 文件缓存
    'file'   =>  $configs['file'],
    // memcache缓存
    'memcached' =>  $configs['memcache'],
    // redis缓存
    'redis' => $configs['redis']
];
