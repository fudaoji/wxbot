<?php
/**
 * Created by PhpStorm.
 * Script Name: Index.php
 * Create: 2023/5/23 11:03
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\addons\demo\admin\controller;

use app\addons\AddonController;
use think\facade\Route;

class Index extends AddonController
{

    public function index(){
        url();
        $this->success('ss', '/addons/demo/admin/index/detail');
        return $this->show();
    }

    public function detail(){
        dump(addon_url());exit;
        //dump(url('addons/demo/admin/index/index'));exit;
        return $this->show();
    }
}