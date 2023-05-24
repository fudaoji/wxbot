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
        $assign['app_info'] = get_addon_info();

        $this->assign = array_merge($this->assign, $assign);

        if (!$view) {
            $view = $assign['controller']. DIRECTORY_SEPARATOR.$assign['action'];
        }else{
            $view = strpos($view, '/') === false ? ($assign['controller']. DIRECTORY_SEPARATOR.$view) : $view;
        }

        $path = $this->addonName . DIRECTORY_SEPARATOR . ($this->module ? $this->module. DIRECTORY_SEPARATOR.'view' : 'view').
            ($assign['theme'] ? DIRECTORY_SEPARATOR . $assign['theme'] : '') . DIRECTORY_SEPARATOR;

        // 设置模板引擎参数
        $config = array_merge(config('view'), [
            'view_path'	=>	config('addon.path') . $path,
        ]);

        $driver = new \think\Template($config);
        $driver->fetch($view, $this->assign);
    }
}