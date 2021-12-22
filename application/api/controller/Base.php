<?php
/**
 * Created by PhpStorm.
 * Script Name: Base.php
 * Create: 2020/7/29 9:07
 * Description: 接口基类
 * Author: fudaoji<fdj@kuryun.cn>
 */
namespace app\api\controller;

use ky\ErrorCode;
use ky\Helper;
use think\Controller;

class Base extends Controller
{
    protected $needToken = true;  //是否需要token
    protected $token = '';
    protected $jscode2session = [];
    protected $userInfo;
    protected $miniApp; //小程序APP

    /**
     * 初始化
     * Author: Jason<dcq@kuryun.cn>
     */
    public function initialize() {
        parent::initialize();
        Helper::$ajax = $this->getAjax();
        model('common/setting')->settings(1);
        //$this->setApp();
        $this->checkToken();
    }

    /**
     * 设置小程序应用
     * Author fudaoji<fdj@kuryun.cn>
     */
    protected function setApp() {
        $this->miniApp = controller('mini/mini', 'event')->getApp();
    }

    /**
     * 校验请求token
     * @throws \think\exception\DbException
     * @author: fudaoji<fdj@kuryun.cn>
     */
    protected function checkToken() {
        $this->checkSign();
        if($this->needToken) {
            $header = request()->header();
            if(empty($header['token'])){
                Helper::error(ErrorCode::RedirectAjax, 'token缺失');
            }
            $this->token = $header['token'];
            $this->jscode2session = json_decode(controller('common/base', 'event')->getRedis()->get($this->token), true);
            if($this->jscode2session) {
                //续时
                controller('common/base', 'event')->getRedis()->setex($this->token, 86400 * 7, json_encode($this->jscode2session));
            } else {
                Helper::error(ErrorCode::RedirectAjax, 'token过期');
            }
            $this->setUserInfo();
        }
    }

    /**
     * 设置用户信息
     * @throws \think\exception\DbException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    protected function setUserInfo(){
        $this->userInfo = model('User')->getOneByMap(['openid' => $this->jscode2session['openid']]);
    }

    /**
     * 获取AJAX请求参数
     * @author fudaoji<fdj@kuryun.cn>
     */
    protected function getAjax() {
        $json = file_get_contents("php://input");
        return json_decode($json, 1);
    }

    /**
     * 签名验证
     * Author: Jason<dcq@kuryun.cn>
     */
    protected function checkSign(){
        $params = Helper::$ajax;
        if(empty($params)){
            $params_str = '';
        }else{
            //签名步骤一：按字典序排序参数
            ksort($params);
            $params_str = "";
            foreach ($params as $k => $v)
            {
                if($k != "sign"){
                    $params_str .= ($k . "=" . json_encode($v,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) . "&");
                }
            }
            $params_str = trim($params_str, "&");
        }
        //签名步骤二：在string后加入KEY
        $params_str .= config('app_key');
        //签名步骤三：MD5加密
        $sign = md5($params_str);
        //判断sign
        if($sign !== request()->header()['sign']){
            abort(ErrorCode::ErrorParam, '非法请求');
        }
    }
}