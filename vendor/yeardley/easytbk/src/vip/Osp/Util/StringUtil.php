<?php

namespace YearDley\EasyTBK\Vip\Osp\Util;

class StringUtil
{

    public static function strTo16Hex($str)
    {
        $result = "";
        for ($i = 0; $i < strlen($str); $i++) {
            $it = ord($str [$i]);
            $result .= sprintf("%02x ", $it);
        }
        return $result;
    }
}

?>
