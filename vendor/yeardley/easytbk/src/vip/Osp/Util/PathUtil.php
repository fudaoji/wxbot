<?php

namespace YearDley\EasyTBK\Vip\Osp\Util;

class PathUtil
{

    /**
     * 获取应用根目录
     *
     * @return string
     */
    public static function getRootPath()
    {
        return realpath(dirname(__FILE__) . '/../../');
    }
}

?>
