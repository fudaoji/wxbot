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

$addons = get_local_addons();
foreach ($addons as $addon){
    $route = root_path('addons'.DIRECTORY_SEPARATOR.$addon) . 'route.php';
    if(file_exists($route)){
        if(is_callable($rules = require_once $route)){
            $rules();
        }
    }
}
return [];
