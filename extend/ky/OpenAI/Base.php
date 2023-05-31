<?php
/**
 * Created by PhpStorm.
 * Script Name: Base.php
 * Create: 12/20/21 11:33 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky\OpenAI;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use ky\Logger;

Abstract class Base
{
    private $options = [];
    protected $errMsg = '';
    protected $appId = '';
    protected $baseUri = '';

    const ANSWER_TEXT = 'text';
    const ANSWER_MUSIC = 'music';

    /**
     * @var Client
     */
    private $client;

    public function __construct($options = [])
    {
        $this->options = array_merge($this->options, $options);
        if(!empty($this->options['appid'])){
            $this->appId = $this->options['appid'];
        }
    }

    abstract public function dealRes($res);

    public function setAppId($appid = ''){
        $this->appId = $appid;
        return $this;
    }

    public function setBaseUri($url = ''){
        $this->baseUri = $url;
        return $this;
    }

    protected function request($params = []){
        $this->client = new Client([
            // 不用证书验证，强制使用https
            'verify' => empty($this->options['verify']) ? false : $this->options['verify'],
            'base_uri' => $this->baseUri,
            'timeout' => empty($this->options['timeout']) ? 0 : $this->options['timeout']
        ]);
        $url = empty($params['url']) ? '/' : $params['url'];
        $method = empty($params['method']) ? 'post' : $params['method'];
        $extra = [
            'http_errors' => false
        ];
        $headers = [
            'Content-Type'     => 'application/json;charset=UTF-8',
        ];
        if(!empty($params['headers'])){
            $headers = array_merge($headers, $params['headers']);
        }
        $extra['headers'] = $headers;
        if(!empty($params['data'])){
            if(isset($params['content_type']) && $params['content_type'] === 'form_params'){
                $extra['form_params'] = $params['data'];
                $extra['headers']['Content-Type'] = 'application/x-www-form-urlencoded';
            }else{
                switch ($method){
                    case 'get':
                        $url .= '?' . http_build_query($params['data']);
                        break;
                    default:
                        $extra['json'] = $params['data'];
                        break;
                }
            }
        }

        $response = $this->client->request($method, $url, $extra);

        if($response->getStatusCode() !== 200){
            $this->setError($response->getStatusCode());
            return false;
        }
        //Logger::error($response->getBody()->getContents());
        return $this->dealRes(json_decode($response->getBody()->getContents(), true));
    }

    public function setError($code = 200){
        $list = [
            401 => '获取token失败',
            404 => '接口路径与请求方式错误',
            429 => '接口请求频率超过限制',
            500 => '服务端错误'
        ];
        $this->errMsg = isset($list[$code]) ? ($code . ':' .$list[$code]) : ($code.':未知错误');
    }

    public function getError(){
        return $this->errMsg;
    }

    /**
     * 接口暂未开放
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    protected function apiUnSupport(){
        return ['code' => 0, 'errmsg' => '此接口暂不支持'];
    }
}