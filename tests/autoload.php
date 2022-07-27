<?php
/**
 * Created by PhpStorm.
 * Script Name: autoload.php
 * Create: 2022/7/21 10:29
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */


use think\App;

require_once "./vendor/autoload.php";
// 看个人需求，需要整体初始化某些通用之类的可以考虑使用
require_once "UnitTestCase.php";

//(new App())->console->run();
(new App())->initialize();
//((new App())->http)->run();