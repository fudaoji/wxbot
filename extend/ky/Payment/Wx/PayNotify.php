<?php

/**
 * 回调基础类
 */
namespace ky\Payment\Wx;
use ky\Payment\Wx\Data\PayNotifyReply;

class PayNotify extends PayNotifyReply
{
    /**
     *
     * 回复通知内容
     * @param bool $result 成功或失败
     * @param bool $needSign 是否需要签名输出
     * @return  string $xml
     */
    final public function replyNotifyData($result, $needSign = true) {
        //如果需要签名
        if($needSign == true) {
            $this->SetSign();
        }
        if($result == false){
            $this->SetReturn_code("FAIL");
            $this->SetReturn_msg("Error Happen");
        } else {
            //该分支在成功回调到NotifyCallBack方法，处理完成之后流程
            $this->SetReturn_code("SUCCESS");
            $this->SetReturn_msg("OK");
        }
        return $this->ToXml();
    }
}