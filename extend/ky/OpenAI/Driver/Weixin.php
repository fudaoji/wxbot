<?php
/**
 * Created by PhpStorm.
 * Script Name: Weixin.php
 * Create: 8/1/22 9:10 AM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky\OpenAI\Driver;


use ky\OpenAI\Base;

class Weixin extends Base
{
    private $token;
    private $encodingAESKey;
    private $signature = '';
    protected $baseUri = "https://openai.weixin.qq.com";
    private $needSignature = true;

    const API_GET_SIGNATURE = '/openapi/sign';
    const API_SMART = '/openapi/aibot';

    public function __construct($options = [])
    {
        parent::__construct($options);
        $this->token = $options['token'];
        $this->encodingAESKey = $options['encoding_aes_key'];
    }

    /**
     * 对话
     * req:{signature: required, query: required, env string, first_priority_skills[], second_priority_skills[]}
     * @param array $params
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function smart($params = []){
        $params['query'] = $params['msg'];
        return $this->doRequest($params, self::API_SMART);
    }

    /**
     * 获取签名
     * req:{userid: required, username: , avatar}
     * @param array $params
     * @return string
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getSignature($params = []){
        $this->needSignature = false;
        $cache_key = 'openai.weixin.signature';
        if(empty($this->signature = (string) cache($cache_key))){
            $res = $this->doRequest($params, self::API_GET_SIGNATURE);
            if(!empty($res['signature'])){
                $signature = $res['signature'];
                cache($cache_key, $signature, time() + $res['expiresIn'] - 60);
                $this->signature = $signature;
            }else{
                $this->errors($res['errcode']);
            }
        }
        return $this->signature;
    }

    private function doRequest($params = [], $api = ''){
        $url = $api . "/" . $this->token;
        $body = $params;
        $this->needSignature && $body['signature'] = $this->getSignature($params);
        return $this->request([
            'data' => $body,
            'url' => $url
        ]);
    }

    /**
     *
     * @param $res
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function dealRes($res)
    {
        if(empty($res['errcode'])){
            $res['code'] = 1;
        }else{
            $res['code'] = 0;
            $this->errors($res['errcode']);
            $res['errmsg'] = $this->errMsg;
        }
        return $res;
    }

    private  function  errors($err_no = -1){
        $list = [
            1001 => 'token无效',
            1002 => '机器人审核没有通过',
            1003 => '签名缺少 userid 字段',
            1004 => '签名字段为空',
            1005 => '签名过期或无效',
            1006 => '签名校验失败，缺少 userid 字段'
        ];
        $this->errMsg = isset($list[$err_no]) ? $list[$err_no] : '未知错误';
    }
}