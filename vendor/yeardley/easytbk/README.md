# 介绍

本项目由 <https://github.com/niugengyun/easytbk> 提供源码，稍微改动以便支持laravel之外的其他框架

淘宝联盟、京东联盟、多多进宝、唯品客、苏宁推客SDK封装

# 安装
1、安装扩展包

```bash
composer require yeardley/easytbk
```


# 初始化SDK
每个平台SDK的具体调用方法参考各平台的文档

1、淘宝SDK初始化

```php
<?php
use YearDley\EasyTBK\Factory;
use YearDley\EasyTBK\TaoBao\Request\TbkItemInfoGetRequest;

$client = Factory::taobao([
    'app_key' => 'TAOBAO_APP_KEY',
    'app_secret' => 'TAOBAO_APP_SECRET',
    'format' => 'json'
]);
$req = new TbkItemInfoGetRequest;
$req->setNumIids('$numIids');
return $client->execute ($req);
```

2、京东SDK初始化
```php
<?php
use YearDley\EasyTBK\Factory;
use YearDley\EasyTBK\JingDong\Request\JdUnionGoodsPromotiongoodsinfoQueryRequest;

$jd = Factory::jingdong([
    'app_key' => 'JD_APP_KEY',
    'app_secret' => 'JD_APP_SECRET',
    'format' => 'json',
]);
$req = new JdUnionGoodsPromotiongoodsinfoQueryRequest();
$req->setSkuIds('$itemid');
return $jd->execute($req);
```

3、拼多多SDK初始化
```php
<?php
use YearDley\EasyTBK\Factory;
use YearDley\EasyTBK\PinDuoDuo\Request\DdkGoodsDetailRequest;

$pdd = Factory::pinduoduo([
    'client_id' => 'PDD_CLIENT_ID',
    'client_secret' => 'PDD_CLIENT_SECRET',
    'format' => 'json',
]);
$req = new DdkGoodsDetailRequest();
$req->setGoodsIdList('[$itemid]');
return  $pdd->execute($req);
```

4、唯品会SDK初始化
```php
<?php
use YearDley\EasyTBK\Factory;
use YearDley\EasyTBK\Vip\Request\PidGenRequest;
use YearDley\EasyTBK\Vip\Request\UnionPidServiceClient;

// 唯品会官方的sdk写的比较垃圾，用法和其他平台稍微不一样
$service= UnionPidServiceClient::getService();
Factory::vip([
    'app_key' => 'VIP_APP_KEY',
    'app_secret' => 'VIP_APP_SECRET',
    'access_token' => 'VIP_APP_ACCESS_TOKEN',
    'format' => 'json',
]);
$pidGenRequest1 = new PidGenRequest();
$pidNameList2 = array();
$pidNameList2[0] = "value";
$pidGenRequest1->pidNameList = $pidNameList2;
$pidGenRequest1->requestId = "requestId";
print_r($service->genPidWithOauth($pidGenRequest1));
```

5、苏宁连联盟SDK初始化
```php
<?php
use YearDley\EasyTBK\Factory;
use YearDley\EasyTBK\SuNing\Request\Netalliance\CouponproductQueryRequest;

$c = Factory::suning([
    'app_key' => 'SUNING_APP_KEY',
    'app_secret' => 'SUNING_APP_SECRET',
    'format' => 'json',
]);
$req = new CouponproductQueryRequest();
$req->setPageNo("1");
$req->setPageSize("10");
$req->setPositionId("12");
$resp = $c->execute($req);
print_r($resp);
```
