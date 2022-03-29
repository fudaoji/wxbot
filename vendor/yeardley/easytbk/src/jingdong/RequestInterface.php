<?php
/**
 * Created by PhpStorm.
 * User: YearDley
 * Date: 2019/1/8
 * Time: 15:54
 */

namespace YearDley\EasyTBK\JingDong;


interface RequestInterface
{
    public function getMethod();

    public function getParamJson();
}