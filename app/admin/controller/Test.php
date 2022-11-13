<?php
/**
 * Created by PhpStorm.
 * Script Name: Test.php
 * Create: 12/20/21 11:49 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\controller;

class Test
{

    public function index(){
        return response('hello thinkphp6');
    }
}