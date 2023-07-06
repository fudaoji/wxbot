<?php
/**
 * Created by PhpStorm.
 * Script Name: Qyk.php
 * Create: 2021/12/29 17:34
 * Description: 青云客聊天机器人
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky\OpenAI\Driver;

use ky\Logger;
use ky\OpenAI\Base;

class AIEdu extends Base
{
    const API_SMART = '/api/v1/text?key=';
    const API_CHECK_KEY = '/fc/verify-key?key=';
    //https://chat.forchange.cn/
    protected $baseUri = 'https://api.aigcfun.com';
    protected $errMsg = '';
    protected $appKey = '';
    protected $model = 'gpt-3.5-turbo';
    private $method = 'post';

    public function __construct($options = [])
    {
        parent::__construct($options);
        !empty($options['key']) && $this->appKey = $options['key'];
    }

    /**
     * 智能聊天
     * @param $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function smart($params){
        $message = [
            ['role' => 'system', 'content' => "你是一个智能助手assistant"],
            ['role' => 'system', 'content' => "今天是" . date("Y-m-d") . "，现在时间是" . date("H:i").'。有人问你时，按照我给你的时间进行测算。'],
        ];
        if(! empty($params['content_rule'])){
            array_push($message, ['role' => 'system', 'content' => $params['content_rule']]);
        }
        if(! empty($params['background'])){
            array_push($message, ['role' => 'system', 'content' => $params['background']]);
        }
        if(! empty($params['context'])){
            $message = array_merge_recursive($message, $params['context']);
        }

        array_push($message, ['role' => 'user', 'content' => $params['msg']]);
        //$length = mb_strlen($params['msg']);
        //Logger::error($message);
        $data = [
            'messages' => $message,
            'model' => $this->model,
            //'tokensLength' => $length
        ];
        $res = $this->doRequest($data, self::API_SMART);
        if($res['code']){
            $res['answer_type'] = self::ANSWER_TEXT;
            $text = isset($res['choices'][0]['text']) ? $res['choices'][0]['text'] : '';
            $res['answer'] = str_replace('<br/>',"\n", trim($text, "<br/>"));
        }
        return  $res;
    }

    private function doRequest($params = [], $api = ''){
        $api .= $this->appKey;
        $options = [
            'url' => $api,
            'method' => $this->method
        ];
        !empty($params) && $options['data'] = $params;
        return $this->request($options);
    }

    public function errors($code = 200){
        $list = [
            401 => '获取token失败',
            404 => '接口路径与请求方式错误',
            429 => '接口请求频率超过限制',
            500 => '服务端错误'
        ];
        $this->errMsg = isset($list[$code]) ? ($code . ':' .$list[$code]) : ($code.':未知错误');
    }

    public function dealRes($params){
        $res = $params;
        if(empty($params['errCode'])){
            $res['code'] = 1;
        }else{
            $res['code'] = 0;
            $res['errmsg'] = $params['choices'][0]['text'];
        }
        return $res;
    }

    /**
     * return:
     * [
        "errCode" => 0
        "msg" => "OK"
        "data" => array:6 [
            "state" => true
            "type" => "public"
            "total" => 9999
            "remain" => 9947
            "limitType" => "totalLimit"
            "expTime" => 1682642361
        ]
        "code" => 1
        ]

     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function checkKey(){
        $this->method = 'get';
        return  $this->doRequest([], self::API_CHECK_KEY);
    }
}