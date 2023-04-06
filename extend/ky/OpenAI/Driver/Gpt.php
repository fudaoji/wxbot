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

class Gpt extends Base
{
    //https://chat.forchange.cn/
    protected $baseUri = 'https://api.forchange.cn/';
    protected $errMsg = '';

    public function __construct($options = [])
    {
        parent::__construct($options);
    }

    /**
     * 智能聊天
     * @param $params
     * @return bool|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function smart($params){
        return  $this->doRequest($params, '/');
    }

    private function doRequest($params = [], $api = ''){
        $params['prompt'] = "Human:{$params['msg']}\nAI:";
        //$params['tokensLength'] = mb_strlen($params['prompt']);
        return $this->request([
            'url' => $api,
            'data' => $params
        ]);
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
        $res['ori_res'] = $params;
        if(!empty($params['choices'])){
            $res['code'] = 1;
            $res['answer_type'] = self::ANSWER_TEXT;
            $text = isset($params['choices'][0]['text']) ? $params['choices'][0]['text'] : '';
            $res['answer'] = str_replace('<br/>',"\n", trim($text, "<br/>"));
        }else{
            $res['code'] = 0;
            $res['errmsg'] = $params['error'];
        }
        return $res;
    }
}