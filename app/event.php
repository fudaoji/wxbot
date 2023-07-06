<?php
// 事件定义文件
return [
    'bind'      => [
        'UserLogin'    =>  'app\event\UserLogin',
    ],

    'listen'    => [
        'AppInit'  => [],
        'HttpRun'  => [],
        'HttpEnd'  => [],
        'LogLevel' => [],
        'LogWrite' => [],

        'UserLogin'    =>    ['app\listener\UserLogin'], //用数组说明可以被多个行为处理
    ],

    'subscribe' => [
    ],
];
