<?php
// +----------------------------------------------------------------------
// | [KyPHP System] Copyright (c) 2020 http://www.kuryun.com/
// +----------------------------------------------------------------------
// | [KyPHP] 并不是自由软件,你可免费使用,未经许可不能去掉KyPHP相关版权
// +----------------------------------------------------------------------
// | Author: fudaoji <fudaoji@gmail.com>
// +----------------------------------------------------------------------

namespace ky;

class Helper
{
    public static $ajax = array();
    public static $param = array();

    public static function doString($str)
    {
        return trim(strip_tags($str));
    }

    public static function doHtml($html)
    {
        if(!get_magic_quotes_gpc()){
            $html = addslashes($html);
        }
        return $html;
    }

    public static function checkTimestamp($timestamp)
    {
        if(strtotime(date('Y-m-d H:i:s',$timestamp)) === $timestamp) {
            return true;
        } else return false;
    }

    public static function checkUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    public static function checkEmail($email)
    {
        return strlen($email) > 5 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
    }

    /**
     * 验证电话号码和手机号码
     * @param string $tel
     * @param string $type
     * @return bool|string
     */
    public static function checkTel($tel='', $type='')
    {
        $isMob="/^1[3-5,7,8,9]{1}[0-9]{9}$/"; // 手机号码
        $isTel="/^([0-9]{3,4}-?)?[0-9]{7,8}$/"; // 固定电话
		switch($type){
			case 'mobile':
				if(!preg_match($isMob,$tel)) $tel = false;
				break;
			case 'telephone':
				if(!preg_match($isTel,$tel)) $tel = false;
				break;
			default:
				if(!preg_match($isMob,$tel) && !preg_match($isTel,$tel)) {
					$tel = false;
				}
				break;
		}
        return $tel;
    }

    /**
     * 验证邮编
     */
    public static function checkZip($zip)
    {
        $isZip = "/^[0-9]{6}$/";
        if (!preg_match($isZip, $zip)) {
            return false;
        }

        return $zip;
    }

    public static function checkWeixinhao($weixinhao)
    {
        return preg_match("/^[A-Za-z0-9_\-]+$/", $weixinhao);
    }

    /**
     * 字符串验证
     */
    public static function stringValid($string, $min, $max)
    {
        $string = self::doString($string);
        $stringLen = mb_strlen($string, 'utf8');
        if ($stringLen < $min || $stringLen > $max) {
            return false;
        }

        return $string;
    }

    /**
     * 带html内容验证
     * @param $html
     * @param $min
     * @param $max
     * @return bool|string
     */
    public static function htmlValid($html, $min, $max)
    {
        $html = self::doHtml($html);
        $htmlLen = mb_strlen($html, 'utf8');
        if ($htmlLen < $min || $htmlLen > $max) {
            return false;
        }
        return $html;
    }

    /**
     * 产生长度为16的唯一id
     *
     * 组成: 时间戳(10位) + 用户id(用户id被0填充到左边，使长度为6) =
     */
    public static function getUniqueId($id, $length = 6, $string = 0)
    {
        $id = str_pad($id, $length, $string, STR_PAD_LEFT);

        $timestamp = time();

        $uniqid = $timestamp . $id;

        return intval($uniqid);
    }

    /**
     * 验证身份证号码
     */
    public static function checkIdCard($idcard)
    {
        // 只能是18位
        if(strlen($idcard) != 18) {
            return false;
        }
        // 取出本体码
        $idcard_base = substr($idcard, 0, 17);
        // 取出校验码
        $verify_code = substr($idcard, 17, 1);
        // 加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        // 校验码对应值
        $verify_code_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        // 根据前17位计算校验码
        $total = 0;
        for($i=0; $i<17; $i++){
            $total += (int)substr($idcard_base, $i, 1) * $factor[$i];
        }
        // 取模
        $mod = $total % 11;
        // 比较校验码
        if($verify_code == $verify_code_list[$mod]) {
            return $idcard;
        }else{
            return false;
        }
    }

    /**
     * 成功返回
     * @param string $msg
     * @param array $data
     * @Author: Doogie <461960962@qq.com>
     */
    public static function success($msg = '', $data = []){
        response()->create(['code' => ErrorCode::SuccessCode, 'msg' => $msg, 'data' => $data], 'json')->send();exit;
    }

    /**
     * 错误返回
     * @param string $msg
     * @param int $code
     * @Author: Doogie <461960962@qq.com>
     */
    public static function error($code, $msg = ''){
        response()->create(['code' => $code, 'msg' => $msg], 'json')->send();exit;
    }

    /**
     * 验证银行卡号
     * 16-19 位卡号校验位采用 Luhm 校验方法计算：
     * 1，将未带校验位的 15 位卡号从右依次编号 1 到 15，位于奇数位号上的数字乘以 2
     * 2，将奇位乘积的个十位全部相加，再加上所有偶数位上的数字
     * 3，将加法和加上校验位能被 10 整除。
     * @param string $no 卡号
     * @return bool
     */
    public static function checkBank($no='')
    {
        $arr_no = str_split($no);
        $last_n = $arr_no[count($arr_no)-1];
        krsort($arr_no);
        $i = 1;
        $total = 0;
        foreach ($arr_no as $n){
            if($i%2==0){
                $ix = $n*2;
                if($ix>=10){
                    $nx = 1 + ($ix % 10);
                    $total += $nx;
                }else{
                    $total += $ix;
                }
            }else{
                $total += $n;
            }
            $i++;
        }
        $total -= $last_n;
        $total *= 9;
        return $last_n == ($total%10);
    }
}
