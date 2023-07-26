# dao-upload

用于PHP文件上传（PHP 7.1+），支持本地、七牛、阿里云(oss)、腾讯云(cos)等上传方式。

## 安装
~~~
composer require fudaoji/php-upload
~~~

## 用法：
~~~php
use Dao\Upload\Upload;
//上传本地
$config = [
        'mimes'         =>  [], //允许上传的文件MiMe类型
        'maxSize'       =>  0, //上传的文件大小限制 (0-不做限制)
        'exts'          =>  [], //允许上传的文件后缀
        'autoSub'       =>  true, //自动子目录保存文件
        'subName'       =>  ['date', 'Y-m-d'], //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'rootPath'      =>  './public/uploads/', //保存根路径
        'savePath'      =>  '', //保存路径
        'saveName'      =>  ['uniqid', ''], //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
        'replace'       =>  false, //存在同名是否覆盖
    ];

$Upload = new Uploader($config, 'local', []);
$info   = $Upload->upload($files, '文件名前缀，选填');  //false || [{}]

//上传七牛
$config = [
        'mimes'         =>  [], //允许上传的文件MiMe类型
        'maxSize'       =>  0, //上传的文件大小限制 (0-不做限制)
        'exts'          =>  [], //允许上传的文件后缀
        'autoSub'       =>  true, //自动子目录保存文件
        'subName'       =>  ['date', 'Y-m-d'], //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'rootPath'      =>  '', //保存根路径
        'savePath'      =>  '', //保存路径
        'saveName'      =>  ['uniqid', ''], //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
        'replace'       =>  false, //存在同名是否覆盖
    ];
//七牛的配置
$driver_config = [
    'accessKey' => '',
    'secrectKey' => '',
    'bucket' => '',
    'domain' => '',
];
$Upload = new Uploader($config, 'qiniu', $driver_config);
$info   = $Upload->upload($files, '文件名前缀，选填');  //false || [{}]

//阿里云的配置
$driver_config = [
    'accessKey' => '', //对应oss的AccessKeyId
    'secrectKey' => '', //对应oss的AccessKeySecret
    'bucket' => '',
    'domain' => '',
];
$Upload = new Uploader($config, 'aliyun', $driver_config);
$info   = $Upload->upload($files, '文件名前缀，选填');  //false || [{}]

//腾讯云的配置
$driver_config = [
    'accessKey' => '', //对应cos的secretId 
    'secrectKey' => '', //对应cos的secretKey
    'region' => '', //区域
    'bucket' => '',
    'domain' => '',
];
$Upload = new Uploader($config, 'qcloud', $driver_config);
$info   = $Upload->upload($files, '文件名前缀，选填');  //false || [{}]
~~~
