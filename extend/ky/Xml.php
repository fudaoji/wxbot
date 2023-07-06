<?php
/**
 * Created by PhpStorm.
 * Script Name: Xml.php
 * Create: 2023/4/19 7:41
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky;


abstract class Xml
{
    protected $object;

    function __construct($xml = '')
    {
        $this->object = simplexml_load_string($xml);
    }

    abstract function decodeObject();
}