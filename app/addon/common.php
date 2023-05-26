<?php

if(!function_exists('get_addon_menu')) {
    function get_addon_menu(string $name = ''){
        $info = [];
        if(empty($name)){
            $rule_arr = explode('/', request()->rule()->getRule());
            $name = $rule_arr[0];
        }
        $path = root_path(config('addon.pathname') . DIRECTORY_SEPARATOR . $name) . 'menu.php';
        if(is_file($path)){
            $info = require $path;
        }
        return $info;
    }
}

/**
 * Created by PhpStorm.
 * Script Name: common.php
 * Create: 2023/5/23 16:38
 * Description: 插件url生成器
 * Author: fudaoji<fdj@kuryun.cn>
 * @param string $url
 * @param array $vars
 * @param bool $suffix
 * @param bool $domain
 * @return string
 */
if(!function_exists('addon_url')) {
    function addon_url(string $url = '', array $vars = [], $suffix = true, $domain = false)
    {
        $url = trim($url, '/');
        $module = request()->root();
        $rule = request()->rule()->getRule();
        $rule_arr = explode('/', $rule);
        $addon = $rule_arr[0];
        return url("/{$module}/{$addon}/{$url}", $vars, $suffix, $domain)->build();
    }
}

if(!function_exists('get_local_addons')){
    function get_local_addons()
    {
        $path = root_path(config('addon.pathname'));
        $files = scandir($path);
        $addons = [];
        foreach ($files as $v) {
            $file = $path . DS . $v;
            if (is_dir($file) && $v != '.' && $v != '..') {
                $addons[] = $v;
            }
        }
        return $addons;
    }
}

if(!function_exists('include_addon_func')) {
    function include_addon_func()
    {
        $addons = get_local_addons();
        foreach ($addons as $addon) {
            if (file_exists($func_file = config('addon.path') . $addon . DIRECTORY_SEPARATOR . 'common.php')) {
                require_once $func_file;
            }
        }
    }
}
include_addon_func();
