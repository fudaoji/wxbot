<?php
/**
 * Created by PhpStorm.
 * Script Name: Demo.php
 * Create: 2020/10/13 15:10
 * Description:
 * Author: Jason<dcq@kuryun.cn>
 */

namespace tests;

use GuzzleHttp\Client;
use think\facade\Log;

class HttpTestCase extends UnitTestCase
{
    protected $client;
    protected $tokenKey = 'fdj_text';
    protected $openid_fdj = 'oUcHy5LOrkjDoZtHqGFsiKJlLcQs';

    /**
     * 初始化
     * Author: Jason<dcq@kuryun.cn>
     */
    public function __construct() {
        parent::__construct();
        $this->client = new Client();
    }

    /**
     * 发送请求
     * @param array $params
     * @param string $api_url
     * @param bool $auth
     * @return mixed
     * Author: Jason<dcq@kuryun.cn>
     */
    public function request($params, $api_url, $auth = true) {
        $api_url = $this->baseUrl . $api_url;
        $params['timestamp'] = time();
        $headers = [
            'sign' => $this->calcuSign($params)
        ];
        if($auth) {
            $auth_token = json_decode(get_redis()->get($this->tokenKey), true);
            if(!empty($auth_token)) {
                $headers['token'] = $auth_token['token'];
            }else {
                Log::write('请先登录');
            }
        }
        $response = $this->client->post($api_url, [
            \GuzzleHttp\RequestOptions::JSON => $params,
            'headers' => $headers
        ]);
        if($response->getStatusCode() == 200){
            return json_decode($response->getBody()->getContents(), true);
        }
        return false;
    }

    /**
     * 计算签名
     * @param array $params
     * @return string
     * Author: Jason<dcq@kuryun.cn>
     */
    private function calcuSign($params) {
        if(count($params) == 0) {
            $params_str = '';
        }else {
            //签名步骤一：按字典序排序参数
            ksort($params);
            $params_str = "";
            foreach ($params as $k => $v) {
                if($k != "sign"){
                    $params_str .= ($k . "=" . json_encode($v,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) . "&");
                }
            }
            $params_str = trim($params_str, "&");
        }
        //签名步骤二：在string后加入KEY
        $params_str .= config('app.app_key');
        //签名步骤三：MD5加密
        return md5($params_str);
    }
}