<?php
/**
 * Created by PhpStorm.
 * Script Name: Qyk.php
 * Create: 2021/12/29 17:34
 * Description: 青云客聊天机器人
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky;

use GuzzleHttp\Client;

class Qyk
{
    //http://api.qingyunke.com/api.php?key=free&appid=0&msg=%E4%BD%A0%E5%A5%BD
    protected $baseUri = 'http://api.qingyunke.com/';
    private $client;
    private $options = [];
    protected $errMsg = '';
    protected $appKey = '';

    public function __construct($options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * 智能聊天
     * @param $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function smart($params){
        $url = '/api.php?key=free&appid=0&msg=%E4%BD%A0%E5%A5%BD';
        return $this->request([
            'url' => $url . "&msg=" . $params['content'],
            'method' => 'get'
        ]);
    }

    protected function request($params = []){
        $this->client = new Client([
            'base_uri' => empty($this->options['base_uri']) ? $this->baseUri : $this->options['base_uri'],
            'timeout' => empty($this->options['timeout']) ? 15 : $this->options['timeout']
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
        //return $response->getBody()->getContents();
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

    public function dealRes($params){
        $res['ori_res'] = $params;
        if($res['result'] == 0){
            $res['code'] = 1;
            $res['content'] = $params['content'];
        }else{
            $res['code'] = 0;
            $res['errmsg'] = $this->getError();
        }
        return $res;
    }
}