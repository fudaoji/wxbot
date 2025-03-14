<?php
/**
 * Created by PhpStorm.
 * Script Name: Client.php
 * Create: 2023/1/17 11:27
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace DACommunity;
use GuzzleHttp\Client as GuzzleHttpClient;

class Client
{
    private $token = '';
    private $project = 'wxbot';
    static public $baseUrl = 'https://daoadmin.kuryun.com';
    /**
     * @var GuzzleHttpClient
     */
    static private $client = null;
    private static $instance = null;

    const API_AUTH_REGISTER = 'auth/registerPost';
    const API_AUTH_LOGIN = 'auth/loginPost';
    const API_APP_LIST     = 'app/listPost';
    const API_APP_GET = 'app/getPost';
    const API_APP_GET_BY_NAME = 'app/getByNamePost';
    const API_APP_GETCATES = 'app/getCatesPost';
    const API_APP_DOWNLOAD = 'app/downloadPost';
    const API_APP_LISTUPGRADE = 'app/listUpgradePost';
    const API_APP_GETUPGRADE =  'app/getUpgradePost';
    const API_USER_GET= 'user/getPost';
    const API_FRAMEWORK_VERSIONS = 'wxbot/getVersions';
    const API_FRAMEWORK_GETUPGRADE = 'framework/getUpgradePackage';
    const API_APP_NOTICE_LIST     = 'notice/listNotice';

    public function __construct($options = [])
    {
        if(is_null(self::$client)){
            self::setClient();
        }
        if(!empty($options['token'])){
            $this->token = $options['token'];
        }
    }

    /**
     * 版本列表
     * @param array $params
     * @return array|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function versionList($params = []){
        return $this->doRequest(['uri' => self::API_FRAMEWORK_VERSIONS, 'data' => $params]);
    }

    /**
     * 社区公告
     * @param array $params
     * @return array|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function noticeList($params = []){
        return $this->doRequest(['uri' => self::API_APP_NOTICE_LIST, 'data' => $params]);
    }

    /**
     * 获取应用升级包
     * @param array $params
     * @return array|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function appUpgradeGet($params = []){
        return $this->doRequest(['uri' => self::API_APP_GETUPGRADE, 'data' => $params, 'token' => $params['token']]);
    }

    /**
     * 可升级的应用列表
     * @param array $params
     * @return array|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function appUpgradeList($params = []){
        return $this->doRequest(['uri' => self::API_APP_LISTUPGRADE, 'data' => $params, 'token' => $params['token']]);
    }

    /**
     * 登录账号信息
     * @param array $params
     * @return array|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function userGet($params = []){
        return $this->doRequest(['uri' => self::API_USER_GET, 'token' => $params['token']]);
    }

    /**
     * 应用下载
     * @param array $params
     * @return array|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function appDownload($params = []){
        return $this->doRequest(['uri' => self::API_APP_DOWNLOAD, 'data' => $params, 'token' => $params['token']]);
    }

    /**
     * 根据name获取应用详情
     * @param array $params
     * @return array|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function appGetByName($params = []){
        return $this->doRequest(['uri' => self::API_APP_GET_BY_NAME, 'data' => $params]);
    }

    /**
     * 应用详情
     * @param array $params
     * @return array|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function appGet($params = []){
        return $this->doRequest(['uri' => self::API_APP_GET, 'data' => $params, 'token' => $params['token']]);
    }

    /**
     * 应用列表
     * @param array $params
     * @return array|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function appList($params = []){
        return $this->doRequest(['uri' => self::API_APP_LIST, 'data' => $params]);
    }

    /**
     * 应用类目
     * @return array|mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function appCateList(){
        return $this->doRequest(['uri' => self::API_APP_GETCATES]);
    }

    public static function instance($options = []){
        if(is_null(self::$instance)){
            self::$instance = new self($options);
        }
        return self::$instance;
    }

    /**
     * 设置请求客户端
     * Author: fudaoji<fdj@kuryun.cn>
     */
    private static function setClient(){
        self::$client = new \GuzzleHttp\Client([
            'base_uri' => self::$baseUrl . '/api/',
            'verify' => false
        ]);
    }

    protected function doRequest($params = []){
        $data = empty($params['data']) ? [] : $params['data'];
        !empty($params['token']) && $this->token = $params['token'];
        $method = 'post';
        $extra = [
            'http_errors' => false,
            'json' => $data,
            'headers' => ['token' => $this->token, 'project' => $this->project]
        ];
        try {
            $res = self::$client->request($method, $params['uri'], $extra);
            if($res->getStatusCode() == 200){
                return json_decode($res->getBody()->getContents(), true);
            }else{
                return ['code' => 0, 'msg' => "请求失败,statusCode:" . $res->getStatusCode()];
            }
        }catch (\Exception $e){
            var_dump($e->getMessage());
        }
        return ['code' => 0, 'msg' => "请求失败"];
    }
}