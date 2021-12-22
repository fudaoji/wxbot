<?php
/**
 * Created by PhpStorm.
 * Script Name: Base.php
 * Create: 12/20/21 11:33 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky\Bot;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

class Base
{
    private $client;
    private $options = [];
    protected $baseUri = '';
    private $errMsg = '';
    protected $appKey = '';

    public function __construct($options = [])
    {
        $this->options = array_merge($this->options, $options);
        if(!empty($this->options['appKey'])){
            $this->appKey = $this->options['appKey'];
        }
    }

    protected function request($params = []){
        $this->client = new Client([
            'base_uri' => empty($this->options['base_uri']) ? $this->baseUri : $this->options['base_uri'],
            'timeout' => empty($this->options['timeout']) ? 10 : $this->options['timeout']
        ]);
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
                        $params['url'] .= '?' . http_build_query($params['data']);
                        break;
                    default:
                        $extra['json'] = $params['data'];
                        break;
                }
            }
        }

        $response = $this->client->request($method, $params['url'], $extra);

        if($response->getStatusCode() !== 200){
            $this->setError($response->getStatusCode());
            return false;
        }
        //return $response->getBody()->getContents();
        return json_decode($response->getBody()->getContents(), true);
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

}