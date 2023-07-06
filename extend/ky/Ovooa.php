<?php
/**
 * Created by PhpStorm.
 * Script Name: Ovooa.php
 * Create: 5/28/22 11:58 AM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky;

use GuzzleHttp\Client;

class Ovooa
{
    private static $instance;
    private $client;
    private $options = [];
    protected $baseUri = 'http://ovooa.com';
    protected $errMsg = '';

    const API_MIGU = '/API/migu/api.php';

    public function __construct($params = [])
    {
    }

    /**
     * 单例对象
     * @param array $params
     * @return self
     * @author: Doogie<461960962@qq.com>
     */
    public static function getInstance($params = []) {
        if (empty(self::$instance)) {
            self::$instance = new self($params);
        }
        return self::$instance;
    }

    /**
     * 咪咕音乐
     * https://ovooa.com/?action=doc&id=80
     * @param array $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function migu($params = []){
        $params['n'] = empty($params['n']) ? 1 : $params['n'];
        return $this->request([
            'url' => self::API_MIGU,
            'data' => $params
        ]);
    }

    protected function request($params = []){
        $this->client = new Client([
            'base_uri' => empty($this->options['base_uri']) ? $this->baseUri : $this->options['base_uri'],
            'timeout' => empty($this->options['timeout']) ? 0 : $this->options['timeout']
        ]);
        $url = empty($params['url']) ? '/' : $params['url'];
        $method = empty($params['method']) ? 'get' : $params['method'];
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
        //return $response->getBody()->getContents();
        return $this->dealRes(json_decode($response->getBody()->getContents(), true));
    }

    public function dealRes($res){
        $res['ori_code'] = $res['code'];
        if($res['code'] != 1){
            $this->setError($res['code']);
            $res['code'] = 0;
            $res['errmsg'] = $this->errMsg;
        }
        return $res;
    }

    public function setError($code = 200){
        $list = [
            -1 => '请求失败',
            400 => '请求错误！',
            403 => '请求被服务器拒绝！',
            405 => '客户端请求的方法被禁止！',
            408	=> '请求时间过长！',
            500	=> '服务器内部出现错误！',
            501	=> '服务器不支持请求的功能，无法完成请求！',
            503	=> '系统维护中！',
            514	=> '触发QPS限制！'
        ];
        $this->errMsg = isset($list[$code]) ? ($code . ':' .$list[$code]) : ($code.':未知错误');
    }

    public function getError(){
        return $this->errMsg;
    }

}