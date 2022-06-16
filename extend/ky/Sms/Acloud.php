<?php
/**
 * Created by PhpStorm.
 * Script Name: Acloud.php
 * Create: 2018/4/20 15:28
 * Description:
 * Author: Jason<dcq@kuryun.cn>
 */
namespace ky\Sms;

require_once dirname(__DIR__) . '/Sms/Acloud/vendor/autoload.php';
use \Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\SendBatchSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;
use ky\Logger;
//加载区域结点配置
Config::load();

class Acloud
{
    private $accessKeyId;
    private $accessKeySecret;
    private $client;
    private $error;

    /**
     * 初始化
     * @param string $accessKeyId
     * @param string $accessKeySecret
     * @author Jason<dcq@kuryun.cn>
     */
    public function __construct($accessKeyId = '', $accessKeySecret = '') {
        $product = "Dysmsapi";   //产品名称
        $domain = "dysmsapi.aliyuncs.com";   //产品域名
        $region = "cn-hangzhou";   //Region
        $endPointName = "cn-hangzhou";   // 服务结点
        $this->accessKeyId = $accessKeyId;
        $this->accessKeySecret = $accessKeySecret;

        //初始化acsClient,暂不支持region化
        $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
        //增加服务结点
        DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);
        //初始化AcsClient用于发起请求
        $this->client = new DefaultAcsClient($profile);
    }

    /**
     * 发送短信
     * @param string $mobile
     * @param array $content
     * @return mixed
     * @author Jason<dcq@kuryun.cn>
     */
    public function send($mobile='', $content=[]) {
        //初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();
        //必填，设置短信接收号码
        $request->setPhoneNumbers($mobile);
        //必填，设置签名名称，应严格按"签名名称"填写
        $request->setSignName($content['sign_name']);
        //必填，设置模板CODE，应严格按"模板CODE"填写
        $request->setTemplateCode($content['template_code']);
        //可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
        $request->setTemplateParam(json_encode($content['template_param'], JSON_UNESCAPED_UNICODE));
		
        //发起访问请求
        $acsResponse = $this->client->getAcsResponse($request);
		
		$rsp = json_decode( json_encode($acsResponse),true);
		if($rsp['Code'] == 'OK'){
			return true;
		}else{
			$this->setError($rsp['Code']);
			Logger::write(json_encode($rsp));
			return false;
		}
    }

    /**
     * 错误码对照表
     * @param null $code
     * @author Jason<dcq@kuryun.cn>
     */
    private function setError($code = null){
        $list = [
            'OK'                                => '请求成功',
            'isp.RAM_PERMISSION_DENY'           => 'RAM权限DENY',
            'isv.OUT_OF_SERVICE'                => '业务停机',
            'isv.PRODUCT_UN_SUBSCRIPT'          => '未开通云通信产品的阿里云客户',
            'isv.PRODUCT_UNSUBSCRIBE'           => '产品未开通',
            'isv.ACCOUNT_NOT_EXISTS'            => '账户不存在',
            'isv.ACCOUNT_ABNORMAL'              => '账户异常',
            'isv.SMS_TEMPLATE_ILLEGAL'          => '短信模板不合法',
            'isv.SMS_SIGNATURE_ILLEGAL'         => '短信签名不合法',
            'isv.INVALID_PARAMETERS'            => '参数异常',
            'isp.SYSTEM_ERROR'                  => '系统错误',
            'isv.MOBILE_NUMBER_ILLEGAL'         => '非法手机号',
            'isv.MOBILE_COUNT_OVER_LIMIT'       => '手机号码数量超过限制',
            'isv.TEMPLATE_MISSING_PARAMETERS'   => '模板缺少变量',
            'isv.BUSINESS_LIMIT_CONTROL'        => '业务限流',
            'isv.INVALID_JSON_PARAM'            => 'JSON参数不合法，只接受字符串值',
            'isv.BLACK_KEY_CONTROL_LIMIT'       => '黑名单管控',
            'isv.PARAM_LENGTH_LIMIT'            => '参数超出长度限制',
            'isv.PARAM_NOT_SUPPORT_URL'         => '不支持URL',
            'isv.AMOUNT_NOT_ENOUGH'             => '账户余额不足',
        ];
        $this->error = isset($list[$code]) ? $list[$code] : '未知错误';
    }

    /**
     * 返回错误信息
     * @return mixed
     * @author Jason<dcq@kuryun.cn>
     */
    public function getError(){
        return $this->error;
    }
}
