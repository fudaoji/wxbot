<?php
/**
 * Script name: Sms.php
 * Created by PhpStorm.
 * Create: 2016/7/14 14:37
 * Description: 手机短信
 * Author: Doogie<461960962@qq.com>
 */

namespace ky\Sms;

class Shiyuan
{
    private $account;
    private $pswd;
    private $error='';

    public function __construct($account='', $pwd='')
    {
        header("Content-Type: text/html; charset=utf-8");
        $this->account = $account;
        $this->pswd = $pwd;
    }

    /**
     * 查询账户余额
     */
    public function account()
    {
        $url = 'http://120.26.69.248/msg/QueryBalance';

        $requestUrl = $url . '?account=' . $this->account . '&pswd=' . $this->pswd;

        return file_get_contents($requestUrl);
    }

    /**
     * 发送短信
     */
    public function send($mobile, $content)
    {
        $url = 'http://send.18sms.com/msg/HttpBatchSendSM';
        $post_data['account'] = $this->account;
        $post_data['pswd']    = $this->pswd;
        $post_data['msg']     = urlencode($content); //短信内容需要用urlencode编码下
        $post_data['mobile']  = $mobile; //手机号码， 多个用英文状态下的 , 隔开
        $post_data['product'] = ''; //产品ID  不用填写
        $post_data['extno']   = '';  //扩展码      不用填写
        $post_data['needstatus'] = false; //是否需要状态报告，需要true，不需要false
        $post_data = http_build_query($post_data);//substr($o,0,-1);

        return $this->curl($url, $post_data);

    }

    public function curl($url, $post_data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
        $result = curl_exec($ch);

        $resultArr = explode('\n', $result);
        $statusArr = explode(',', $resultArr[0]);
        if ($statusArr[1] != 0) {
            $this->setError($statusArr[1]);
            return false;
        }

        return true;
    }

    /**
     * 错误信息
     * @param $code
     * @return array
     * @Author: Doogie <461960962@qq.com>
     */
    private function setError($code)
    {
        $status = [
            101	=> '无此用户',
            102	=> '密码错',
            103	=> '提交过快（提交速度超过流速限制）',
            104	=> '系统忙（因平台侧原因，暂时无法处理提交的短信）',
            105	=> '敏感短信（短信内容包含敏感词）',
            106	=> '消息长度错（>536或<=0）',
            107	=> '包含错误的手机号码',
            108	=> '手机号码个数错（群发>50000或<=0;单发>200或<=0）',
            109	=> '无发送额度（该用户可用短信数已使用完）',
            110	=> '不在发送时间内',
            111	=> '超出该账户当月发送额度限制',
            112	=> '无此产品，用户没有订购该产品',
            113	=> 'extno格式错（非数字或者长度不对）',
            115	=> '自动审核驳回',
            116	=> '签名不合法，未带签名（用户必须带签名的前提下）',
            117	=> 'IP地址认证错,请求调用的IP地址不是系统登记的IP地址',
            118	=> '用户没有相应的发送权限',
            119	=> '用户已过期',
            120	=> '内容不在白名单中'
        ];
        $this->error = isset($status[$code]) ? $status[$code] : '未知错误';
    }

    /**
     * 返回错误信息
     * @return string
     * @author: Doogie<461960962@qq.com>
     */
    public function getError(){
        return $this->error;
    }
}
