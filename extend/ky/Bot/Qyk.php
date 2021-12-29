<?php
/**
 * Created by PhpStorm.
 * Script Name: Qyk.php
 * Create: 2021/12/29 17:34
 * Description: 青云客机器人
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky\Bot;


class Qyk extends Base
{
    //http://api.qingyunke.com/api.php?key=free&appid=0&msg=%E4%BD%A0%E5%A5%BD
    protected $baseUri = 'http://api.qingyunke.com/';

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
        $url = '/api.php?key=free&appid=0&msg=%E4%BD%A0%E5%A5%BD';
        return $this->request([
            'url' => $url . "&msg=" . $params['content'],
            'method' => 'get'
        ]);
    }
}