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
    protected $module = '';
    protected $urlPrefix = ''; //配合addon_url快速填写 $urlPrefix . $action
    protected $addonMenus = [];
    protected $addonInfo;

    public function initialize(){
        parent::initialize();
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
        $this->urlPrefix = $this->module . '/'.$this->controller . '/';
        $this->addonMenus = get_addon_menu($this->addonName);
        $this->addonInfo = get_addon_info();
        if(empty($this->addonInfo['status'])){
            exit('应用不存在或已下架');
        }
    }

    public function show($assign = [], $view = ''){
        $assign = array_merge($this->assign, $assign, [
            'module' => $this->module,
            'controller' => $this->controller,
            'action' => $this->action,
            'theme' => config('view.theme'),
            'app_info' => $this->addonInfo,
            'addon_menus' => $this->addonMenus
        ]);

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
        $driver->fetch($view, $assign);
    }

    /**
     * 应用内省略模块和控制的快速url
     * @param string $url
     * @param array $vars
     * @param bool $suffix
     * @param bool $domain
     * @return string
     * Author: fudaoji<fdj@kuryun.cn>
     */
    protected function url(string $url = '', array $vars = [], $suffix = true, $domain = false){
        $url_arr = explode('/', $url);
        switch (count($url_arr)){
            case 1:
                $url = $this->module.'/'.$this->controller . '/'.$url;
                break;
            case 2:
                $url = $this->module.'/'.$url;
                break;
        }
        return addon_url($url, $vars, $suffix, $domain);
    }
}