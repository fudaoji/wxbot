<?php
/**
 * Created by PhpStorm.
 * Script Name: AddonsController.php
 * Create: 2023/5/23 11:56
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\addon;

use app\common\controller\BaseCtl;
use think\facade\View;

class AddonController extends BaseCtl
{
    protected $addonName = null;

    public function initialize(){
        $rule = $this->request->rule()->getRule();
        $rule_arr = explode('/', $rule);
        $this->addonName = $rule_arr[0];
        if(count($rule_arr) >= 4){
            $this->module = $rule_arr[1];
            $this->controller = $rule_arr[2];
            $this->action = $rule_arr[3];
        }else{
            $this->controller = $rule_arr[1];
            $this->action = $rule_arr[2];
        }
    }

    public function show($assign = [], $view = ''){
        $assign['module'] = $this->module;
        $assign['controller'] = $this->controller;
        $assign['action'] = $this->action;
        $assign['theme'] = config('view.theme');

        $this->assign = array_merge($this->assign, $assign);

        if (!$view) {
            $view = $assign['controller']. DIRECTORY_SEPARATOR.$assign['action'];
        }else{
            $view = strpos($view, '/') === false ? ($assign['controller']. DIRECTORY_SEPARATOR.$view) : $view;
        }

        $path = $this->addonName . DIRECTORY_SEPARATOR . ($this->module ? $this->module. DIRECTORY_SEPARATOR.'view' : 'view').
            ($assign['theme'] ? DIRECTORY_SEPARATOR . $assign['theme'] : '') . DIRECTORY_SEPARATOR;
        //使用绝对路径
        $template = config('addon.path') . $path .$view.'.'.config('view.view_suffix');
        return View::fetch($template, $this->assign);
    }
}