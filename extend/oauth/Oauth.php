<?php
/**
 * 彩虹聚合登录SDK
 * 聚合登录请求类
 * 1.0
**/

namespace oauth;

class Oauth
{
	private $apiurl;
	private $appid;
	private $appkey;
	private $callback;

	const SESSION_STATE = 'Oauth_state';
    private $inputs;
    /**
     * @var string
     */
    private $state;

    function __construct($config){
		$this->apiurl = $config['apiurl'].'connect.php';
		$this->appid = $config['appid'];
		$this->appkey = $config['appkey'];
		$this->callback = $config['callback'];
		$this->inputs = input();
	}

	//获取登录跳转url
	public function login($type, $extra = []){

		//-------生成唯一随机串防CSRF攻击
		$state = $extra['state'] ?? (uniqid(rand(), TRUE));
		$this->setState($state);

		//-------构造请求参数列表
		$keysArr = array(
			"act" => "login",
			"appid" => $this->appid,
			"appkey" => $this->appkey,
			"type" => $type,
			"redirect_uri" => $this->callback,
			"state" => $state
		);
		$login_url = $this->apiurl.'?'.http_build_query($keysArr);
		$response = $this->get_curl($login_url);
		$arr = json_decode($response,true);
		return $arr;
	}

	//登录成功返回网站
	public function callback(){
		//-------请求参数列表
		$keysArr = array(
			"act" => "callback",
			"appid" => $this->appid,
			"appkey" => $this->appkey,
			"code" => $this->inputs['code']
		);

		//------构造请求access_token的url
		$token_url = $this->apiurl.'?'.http_build_query($keysArr);
		$response = $this->get_curl($token_url);

		$arr = json_decode($response,true);
		return $arr;
	}

	//查询用户信息
	public function query($type, $social_uid){
		//-------请求参数列表
		$keysArr = array(
			"act" => "query",
			"appid" => $this->appid,
			"appkey" => $this->appkey,
			"type" => $type,
			"social_uid" => $social_uid
		);

		//------构造请求access_token的url
		$token_url = $this->apiurl.'?'.http_build_query($keysArr);
		$response = $this->get_curl($token_url);

		$arr = json_decode($response,true);
		return $arr;
	}

	private function get_curl($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36");
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$ret = curl_exec($ch);
		curl_close($ch);
		return $ret;
	}

    public function setState(string $state)
    {
        session(self::SESSION_STATE, $state);
        $this->state = $state;
    }

    public function getState()
    {
        return session(self::SESSION_STATE);
    }
}
