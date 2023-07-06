<?php
/**
 * Created by PhpStorm.
 * Script Name: Client.php
 * Create: 2022/7/6 10:13
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky\OpenAI;


class Client
{
    private static $instance;
    /**
     * @var
     */
    private $ai;
    private $driver;

    public function __construct($options = [], $driver = '')
    {
        $this->driver = $driver;
        if(empty($options['base_uri'])){
            throw new \Exception("base_uri参数缺失");
        }
        $class = '\\ky\\OpenAI\\Driver\\' . ucfirst(strtolower($this->driver));
        $this->ai = new $class($options);
        if(!$this->ai){
            throw new \Exception("不存在的AI驱动：{$driver}");
        }
        return $this->ai;
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


    public function getError()
    {
        return $this->ai->getError();
    }
}