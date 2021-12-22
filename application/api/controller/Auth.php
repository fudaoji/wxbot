<?php
/**
 * Created by PhpStorm.
 * Script Name: Auth.php
 * Create: 2020/10/13 16:48
 * Description: 用户认证
 * Author: Jason<dcq@kuryun.cn>
 */

namespace app\api\controller;

use app\api\helper\AuthHelper;
use ky\ErrorCode;
use ky\Helper;
use think\Db;
use think\facade\Log;
use ky\Logger;

class Auth extends Base
{
    /**
     * 初始化
     * Author: Jason<dcq@kuryun.cn>
     */
    public function initialize() {
        if(in_array(strtolower(request()->action()), ['tokenpost'])){
            $this->needToken = false;
        }
        parent::initialize();
    }

    /**
     * 获取token
     * Author: Jason<dcq@kuryun.cn>
     */
    public function tokenPost() {
        AuthHelper::tokenPostValid();
        $this->userInfo = model('User')->getOneByMap(['openid' => Helper::$param['openid']]);
        if(!$this->userInfo) {
            $this->userInfo = model('User')->addOne(['openid' => Helper::$param['openid']]);
        }
        $this->token = md5(uniqid());
        controller('common/base', 'event')->getRedis()->setex($this->token, 86400 * 7, json_encode(['openid' => $this->userInfo['openid']]));

        Helper::success('success', ['token' => $this->token, 'user_info' => $this->userInfo]);
    }

    /**
     * 获取授权用户信息
     * Author: Jason<dcq@kuryun.cn>
     */
    public function wxAuthPost(){
        AuthHelper::wxAuthPostValid();
        $update_data = Helper::$param;
        $update_data['id'] = $this->userInfo['id'];
        Db::startTrans();
        try{
            $this->userInfo = model('User')->updateOne($update_data);
            model('User')->getOneByMap([
                'openid' => $this->jscode2session['openid']
            ], true, true);
            Db::commit();
            Helper::success('success', ['user_info' => $this->userInfo]);
        }catch (\Exception $e){
            Logger::error($e->getMessage());
            Db::rollback();
            Helper::error(ErrorCode::BadParam, '授权出错，请联系客服');
        }
    }
}
