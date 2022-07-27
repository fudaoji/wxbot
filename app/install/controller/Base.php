<?php
/**
 * Created by PhpStorm.
 * Script Name: Base.php
 * Create: 2022/7/7 11:23
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\install\controller;

use app\BaseController;
use liliuwei\think\Jump;
use think\facade\View;

class Base extends BaseController
{
    use Jump;

    protected $assign = [];
    protected $module = 'install';
    protected $controller;
    protected $action;

    public function __construct()
    {
        parent::__construct();
    }

    public function initialize()
    {
        if (request()->action() != 'complete' && is_file(app()->getRootPath() . '/install.lock')) {
            $this->redirect(url('admin/index/index'));
        }
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
        $assign['company'] = '厦门酷云网络科技有限公司';
        $assign['official_web'] = 'https://kyphp.kuryun.com/home/guide/bot.html';
        $assign['app_name'] = env('app_name', 'WxBot');

        $this->assign = array_merge($this->assign, $assign);

        if (!$view) {
            $view = $assign['action'];
        }
        return View::fetch($view, $this->assign);
    }

}