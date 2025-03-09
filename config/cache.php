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

$prefix = env('cache_prefix', env('app_prefix', 'wxbot_'));
$configs = [
    'file' => [
        // 驱动方式
        'type'       => 'File',
        // 缓存前缀
        'prefix' => $prefix,
        // 设置不同的缓存保存目录
        //'path'   => root_path() . 'runtime/cache/',
        // 缓存有效期 0表示永久缓存
        'expire'     => 0,
        // 缓存标签前缀
        'tag_prefix' => 'tag:',
        // 序列化机制 例如 ['serialize', 'unserialize']
        'serialize'  => [],
    ],
    // 更多的缓存连接
    // redis缓存
    'redis' => [
        // 缓存前缀
        'prefix' => $prefix,
        // 驱动方式
        'type' => 'redis',
        // 服务器地址
        'host' => env('redis.host', 'localhost'),
        'port' => env('redis.port', '6379'),
        // 缓存有效期 0表示永久缓存
        'expire' => 86400,
        'select' => 0
    ],
    'memcache' => [
        'type'  => 'memcached',
        // 缓存前缀
        'prefix' => $prefix,
        'host'  => env('memcached.host', 'localhost'),
        'port'  => env('memcached.port', 11211),
        // 缓存有效期 0表示永久缓存
        'expire' => 86400,
    ]
];


return [
    // 默认缓存驱动
    'default' => env('cache_type', 'file'),

    // 缓存连接方式配置
    'stores'  => $configs
];