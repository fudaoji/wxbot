<?php
namespace app\home\controller;
use think\Controller;

class Base extends Controller
{
    protected $appid;
    protected $appkey;
    protected $version;
    public function __construct(){
        $this->appid = "2112202303504964";
        $this->appkey = "3dd074414022a194939588a3547b20a6";
        $this->version = 2;
    }
}