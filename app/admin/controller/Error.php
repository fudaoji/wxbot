<?php
/**
 * Created by PhpStorm.
 * Script Name: Error.php
 * Create: 2022/7/25 13:39
 * Description: 空控制器处理
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\controller;


class Error
{
    public function __call($method, $args)
    {
        return '控制器不存在！';
    }
}