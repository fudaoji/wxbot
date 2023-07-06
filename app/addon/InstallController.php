<?php
/**
 * Created by PhpStorm.
 * Script Name: InstallController.php
 * Create: 2023/5/24 11:41
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\addon;


abstract class InstallController
{
    abstract function install();
    abstract function upgrade();
    abstract function uninstall();
}