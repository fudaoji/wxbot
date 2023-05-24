<?php

use think\facade\Route;

Route::rule('demo/admin/index/index',"\app\addons\demo\admin\controller\Index::index");
Route::rule('demo/admin/index/detail',"\app\addons\demo\admin\controller\Index::detail");