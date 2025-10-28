# dao-sms

聚合短信发送类库，集合了腾讯云、移动、助通科技、示远科技等。

## 安装
~~~
composer require fudaoji/php-sms
~~~

## 用法：
~~~php
use Dao\Sms\Sms;

$content = '验证码：2323，打死也不能告诉别人。【酷云】';
$mobile = '13511111111';  //单发
//$mobile = ['13511111111', '13422222222']; //群发1
//$mobile = '13511111111,13422222222'; //群发2

$sms = new Sms('账号', '密码', 'shiyuan');   //使用示远短信
$sms = new Sms('账号', '密码', 'zhutong');   //使用助通短信
$sms = new Sms('账号', '密码', 'yunxin');   //使用中国移动短信，记得要先在对应平台设置短信模版
$sms = new Sms('账号', '密码', 'qcloud');   //使用腾讯云,记得要先在对应平台设置短信模版
$res = $sms->send($mobile, $content);
if($res !== true){
    var_dump($sms->getError());  //错误信息
}
~~~
