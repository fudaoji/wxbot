<?php
// +----------------------------------------------------------------------
// | [KyPHP System] Copyright (c) 2020 http://www.kuryun.com/
// +----------------------------------------------------------------------
// | [KyPHP] 并不是自由软件,你可免费使用,未经许可不能去掉KyPHP相关版权
// +----------------------------------------------------------------------
// | Author: fudaoji <fdj@kuryun.cn>
// +----------------------------------------------------------------------
/**
 * Created by PhpStorm.
 * Script Name: Mini.php
 * Create: 2020/7/22 18:05
 * Description: 小程序相关
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\event;

use EasyWeChat\Factory;
use ky\ErrorCode;
use ky\Logger;
use ky\MiniPlatform\ErrorMsg;
use think\Db;
use think\facade\Log;
use Intervention\Image\ImageManagerStatic as Image;

class Mini extends Base
{

    /**
     * 通用生成海报
     * @param array $list
     * @return string
     * Author: Doogie<fdj@kuryun.cn>
     */
    public function generatePoster($list = [])
    {
        Image::configure(['driver' => 'imagick']);
        //背景图片
        $image = Image::make($list['bg']);
        foreach ($list['items'] as $params){
            switch ($params['type']){
                case 'text':
                    try {
                        $image->text($params['value'], $params['position'][0], $params['position'][1], function ($font) use($params) {
                            $font->file(empty($params['family']) ? '/usr/share/fonts/chinese/MSYH.TTF' : $params['family']);
                            $font->size($params['size']);
                            $font->color($params['color']);
                            !empty($params['align']) && $font->align($params['align']);
                            !empty($params['valign']) && $font->valign($params['valign']);
                        });
                    }catch(\Exception $e){
                        Logger::write('写' . $params['title'].'错误：' . json_encode($e->getMessage()));
                        return false;
                    }
                    break;
                case 'image':
                    $flag = false;
                    $count = 0;
                    while ($flag === false && $count < 10) {
                        $count++;
                        try {
                            $head = Image::make($params['value']);
                            if(!empty($params['size'])){
                                $head = $head->fit($params['size'][0], $params['size'][1]);
                            }
                            if(!empty($params['corner'])){
                                $head->getCore()->roundCorners($params['corner'][0], $params['corner'][1]); //getCore()方法指向了原生的Imagick类的对象
                            }
                            $image->insert($head, $params['position_name'], $params['position'][0], $params['position'][1]);
                            $flag = true;
                        } catch (\Exception $e) {
                            Logger::write($params['title'] . '放入背景图出错：' . json_encode($e->getMessage()));
                            return false;
                        }
                    }
                    if($flag === false){
                        return false;
                    }
                    break;
                case 'line':
                    try {
                        $image->line($params['point1'][0], $params['point1'][1], $params['point2'][0],$params['point2'][1], function ($draw) use($params) {
                            $draw->color($params['color']);
                            !empty($params['with']) && $draw->with($params['with']);
                        });
                    }catch(\Exception $e){
                        Logger::write('画' . $params['title'].'错误：' . json_encode($e->getMessage()));
                        return false;
                    }
                    break;
            }
        }

        try {
            $pic_name = $list['pic_name'] . '.png';
            $save_path = request()->server()['DOCUMENT_ROOT'] . '/uploads/qrcode/' . $pic_name;
            is_file($save_path) && @unlink($save_path);
            $image->save($save_path);
            $res = fetch_img(request()->domain() . '/uploads/qrcode/' . $pic_name, $pic_name);
            is_file($save_path) && @unlink($save_path);
            return $res;
        } catch (\Exception $e) {
            Logger::write('保存海报失败： ' . json_encode($e->getMessage()));
            return false;
        }
    }

    /**
     * 生成活动小程序码
     * @param array $params
     * @return bool|string
     * Author: Doogie<fdj@kuryun.cn>
     * @throws \Exception
     */
    public function generateQr($params = []){
        //生成活动小程序码
        $app = $this->getApp();
        //生成活动小程序码
        if(isset($params['type']) && $params['type'] == 'unlimit'){
            $response = $app->app_code->getUnlimit($params['scene'], [
                'page'  => $params['path'],
            ]);
        }else{
            $response = $app->app_code->get($params['path']);
        }
        if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
            $save_path = './uploads/qrcode/';
            if(!is_dir($save_path)){
                @mkdir($save_path, 0777, true);
            }
            $code_name = $response->saveAs($save_path, $params['filename']);
            $code_url = '/uploads/qrcode/' . $code_name;  //小程序码访问url

            $qiniuClass = $this->getQiniu();
            $qiniu_key = $qiniuClass->fetch(request()->domain() . $code_url, time() . $params['filename']);
            if($qiniu_key){
                @unlink('.' . $code_url);
                return $qiniuClass->downLink($qiniu_key);
            }else{
                Logger::write('生成小程序码失败: ' . $qiniuClass->getError());
            }
        }else{
            Logger::write('生成小程序码失败: ' . ErrorMsg::getErrorMsg($response['errcode']));
        }
        return false;
    }

    /**
     * 获取公众号APP
     * Author: fudaoji<fdj@kuryun.cn>
     * @return \EasyWeChat\MiniProgram\Application|\EasyWeChat\OpenPlatform\Authorizer\MiniProgram\Application
     * @throws \Exception
     */
    public function getApp(){
        $config = [
            'app_id'   => config('system.weixin.appid'),
            'secret'   => config('system.weixin.secret'),
            'response_type' => 'array',
            'log' => [
                'level' => 'error',
                'file'  => RUNTIME_PATH . 'log/mini.log',
            ],
        ];
        return Factory::miniProgram($config);
    }

    /**
     * 获取小程序支付配置
     * @param int $mini_id
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getPayConfig(){
        return [
            'appid'     => config('system.weixin.pay_appid'),
            'appsecret' => config('system.weixin.pay_secret'),
            'mchid'     => config('system.weixin.pay_merchant_id'), //商户号
            'key'       => config('system.weixin.pay_key'), //API秘钥
            'sslcert_path' => config('system.weixin.pay_cert_path'),
            'sslkey_path' => config('system.weixin.pay_key_path'),
            'rsa_path'  => ''
        ];
    }
}