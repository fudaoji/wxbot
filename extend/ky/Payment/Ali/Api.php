<?php
/**
 * Script name: Api.php
 * Created by PhpStorm.
 * Create: 2017-11-01 16:09
 * Description: 阿里支付API统一封装
 * Author: Jason<1589856452@qq.com>
 */
namespace ky\Payment\Ali;

use ky\Logger;
use ky\Payment\Ali\aop\request\AlipayTradePagePayRequest;
use ky\Payment\Ali\aop\request\AlipayTradeQueryRequest;
use ky\Payment\Ali\aop\request\AlipayTradeRefundRequest;
use ky\Payment\Ali\aop\request\AlipayTradeCloseRequest;
use ky\Payment\Ali\aop\request\AlipayTradeFastpayRefundQueryRequest;
use ky\Payment\Ali\aop\request\AlipayDataDataserviceBillDownloadurlQueryRequest;
use ky\Payment\Ali\aop\AopClient;

class Api
{
    /**
     * 配置参数
     * @var array
     * @author Jason<1589856452@qq.com>
     */
    private $config = [
        'app_id'                => "2017103109643518", //应用ID
        'notify_url'            => "", //异步通知地址
        'return_url'            => "", //同步跳转
        'charset'               => "UTF-8", //编码格式
        'sign_type'             => "RSA2", //签名方式
        'alipay_public_key'     => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAsZud0Re1KAETjz14V1LmjxYzkq73TVp6MWFYLKB/aGoYaILRisTgjVFmHX7hXdHy2yhmX9MK0MmvHbE9gEjnP/XVQ3uatR+dLC1guiZIXsyjxZkUZAcqYsVQbLapkZ65uD2Bp3q2ZSCMcJWXgt0/Uw+dQ1uB/CKMHLpsk4aaDSIwiRz1mMIxQ4xqSGyA9hIcvUX3AMvHvPa9lS8ZavUF6TCfBE4ERf5DGQtTDH2cK8IfKQxpWDXK+scFOaZV64eeqiWP3/IlSlOXsgtiQeTOQoo/bHmMUTdt5poOFk7bZBR9sPRzWnqG4Lc7G9pEXQxioo2G306tLApY5KruItgruwIDAQAB", //支付宝公钥
        'app_private_key'       => "MIIEpQIBAAKCAQEA4k4c71+h/OfJB5ofMEQUcy/5fD8SVkW24R1KymeqnpFXtFKmAwBoz7DftUuA3d9tZmPicbP76zN15gzDaFy8JpvxXMnf2o8vPJwHzWdMttMCe5qn3wF+iBXcKgyiO+7W9tBfhXoEQzZPTqsNlrxagbeRo1AAiXy0Z8qrgRSJwkjhuwn9EqRJDUIIIInj7WJMm+ipe+VrvOHGg7JDHLai0DafMhuQhlZAy92nXg7mgYY3my3dgizK4pJ9BOg+oHN2PgqnpuqBKt1YPxZaWxwhDtdMHri28o4U6LiXI8uwB9CQ409J0pKzXH8Ix27ChQBWSqsVMVrKB3jx0GnD3ldyYQIDAQABAoIBAQCdgz7ygX6vF+k9SFGy93/AWMDucLvjnmxWrVu0G4mPCpyf3vK7nhpVjMd4WVAdnfh4Tsd62opaP5ws/e84gWtX+Cx7p+sOJaD8L6HinqavjZjpzVBQkKqwL37pKZf13Rxz9760maPD4FY87CPuKbVZGhrdL77f8xNiNpc+pbvopCBm5RJljtlt7mcgi7ri4c+HsFeqhopc5K+p/0naB08hUsNC9p+kgBldpmTaDy6vlnJPO1UfFll8AP7XpWLPO7Lly6D1iILbray8Qrn2xhIrIDa42tzvyqCnbCISdKr+U/00NLINB3G0c/LULakxggH/ZDHHGMxSS7Op6ed3FF9BAoGBAPnDE6wdWdJBT7f28oh9YS98fqd9XiIFlzCMgVB9dX0G4G5n7YPLXGSLxGwb9cqWWazYwz7mfvSakLnfsPrES/H2DqFD0wqyQDdMOi9wcHRRgX+id7NGa7PaheKGaVGISwwS0gph70H9ZUhCOgM0bSgeeh5PPmr1GhaMa9Vnv/QZAoGBAOf1DtnhfVxujKdTXoJGqqq6BmaSLxqwY1lpbuVb1Chy3sspAQrMkOnFhLrqhnmnm6zYFN/jAeBY+bcb9zjKDVz56+zJ+i6J5XY+OqinrA6FCBIgax4/gGiqUbLpNkWKboc+TyL5/QavMa+6Wzjvf/Bec/TFfEUmBjfDIgQH7nmJAoGBAOoEzIPyJF0K8QTwD6OvxsXVol6Q6wh5sIW+wNOIGfOB4/vOyQ86FmHnS634LL8bTP7VQ2vGUKIjyQNAAvWTw4z8/gxAHpfhGKY3eDVG5zc1v161/e4uR3AHincLddb9G0Q+xisQRDvSj3m7A9xI3RZAKDAKE/PnoAAMpmUYhxTRAoGAHYSeAzQi0+Rtmc2ONV3w0hEiR2NLA0Z3HiXAqwdolE0p9QiezVUDC/zKy06VFl4x/WjyHxuwXXB1NswkReHQmYhkcZmycMGzpbx9koDj8GUVhdkCSLMkEC28f9wVv/vB3Qwc3gKaJIOe+xHoEfLIgTpD315SVipkmCwI5CJuQqECgYEA0wx3+Df0jga29RL8SQi6zuNL9zjLEX0xz8Xgck3LOUapfK7PQqLvswmSk99rRKsaTSJDuHjoDSH6dx/svYJuAtTIWksw9UgJrstQcDlaMV/xtNpHwhvrtHnDzO7ah5lT7OvUoFxkG8/U3qHm1kh2VL8bkWhdz0/4fXMjRn+pg2k=", //应用私钥
        'format'                => "json", //返回数据格式
        'api_version'           => "1.0", //接口版本号
    ];

    /**
     * 支付宝网关
     * @var array
     * @author Jason<1589856452@qq.com>
     */
    private $apiUrl = [
        'gatewayUrl' => 'https://openapi.alipay.com/gateway.do'
    ];

    /**
     * 初始化
     * @param array $config 配置数组
     * @author Jason<1589856452@qq.com>
     */
    public function __construct($config = []) {
        $config && $this->config = array_merge($this->config, $config);
        if(empty($this->config)){
            Logger::setMsgAndCode('缺少支付宝支付配置参数');
        }
    }

    /**
     * 使用 $this->name 获取配置
     * @param string $name 配置名称
     * @return mixed 配置值
     * @author Jason<1589856452@qq.com>
     */
    public function __get($name) {
        return $this->config[$name];
    }

    /**
     * 设置配置值
     * @param $name
     * @param $value
     * @author Jason<1589856452@qq.com>
     */
    public function __set($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name] = $value;
        }
    }

    /**
     * 电脑网站支付
     * alipay.trade.page.pay
     * @param string $builder 业务参数，使用build中的对象生成。
     * @param string $return_url 同步跳转地址，公网可以访问
     * @param string $notify_url 异步通知地址，公网可以访问
     * @return object $response 支付宝返回的信息
     * @author Jason<1589856452@qq.com>
     */
    public function pagePay($builder, $return_url, $notify_url) {
        //请求参数的集合
        $biz_content = $builder->getBizContent();
        $request = new AlipayTradePagePayRequest();

        $request->setNotifyUrl($notify_url);
        $request->setReturnUrl($return_url);
        $request->setBizContent($biz_content);

        //首先调用支付api
        $response = $this->aopClientRequestExecute($request, true);

        return $response;
    }

    /**
     * sdkClient
     * @param object $request 接口请求参数对象。
     * @param boolean $ispage  是否是页面接口，电脑网站支付是页面表单接口。
     * @return object $response 支付宝返回的信息
     * @author Jason<1589856452@qq.com>
     */
    public function aopClientRequestExecute($request, $ispage = false) {
        $aop = new AopClient ();

        $aop->gatewayUrl            = $this->apiUrl['gatewayUrl'];
        $aop->appId                 = $this->config['app_id'];
        $aop->rsaPrivateKey         = $this->config['app_private_key'];
        $aop->alipayrsaPublicKey    = $this->config['alipay_public_key'];
        $aop->apiVersion            = $this->config['api_version'];
        $aop->postCharset           = $this->config['charset'];
        $aop->format                = $this->config['format'];
        $aop->signType              = $this->config['sign_type'];
        $aop->debugInfo             = true; // 开启页面信息输出

        if($ispage) {
            $result = $aop->pageExecute($request, "post");
            echo $result;
        } else {
            $result = $aop->Execute($request);
        }

        //打开后，将报文写入log
        Logger::setMsgAndCode('response: ' . var_export($result,true));

        return $result;
    }

    /**
     * 统一收单线下交易查询
     * alipay.trade.query
     * @param string $builder 业务参数，使用build中的对象生成。
     * @return object $response 支付宝返回的信息
     * @author Jason<1589856451@qq.com>
     */
    public function query($builder) {
        //请求参数的集合
        $biz_content = $builder->getBizContent();

        $request = new AlipayTradeQueryRequest();
        $request->setBizContent($biz_content);

        $response = $this->aopClientRequestExecute($request);
        $response = $response->alipay_trade_query_response;

        return $response;
    }

    /**
     * 统一收单交易退款接口
     * alipay.trade.refund
     * @param string $builder 业务参数，使用build中的对象生成。
     * @return object $response 支付宝返回的信息
     * @author Jason<1589856452@qq.com>
     */
    public function refund($builder) {
        //请求参数的集合
        $biz_content = $builder->getBizContent();

        $request = new AlipayTradeRefundRequest();
        $request->setBizContent($biz_content);

        $response = $this->aopClientRequestExecute($request);
        $response = $response->alipay_trade_refund_response;

        return $response;
    }

    /**
     * 统一收单交易关闭接口
     * alipay.trade.close
     * @param string $builder 业务参数，使用build中的对象生成。
     * @return object $response 支付宝返回的信息
     * @author Jason<1589856452@qq.com>
     */
    public function close($builder) {
        //请求参数的集合
        $biz_content = $builder->getBizContent();

        $request = new AlipayTradeCloseRequest();
        $request->setBizContent ( $biz_content );

        $response = $this->aopclientRequestExecute ($request);
        $response = $response->alipay_trade_close_response;

        return $response;
    }

    /**
     * 统一收单交易退款查询
     * alipay.trade.fastpay.refund.query
     * @param string $builder 业务参数，使用build中的对象生成。
     * @return object $response 支付宝返回的信息
     * @author Jason<1589856452@qq.com>
     */
    public function refundQuery($builder) {
        //请求参数的集合
        $biz_content = $builder->getBizContent();

        $request = new AlipayTradeFastpayRefundQueryRequest();
        $request->setBizContent($biz_content);

        $response = $this->aopclientRequestExecute ($request);
        return $response;
    }

    /**
     * 查询对账单下载地址
     * alipay.data.dataservice.bill.downloadurl.query
     * @param string $builder 业务参数，使用build中的对象生成。
     * @return object $response 支付宝返回的信息
     * @author Jason<1589856452@qq.com>
     */
    public function downloadUrlQuery($builder) {
        //请求参数的集合
        $biz_content = $builder->getBizContent();

        $request = new alipaydatadataservicebilldownloadurlqueryRequest();
        $request->setBizContent ( $biz_content );

        $response = $this->aopclientRequestExecute ($request);
        $response = $response->alipay_data_dataservice_bill_downloadurl_query_response;

        return $response;
    }

    /**
     * 验签方法
     * @param string $arr 验签支付宝返回的信息，使用支付宝公钥。
     * @return boolean
     * @author Jason<1589856452@qq.com>
     */
    public function check($arr) {
        $aop = new AopClient();
        $aop->alipayrsaPublicKey = $this->config['alipay_public_key'];

        $result = $aop->rsaCheckV1($arr, $this->config['alipay_public_key'], $this->config['sign_type']);

        return $result;
    }
}