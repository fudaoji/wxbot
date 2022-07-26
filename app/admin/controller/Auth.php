<?php
/**
 * Created by PhpStorm.
 * Script Name: Auth.php
 * Create: 2020/9/7 8:38
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */
namespace app\admin\controller;
use think\captcha\facade\Captcha;
use think\facade\Session;
use think\facade\Validate;
use app\admin\model\Admin;

class Auth extends Base
{
    /**
     * @var Admin
     */
    protected $model;
    protected $needLogin = false;

    public function initialize()
    {
        parent::initialize();
        $this->model = new Admin();
    }

    /**
     * 验证码
     * @return mixed
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function verify()
    {
        return Captcha::create('admin');
    }

    /**
     * 登录
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     * @throws \think\Exception
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function login(){
        if (request()->isPost()) {
            $post_data = input('post.');
            $validate = Validate::rule([
                'verify|验证码'    => 'require|captcha',
                'username'   => 'require|length:3,20',
                'password'  => 'require|length:6,20',
                '__token__' => 'require|token',
            ])->message([
                'username.require'  => '请填写账号',
                'username.length'   => '账号错误',
                'password.require' => '请填写密码',
                'password.length'  => '密码错误',
            ]);
            $data = [
                'verify'   => $post_data['verify'],
                'username'   => $post_data['username'],
                'password'  => $post_data['password'],
                '__token__' => $post_data['__token__'],
            ];
            $result = $validate->check($data);
            if (!$result) {
                $this->error($validate->getError(), null, ['token' => token()]);
                return false;
            }

            $user = $this->model->getOneByMap(['username' => $post_data['username']]);
            if ($user && $user['status'] == 1) {
                if(password_verify($post_data['password'], $user['password'])){
                    $this->model->updateOne([
                        'id' => $user['id'],
                        'ip' => request()->ip(),
                        'last_time' => time()
                    ]);
                    session($this->sKey, $user['id']);
                    if(!empty($post_data['keeplogin'])){
                        cookie('record_admin', $post_data['username']);
                    }
                    $this->success('登录成功!', cookie('redirect_url') ? cookie('redirect_url') : url('index/index'));
                }else{
                    $this->error('账号或密码错误', '', ['token' => token()]);
                }
            }else{
                $this->error('用户不存在或已被禁用', '', ['token' => token()]);
            }
        }

        if(session($this->sKey)){
            $this->redirect(url('index/index'));
        }
        return $this->show(['username' => cookie('record_admin')]);
    }

    /**
     * 退出
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function logout()
    {
        Session::clear();
        return $this->redirect(url('login'));
    }
}