<?php
/**
 * Script name: Zhutong.php
 * Created by PhpStorm.
 * Create: 2016/7/14 14:37
 * Description: 助通科技短信
 * Author: Doogie<461960962@qq.com>
 */

namespace ky\Sms;

class Zhutong {
    public $data;	//发送数据
    public $timeout = 30; //超时
    private $apiUrl;	//发送地址
    private $username;	//用户名
    private $password;	//密码
    private $error;

    function __construct($username, $password) {
        $this->apiUrl 	= 'http://hy.mix2.zthysms.com/sendSms.do';
        $this->username = $username;
        $this->password = $password;
    }

    private function httpGet() {
        $url = $this->apiUrl . '?' . http_build_query($this->data);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = curl_exec($curl);
        if (curl_errno($curl)) {
            echo 'Error GET '.curl_error($curl);
        }
        curl_close($curl);
        return $res;
    }

    private function httpPost(){ // 模拟提交数据函数
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $this->apiUrl); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_POST, true); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS,  http_build_query($this->data)); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, false); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // 获取的信息以文件流的形式返回
        $result = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            echo 'Error POST'.curl_error($curl);
        }
        curl_close($curl); // 关键CURL会话
        return $result; // 返回数据
    }

    /**
     * @param $type|提交类型 POST/GET
     * @param $isTranscoding|是否需要转 $isTranscoding 是否需要转utf-8 默认 false
     * @return mixed
     */
    private function sendSMS($type, $isTranscoding = false) {
        $this->data['content'] 	= $isTranscoding === true ? mb_convert_encoding($this->data['content'], "UTF-8") : $this->data['content'];
        $this->data['username'] = $this->username;
        $this->data['tkey'] 	= date('YmdHis');
        $this->data['password'] = md5(md5($this->password).$this->data['tkey']);
        return  $type == "POST" ? $this->httpPost() : $this->httpGet();
    }

    /**
     * 发送短信接口
     * @param string $mobile
     * @param string $content
     * @return bool
     * @author: Doogie<461960962@qq.com>
     */
    public function send($mobile='', $content=''){
        $mobile = trim($mobile, ',');
        if(strpos($mobile, ',') !== false){
            $this->apiUrl = 'http://hy.mix2.zthysms.com/sendSmsBatch.do';
        }
        $data = array(
            'content' 	=> $content,//短信内容
            'mobile' 	=> $mobile,//手机号码
            'xh'		=> ''//小号
        );
        $this->data = $data;//初始化数据包
        $res = explode(',', $this->sendSMS('POST'));//GET or POST
        if((int)$res[0] !== 1){
            $this->setError($res[0]);
            return false;
        }
        return true;
    }

    private function setError($code = null){
        $list = [
            -1 => '发送失败',
            1 => '发送成功',
            2 => '用户名为空',
            3 => '用户名错误',
            4 => '密码为空',
            5 => '密码错误',
            6 => '当前时间tkey为空',
            7 => 'tkey 当前时间错误',
            8 => '用户类型错误',
            9 => '鉴权错误',
            10 => 'IP黑名单',
            11 => '产品错误',
            12 => '产品禁用',
            13 => '手机号码错误',
            15 => '签名违规',
            16 => '签名屏蔽',
            17 => '代表签名分配扩展失败',
            18 => '短信内容不能为空',
            19 => '短信内容最大1000个字',
            20 => '预付费用户条数不足',
            21 => '发送内容存在黑词',
            22 => '通道错误',
            28 => '签名最长15个字',
            29 => '小号错误',
            98 => '异常',
            99 => 'DES解密异常'
        ];
        $this->error = isset($list[$code]) ? $list[$code] : '未知错误';
    }

    /**
     * 返回错误信息
     * @return mixed
     * @author: Doogie<461960962@qq.com>
     */
    public function getError(){
        return $this->error;
    }

}
