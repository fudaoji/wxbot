<?php
/**
 * Created by PhpStorm.
 * Script Name: Sms.php
 * Create: 2016/12/7 下午3:19
 * Description:
 * e.g:
 * $content = '验证码：2323，打死也不能告诉别人。【酷云】';
 * $mobile = '13511111111';
 * $sms = new \ky\Sms('账号', '密码');   //使用示远短信
 * $sms = new \ky\Sms('账号', '密码', 'yunxin');   //使用中国移动短信，记得要先在对应平台设置短信模版
 * $sms->send($mobile, $content);
 * Author: Doogie<461960962@qq.com>
 */

namespace ky;
class Sms
{
    protected $api;
    protected $driver;
    protected $error;

    public function __construct($account='', $pwd='', $driver='')
    {
        $account = $account ? $account : config('system.sms.sms_account');
        $pwd = $pwd ? $pwd : config('system.sms.sms_pwd');
        empty($driver) && $driver = config('system.sms.sms_type') ? config('system.sms.sms_type') : 'shiyuan';
		$this->driver = $driver;
        $class = '\\ky\\Sms\\' . ucfirst(strtolower($this->driver));
        $this->api = new $class($account, $pwd);
        if(!$this->api){
            throw new \Exception("不存在短信驱动：{$driver}");
        }
    }

    /**
     * 发送短信
     * @param string $mobile
     * @param string $content
     * @return bool|mixed
     * @author: Doogie<461960962@qq.com>
     */
    public function send($mobile='', $content=''){
        $res = $this->api->send($mobile, $content);
        if($res === true){
            return true;
        }else{
            return $this->getError();
        }
    }

    /**
     * 返回错误信息
     * @return mixed
     * @author: Doogie<461960962@qq.com>
     */
    public function getError(){
        return $this->api->getError();
    }
}
