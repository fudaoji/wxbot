<?php
/**
 * Script name: Logger.php
 * Created by PhpStorm.
 * Create: 2016/6/14 15:31
 * Description: 日志
 * Author: Doogie<461960962@qq.com>
 */

namespace ky;

use think\facade\Log;

class Logger extends Log
{
    /**
     * 抛出异常, 记录错误日志
     * @param string $msg
     * @param int $code
     * @throws \Exception
     * @author: Doogie<461960962@qq.com>
     */
    public static  function setMsgAndCode($msg='', $code = ErrorCode::CatchException){
        Log::error('ErrorCode: ' . $code . '; ErrorMsg: ' . $msg);
        Log::error('StackInfo:' . self::getStackInfo());
        throw new \Exception($msg, $code);
    }

    /**
     * 获取堆栈信息
     * @return string
     * @Author: Doogie <461960962@qq.com>
     */
    static public function getStackInfo(){
        $array = debug_backtrace();
        unset($array[0]);
        $res = '';
        foreach ($array as $row) {
            $file     = array_key_exists('file', $row) ? $row['file'] : '';
            if(strpos($file, 'think/App.php') !== false || strpos($file, 'thinkphp/start.php') !== false) continue;  //把多余的过滤
            $line     = array_key_exists('line', $row) ? $row['line'] : '';
            $class    = array_key_exists('class', $row) ? $row['class'] : '';
            $function = array_key_exists('function', $row) ? $row['function'] : '';
            $args     = array_key_exists('args', $row) ? print_r($row['args'], true) : '-';
            $res .= $file . ' ' . $line . '行, 调用类' . $class . ' 方法: ' . $function . ', 参数: ' . $args . "\r\n";
        }
        $res = substr($res, 0, -1);
        return $res;
    }
}