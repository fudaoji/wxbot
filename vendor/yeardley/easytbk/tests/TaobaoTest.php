<?php


require '../vendor/autoload.php';


$client = \YearDley\EasyTBK\Factory::taobao([
    'app_key' => 'TAOBAO_APP_KEY',
    'app_secret' => 'TAOBAO_APP_SECRET',
    'format' => 'json'
]);
$req = new \YearDley\EasyTBK\TaoBao\Request\TbkDgOptimusMaterialRequest();
$req->setPageSize("20");
$req->setAdzoneId("123");
$req->setPageNo("1");
$req->setMaterialId("123");
$req->setDeviceValue("xxx");
$req->setDeviceEncrypt("MD5");
$req->setDeviceType("IMEI");
$req->setContentId("323");
$req->setContentSource("xxx");
$req->setItemId("33243");
$req->setFavoritesId("123445");
$response =  $client->execute($req);
var_dump($response);