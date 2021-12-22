<?php
/**
 * Created by PhpStorm.
 * Script Name: Sms.php
 * Create: 16/10/12 下午7:07
 * Description: 移动短信接口
 * Author: Doogie<461960962@qq.com>
 * 例子:
 * //电话号码和code值可变,其他不变
 *  $sms = new Sms();
 *  $result = $sms->send('15659827559', ['code' => 3216],'389903');
 *  if($result['stat']=='100') {
 *      echo '发送成功';
 *  } else {
 *      echo '发送失败:'.$result['stat'].'('.$result['message'].')';
 *  }
 */
namespace  ky\Sms;
class Yunxin
{
    /**
     * SMSAPI请求地址
     */
    const API_URL = 'http://api.sms.cn/sms/';

    /**
     * 接口账号
     *
     * @var string
     */
    protected $uid;

    /**
     * 接口密码
     *
     * @var string
     * @link http://sms.sms.cn/ 请到此处（短信设置->接口密码）获取
     */
    protected $pwd;

    /**
     * sms api请求地址
     * @var string
     */
    protected $apiURL;


    /**
     * 短信发送请求参数
     * @var string
     */
    protected $smsParams;

    /**
     * 接口返回信息
     * @var string
     */
    protected $resultMsg;

    /**
     * 接口返回信息格式
     * @var string
     */
    protected $format;
    private $error = '';

    /**
     * 构造方法
     *
     * @param string $uid 接口账号
     * @param string $pwd 接口密码
     */
    public function __construct($uid = '', $pwd = '')
    {
        //用户和密码可直接写在类里
        $def_uid = 'mgyun';
        $def_pwd = 'sms@mgy';
        $this->uid	= $uid ?: $def_uid;
        $this->pwd	= $pwd ?: $def_pwd;
        $this->apiURL = self::API_URL;
        $this->format = 'json';
    }
    /**
     * SMS公共参数
     * @return array
     */
    protected function publicParams()
    {
        return array(
            'uid'		=> $this->uid,
            'pwd'		=> md5($this->pwd.$this->uid),
            'format'	=> $this->format,
        );
    }

    /**
     * 发送验证码短信
     * @param string $mobile
     * @param string $content
     * @return mixed
     * @author: Doogie<461960962@qq.com>
     */
    public function send($mobile='', $content=''){
        $res = $this->sendAll($mobile, $content);
        if($res['stat'] == 100){
            return true;
        }else{
            $this->setError($res['stat']);
            return false;
        }
    }

    /**
     * 设置错误信息
     * @param $err_no
     * @return array
     * @Author: Doogie <461960962@qq.com>
     */
    private function setError($err_no = 0){
        $list = [
            101 => '验证失败',
            102 => '短信不足',
            103 => '操作失败',
            104 => '非法字符',
            105 => '内容过多',
            106 => '号码过多',
            107 => '频率过快',
            108 => '号码内容空',
            109 => '账号冻结',
            112	=> '号码错误',
            116 => '禁止接口发送',
            117 => '绑定IP不正确',
            161 => '未添加短信模板',
            162 => '模板格式不正确',
            163 => '模板ID不正确',
            164 => '全文模板不匹配'
        ];
        $this->error = isset($list[$err_no]) ? $list[$err_no] : '';
    }

    /**
     * 返回错误信息
     * @return string
     * @author: Doogie<461960962@qq.com>
     */
    public function getError(){
        return $this->error;
    }

    /**
     * 发送变量模板短信
     *
     * @param string $mobile 手机号码
     * @param string $contentParam 短信内容参数
     * @param string $template 短信模板ID
     * @return array
     */
    public function sendTemplate($mobile, $contentParam,$template='') {
        //短信发送参数
        $this->smsParams = array(
            'ac'		=> 'send',
            'mobile'	=> $mobile,
            'content'	=> $this->array_to_json($contentParam),
            'template'	=> $template
        );
        $this->resultMsg = $this->request();
        return $this->json_to_array($this->resultMsg, true);
    }

    /**
     * 发送全文模板短信
     *
     * @param string $mobile 手机号码
     * @param string $content 短信内容
     * @return array
     */
    public function sendAll($mobile, $content) {
        //短信发送参数
        $this->smsParams = array(
            'ac'		=> 'send',
            'mobile'	=> $mobile,
            'content'	=> $content,
        );
        $this->resultMsg = $this->request();

        return $this->json_to_array($this->resultMsg, true);
    }

    /**
     * 取剩余短信条数
     *
     * @return array
     */
    public function getNumber() {
        //参数
        $this->smsParams = array(
            'ac'		=> 'number',
        );
        $this->resultMsg = $this->request();
        return $this->json_to_array($this->resultMsg, true);
    }


    /**
     * 获取发送状态
     *
     * @return array
     */
    public function getStatus() {
        //参数
        $this->smsParams = array(
            'ac'		=> 'status',
        );
        $this->resultMsg = $this->request();
        return $this->json_to_array($this->resultMsg, true);
    }
    /**
     * 接收上行短信（回复）
     *
     * @return array
     */
    public function getReply() {
        //参数
        $this->smsParams = array(
            'ac'		=> 'reply',
        );
        $this->resultMsg = $this->request();
        return $this->json_to_array($this->resultMsg, true);
    }
    /**
     * 取已发送总条数
     *
     * @return array
     */
    public function getSendTotal() {
        //参数
        $this->smsParams = array(
            'ac'		=> 'number',
            'cmd'		=> 'send',
        );
        $this->resultMsg = $this->request();
        return $this->json_to_array($this->resultMsg, true);
    }

    /**
     * 取发送记录
     *
     * @return array
     */
    public function getQuery() {
        //参数
        $this->smsParams = array(
            'ac'		=> 'query',
        );
        $this->resultMsg = $this->request();
        return $this->json_to_array($this->resultMsg, true);
    }

    /**
     * 发送HTTP请求
     * @return string
     */
    private function request()
    {
        $params = array_merge($this->publicParams(),$this->smsParams);
        if( function_exists('curl_init') )
        {
            return $this->curl_request($this->apiURL,$params);
        }
        else
        {
            return $this->file_get_request($this->apiURL,$params);
        }
    }
    /**
     * 通过CURL发送HTTP请求
     * @param string $url		 //请求URL
     * @param array $postFields //请求参数
     * @return string
     */
    private function curl_request($url,$postFields){
        $postFields = http_build_query($postFields);
        //echo $url.'?'.$postFields;
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $postFields );
        $result = curl_exec ( $ch );
        curl_close ( $ch );
        return $result;
    }
    /**
     * 通过file_get_contents发送HTTP请求
     * @param string $url  //请求URL
     * @param array $postFields //请求参数
     * @return string
     */
    private function file_get_request($url,$postFields)
    {
        $post='';
        while (list($k,$v) = each($postFields))
        {
            $post .= rawurlencode($k)."=".rawurlencode($v)."&";	//转URL标准码
        }
        return file_get_contents($url.'?'.$post);
    }
    /**
     * 获取当前HTTP请返回信息
     * @return string
     */
    public function getResult()
    {
        $this->resultMsg;
    }
    /**
     * 获取随机位数数字
     * @param  integer $len 长度
     * @return string
     */
    public function randNumber($len = 6)
    {
        $chars = str_repeat('0123456789', 10);
        $chars = str_shuffle($chars);
        $str   = substr($chars, 0, $len);
        return $str;
    }

    //把数组转json字符串
    function array_to_json($p)
    {
        return urldecode(json_encode($this->json_urlencode($p)));
    }
    //url转码
    function json_urlencode($p)
    {
        if( is_array($p) )
        {
            foreach( $p as $key => $value )$p[$key] = $this->json_urlencode($value);
        }
        else
        {
            $p = urlencode($p);
        }
        return $p;
    }

    //把json字符串转数组
    function json_to_array($p)
    {
        if( mb_detect_encoding($p,array('ASCII','UTF-8','GB2312','GBK')) != 'UTF-8' )
        {
            $p = iconv('GBK','UTF-8',$p);
        }
        return json_decode($p, true);
    }
}
