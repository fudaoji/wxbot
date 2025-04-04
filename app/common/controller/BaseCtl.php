<?php
/**
 * Created by PhpStorm.
 * Script Name: BaseCtl.php
 * Create: 2020/9/4 下午10:10
 * Description: 公共控制器基类
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\controller;

use app\BaseController;
use ky\ErWeiCode;
use think\App;
use think\facade\View;

class BaseCtl extends BaseController
{
    use \liliuwei\think\Jump;

    protected $assign = [];

    public function __construct()
    {
        parent::__construct(); // TODO: Change the autogenerated stub
    }

    public function initialize()
    {
        $this->isInstalled();
        model('common/setting')->settings();
    }

    /**
     * 统一视图
     * @param string $view
     * @param array $assign
     * @return mixed
     * @Author  fudaoji<fdj@kuryun.cn>
     */
    public function show($assign = [], $view = ''){
        $assign['module'] = $this->module;
        $assign['controller'] = $this->controller;
        $assign['action'] = $this->action;
        $assign['static_version'] = config("app.app_debug") ? time() : intval(config('system.site.version'));
        $assign['theme'] = config('view.theme');

        $this->assign = array_merge($this->assign, $assign);

        if (!$view) {
            $view = $assign['controller']. DIRECTORY_SEPARATOR.$assign['action'];
        }else{
            $view = strpos($view, '/') === false ? ($assign['controller']. DIRECTORY_SEPARATOR.$view) : $view;
        }
        return View::fetch(config('view.theme').DIRECTORY_SEPARATOR.$view, $this->assign);
    }

    /**
     * 方法不存在的时候
     * @param $name
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function _empty($name){
        $this->error($name . '方法不存在');
    }

    protected function getAjax()
    {
        $json = file_get_contents("php://input");
        return json_decode($json, 1);
    }

    public function isInstalled(){
        $root_path = app()->getRootPath();
        if (!is_file($root_path . '.env') || !is_file($root_path . '/install.lock')) {
            $this->redirect('install/index/index');
        }
    }

    /**
     * 在线二维码
     * Author: fudaoji<fdj@kuryun.cn>
     * @param array $options
     */
    public function getQrCode($options = []){
        $params = empty($options) ? input() : $options;
        if(empty($params['text'])){
            echo '参数错误';exit;
        }
        $code_o = new ErWeiCode();
        $level = empty($params['level']) ? QR_ECLEVEL_M : $params['level'];
        $size = empty($params['size']) ? 6 : $params['size'];
        $margin = empty($params['margin']) ? 1 : $params['margin'];
        $code_o->qrCode($params['text'], false, $level, $size, $margin);
    }
}