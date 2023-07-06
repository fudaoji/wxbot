<?php
/**
 * Created by PhpStorm.
 * Script Name: Database.php
 * Create: 2022/12/5 9:02
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\service;

class Database
{
    static function getDatabaseName(){
        return config('database.connections')[config('database.default')]['database'];
    }

    static function getTablePrefix(){
        return config('database.connections')[config('database.default')]['prefix'];
    }
}