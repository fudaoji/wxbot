<?php
/**
 * Created by PhpStorm.
 * Script Name: Qcloud.php
 * Create: 2023/6/26 11:41
 * Description: 腾讯云cos https://cloud.tencent.com/document/product/436/12266
 * Author: fudaoji<fdj@kuryun.cn>
 */
namespace Dao\Upload\Driver;

class Qcloud
{

    /**
     * 文件目录
     * @var string
     */
    private $savePath;

    /**
     * 上传错误信息
     * @var string
     */
    private $error = '';

    private $config = array(
        'accessKey'      => '', //ak
        'secrectKey'     => '', //sk
        'domain'         => '', //domain
        'bucket'         => '', //bucket
        'timeout'        => 1500, //超时时间
    );
    private $cosClient;

    /**
     * 构造函数，用于设置上传根路径
     * @param array  $config FTP配置
     */
    public function __construct($config){
        $this->config = array_merge($this->config, $config);
        $region = $this->config['region'];
        $this->cosClient = new \Qcloud\Cos\Client([
            'region' => $region,
            'schema' => 'https', //协议头部，默认为 http
            'credentials'=> [
                'secretId'  => $this->config['accessKey'] ,
                'secretKey' => $this->config['secrectKey']
            ]
        ]);
    }

    /**
     * 检测上传根目录(七牛上传时支持自动创建目录，直接返回)
     * @param string $rootpath   根目录
     * @return boolean true-检测通过，false-检测失败
     */
    public function checkRootPath($rootpath = ''){
        return true;
    }

    /**
     * 检测上传目录(七牛上传时支持自动创建目录，直接返回)
     * @param  string $savepath 上传目录
     * @return boolean          检测结果，true-通过，false-失败
     */
    public function checkSavePath($savepath){
        $this->savePath = trim($savepath, './') . '/';
        return true;
    }

    /**
     * 创建文件夹 (七牛上传时支持自动创建目录，直接返回)
     * @param  string $savepath 目录名称
     * @return boolean          true-创建成功，false-创建失败
     */
    public function mkdir($savepath = ''){
        return true;
    }

    /**
     * 保存指定文件
     * @param array $file 保存的文件信息
     * @param boolean $replace 同名文件是否覆盖
     * @return boolean          保存状态，true-成功，false-失败
     * @throws \Exception
     */
    public function save(&$file, $replace=false){
        // 要上传的空间
        $bucket = $this->config['bucket'];

        // 上传到七牛后保存的文件名
        $key = $this->savePath . $file['savename'];

        $content = fopen($file['tmp_name'], "rb");
        try {
            $this->cosClient->putObject([
                'Bucket' => $bucket,
                'Key' => $key,
                'Body' => $content
            ]);
            $file['url'] = $this->downLink($key);
            return true;
        } catch (\Exception $e) {
            $this->error = '上传出错: ' . $e->getMessage();
            return false;
        }
    }

    /**
     * 获取最后一次上传错误信息
     * @return string 错误信息
     */
    public function getError(){
        return $this->error;
    }

    /**
     * 获取文件下载资源链接
     * @param $key
     * @return string
     * @Author: Doogie <461960962@qq.com>
     */
    public function downLink($key){
        //$key = urlencode($key);
        $key = self::_escapeQuotes($key);
        return $this->cosClient->getObjectUrlWithoutSign($this->config['bucket'], $key);
    }

    /**
     * @param $str
     * @return mixed
     * @Author: Doogie <461960962@qq.com>
     */
    static function _escapeQuotes($str){
        $find = array("\\", "\"");
        $replace = array("\\\\", "\\\"");
        return str_replace($find, $replace, $str);
    }

    /**
     * 文件直传
     * @param array $params
     * @return bool|string
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function putString($params = []){
        $key = empty($params['key']) ? uniqid() : $params['key'];
        $string = empty($params['string']) ? '' : $params['string'];
        // 要上传的空间
        $bucket = $this->config['bucket'];

        try {
            $this->cosClient->putObject([
                'Bucket' => $bucket,
                'Key' => $key,
                'Body' => $string
            ]);
            return $this->downLink($key);
        } catch (\Exception $e) {
            $this->error = '上传出错: ' . $e->getMessage();
            return false;
        }
    }

    /**
     * 删除对象
     * @param string $key
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function delete($key = ''){
        $bucket = $this->config['bucket'];
        try {
            $this->cosClient->deleteObject(array(
                'Bucket' => $bucket,
                'Key' => $key,
                //'VersionId' => 'string'
            ));
            return true;
        } catch (\Exception $e) {
            $this->error = '删除出错: ' . $e->getMessage();
            return false;
        }
    }

    /**
     * 资源列表
     * @param int $current_page
     * @param int $page_size
     * @param string $prefix
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function listFile($current_page=1, $page_size=10, $prefix=''){
        $bucket = $this->config['bucket'];
        try {
            $res = $this->cosClient->listObjects(array(
                'Bucket' => $bucket,
                'Marker' => $current_page,
                'MaxKeys' => $page_size //设置单次查询打印的最大数量，最大为1000
            ));
            return [
                'marker' => $res['Marker'],
                'items' => $res['Contents']
            ];
        } catch (\Exception $e) {
            $this->error = '删除出错: ' . $e->getMessage();
            return false;
        }
    }
}