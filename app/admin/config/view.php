<?php
// +----------------------------------------------------------------------
// | 模板设置
// +----------------------------------------------------------------------

return [
    // 模板目录名
    'view_dir_name' => 'themes',
    'theme' => 'default',
    'layout_on'     =>  true,
    'layout_name'   =>  'default/layout/base',

    // 视图中使用的常量
    'tpl_replace_string'  =>  [
        '__STATIC__' => '/static/',
        '__LIB__' => '/static/libs/',
        '__CSS__' => '/static/css/',
        '__JS__' => '/static/js/',
        '__IMG__' => '/static/imgs/'
    ],
];