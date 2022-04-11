<?php
/**
 * Created by PhpStorm.
 * Script Name: Test.php
 * Create: 2022/4/2 10:46
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\home\controller;


class Test extends Base
{

    public function tinyUrl(){
        $url = "https://wx74161fcecb84d46c.wx.ckjr001.com/kpv2p/6m5oe8/?1648865497264=#/homePage/course/imgText?courseId=2935464&ckFrom=5&extId=-1&refereeId=b8ln59l";
        $url2 = urlencode($url);
        var_dump($url2);exit;
        var_dump(file_get_contents("https://ock.cn/api/short?longurl=" . $url2));
    }

    public function testUrl(){
        var_dump(input('name'));exit;
        //var_dump(file_get_contents("https://ock.cn/api/short?longurl=" . $url2));
    }
}