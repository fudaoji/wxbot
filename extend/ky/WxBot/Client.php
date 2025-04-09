<?php
/**
 * Created by PhpStorm.
 * Script Name: Client.php
 * Create: 2022/7/6 10:13
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky\WxBot;

use ky\WxBot\Driver\Cat;
use ky\WxBot\Driver\My;
use ky\WxBot\Driver\Mycom;
use ky\WxBot\Driver\Qianxun;
use ky\WxBot\Driver\Vlw;
use ky\WxBot\Driver\Webgo;
use ky\WxBot\Driver\Wxwork;

class Client
{
    private static $instance;
    /**
     * @var $bot Vlw|Cat|Wxwork|My|Mycom|Webgo|Qianxun
     */
    private $bot;
    private $driver;

    public function __construct($options = [], $driver = '')
    {
        $this->driver = $driver;
        if(empty($options['base_uri'])){
            throw new \Exception("base_uri参数缺失");
        }
        $class = '\\ky\\WxBot\\Driver\\' . ucfirst(strtolower($this->driver));

        $this->bot = new $class($options);
        if(!$this->bot){
            throw new \Exception("不存在的机器人驱动：{$driver}");
        }
        //return $this->bot;
    }

    /**
     * 单例对象
     * @param array $options
     * @param string $driver
     * @return Client
     * @throws \Exception
     * @author: Doogie<461960962@qq.com>
     */
    public static function getInstance($options = [], $driver = '') {
        if (empty(self::$instance)) {
            self::$instance = new self($options, $driver);
        }
        return self::$instance;
    }

    /**
     *
     * @return Cat|Vlw|Wxwork
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getBot(){
        return $this->bot;
    }

    public function getError()
    {
        return $this->bot->getError();
    }
}