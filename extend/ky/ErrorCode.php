<?php
// +----------------------------------------------------------------------
// | [KyPHP System] Copyright (c) 2020 http://www.kuryun.com/
// +----------------------------------------------------------------------
// | [KyPHP] 并不是自由软件,你可免费使用,未经许可不能去掉KyPHP相关版权
// +----------------------------------------------------------------------
// | Author: fudaoji <fdj@kuryun.cn>
// +----------------------------------------------------------------------

namespace ky;

class ErrorCode
{
    const CatchException  = 0;    // 捕获异常
    const SuccessCode     = 1;    // 请求成功, 结果不为空
    const EmptyResult     = 2;    // 请求成功, 结果为空
    const FailedCode      = 2000; // 请求失败

    const RedirectAjax    = 911;  // session过期, 重定向ajax请求

    const ErrorParam      = 2001; // 参数格式错误
    const InvalidParam    = 2002; // 参数值非法
    const BadParam        = 2003; // 参数值非法, 服务端强制客户端提示
    const RepeatSubmit    = 2004; // 表单重复提交
    const IlleglOperation = 2005; // 非法操作
    const AuthExpired     = 2006; // 权限过期

    const RException      = 3000; // redis连接异常

    const UploadExcepion  = 3500; // 文件上传异常

    const InvalidShardKey = 4000; // shardid非法

    const QiniuException  = 4500; // 七牛异常

    const CurlError       = 9000; // curl发生错误
    const HttpError       = 9001; // http错误
    const EncodeError     = 9002; // SDK编码错误

    const ParamException   = 50000; // 参数异常
    const ClassNotExist    = 51000; // 类不存在

    const DbException      = 52000; // 数据库异常
    const CommandException = 53000; // 命令行库异常
    const WeixinException  = 54000; // 微信库异常
    const WxpayException   = 54100; // 微信支付异常
    const WxCompException  = 54200; // 微信开放平台组件异常
    const AlipayException  = 55000; // 支付宝异常

    const QyWeixinException  = 56000; // 企业微信库异常
    const InitException    = 100000; // 初始化异常

    const SMSError   = 200000; // 发送短信接口异常

    /**
     * 微信公众号官方错误
     * @param int $code
     * @param string $msg
     * @return mixed|string
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function mpError($code = 0, $msg = '未知错误'){
        $list = [
            40001 => '获取 access_token 时 AppSecret 错误，或者 access_token 无效。请比对 AppSecret 的正确性，或查看是否正在为恰当的公众号调用接口',
            40113 => '素材文件格式不合法',
            48001 => 'api功能未授权，请确认公众号已获得该接口'
        ];
        return isset($list[$code]) ? $list[$code] : $msg;
    }

}