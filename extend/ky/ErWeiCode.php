<?php
// +----------------------------------------------------------------------
// | [KyPHP System] Copyright (c) 2020 http://www.kuryun.com/
// +----------------------------------------------------------------------
// | [KyPHP] 并不是自由软件,你可免费使用,未经许可不能去掉KyPHP相关版权
// +----------------------------------------------------------------------
// | Author: fudaoji <fdj@kuryun.cn>
// +----------------------------------------------------------------------

/**
 * Created by PhpStorm.
 * Create: 2016/4/19 19:01
 * Description: 生成二维码
 * Author: Doogie<461960962@qq.com>
 */

namespace ky;

class ErWeiCode
{
    private $error;
    private $config = [
        'savePath' => UPLOAD_PATH . 'qrcode/',
        'rootPath' => '',
    ];

    public function __construct($config = []){
        include_once dirname(__FILE__) . '/ErWeiCode/phpqrcode.php';

        if($config){
            $config = (array)$config;
            foreach($config as $k => $v){
                if(isset($this->config[$k])) $this->config[$k] = $v;
            }
        }
    }

    /**
     * 返回二维码路径
     * @param string $text 表示生成二维码需要携带的信息
     * @param bool|false $outfile 是否输出二维码图片 文件，默认否
     * @param int $level 示容错率，也就是有被覆盖的区域还能识别，分别是 L（QR_ECLEVEL_L，7%），M（QR_ECLEVEL_M，15%），Q（QR_ECLEVEL_Q，25%），H（QR_ECLEVEL_H，30%）
     * @param int $size 生成图片大小，默认是6
     * @param int $margin 表示二维码周围边框空白区域间距值
     * @param bool|false $save_and_print 表示是否保存二维码并显示
     * @return bool|string
     * @Author: Doogie <461960962@qq.com>
     */
    public function qrCode($text, $outfile = false, $level = QR_ECLEVEL_M, $size = 6, $margin = 2, $save_and_print = false){
        if(class_exists('QRcode')){
            if($outfile){
                if($dir = $this->checkSavePath($this->getSavePath())){
                    $dir = rtrim($dir, '/');
                    $outfile = $dir . '/' . $outfile;
                    \QRcode::png($text, $outfile, $level, $size, $margin, $save_and_print);
                    return '/' . ltrim($outfile, './');
                }else{
                    return false;
                }
            }else{
                \QRcode::png($text, $outfile, $level, $size, $margin, $save_and_print);
            }
        }else{
            $this->setError('加载QRcode类错误');
            return false;
        }
    }

    /**
     * 返回二维码路径
     * @param string $text 表示生成二维码需要携带的信息
     * @param bool|false $outfile 是否输出二维码图片 文件，默认否
     * @param int $level 示容错率，也就是有被覆盖的区域还能识别，分别是 L（QR_ECLEVEL_L，7%），M（QR_ECLEVEL_M，15%），Q（QR_ECLEVEL_Q，25%），H（QR_ECLEVEL_H，30%）
     * @param int $size 生成图片大小，默认是6
     * @param int $margin 表示二维码周围边框空白区域间距值
     * @param bool|false $save_and_print 表示是否保存二维码并显示
     * @param bool $logo 需要添加的logo访问路径
     * @return bool|string
     * Author: Doogie<fdj@kuryun.cn>
     */
    public function qrCodeWithLogo($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 6, $margin = 2, $save_and_print = false, $logo = false){
        $code = $this->qrCode($text, $outfile, $level, $size, $margin, $save_and_print);
        if($code === false){
            return false;
        }
        $qr_code = 'http://' . $_SERVER['HTTP_HOST'] . $code;
        if ($logo) {
            $qr = imagecreatefromstring ( file_get_contents ( $qr_code ) );
            $logo = imagecreatefromstring ( file_get_contents ( $logo ) );
            $qr_width = imagesx ( $qr );
            $logo_width = imagesx ( $logo );
            $logo_height = imagesy ( $logo );
            $logo_qr_width = $qr_width / 5;
            $scale = $logo_width / $logo_qr_width; //缩放倍数
            $logo_qr_height = $logo_height / $scale;
            $from_width = ($qr_width - $logo_qr_width) / 2;
            imagecopyresampled ( $qr, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height );
            @unlink('./' . $code);
            imagepng ( $qr, './' . $code );//带Logo二维码的文件名
            return $qr_code;
        }else{
            $this->setError('logo图片无法访问');
            return false;
        }
    }

    /**
     * 返回保存路径
     * @return mixed
     * @Author: Doogie <461960962@qq.com>
     */
    private function getSavePath(){
        return $this->config['savePath'];
    }

    /**
     * 检查保存目录
     * @param string $savePath
     * @return bool
     */
    private function checkSavePath($savePath)
    {
        $dir = $this->mkDir($savePath);
        if($dir === false){
            return false;
        }else{
            if (!is_writeable($dir)) {
                $this->setError('上传目录' . $dir . '不可写!');
                return false;
            } else {
                return $dir;
            }
        }
    }

    /**
     * 创建目录
     * @param string $savePath
     * @return bool
     */
    private function mkDir($savePath)
    {
        $dir = $this->getRootPath() . $savePath;
        if (is_dir($dir)) {
            return $dir;
        }

        if (mkdir($dir, 0777, true)) {
            return $dir;
        } else {
            $this->setError('目录' . $dir . '创建失败!');
            return false;
        }
    }

    /**
     * 获取根路径
     */
    public function getRootPath()
    {
        return $this->config['rootPath'];
    }

    /**
     * 设置错误信息
     * @param string $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * 获取错误信息
     */
    public function getError()
    {
        return $this->error;
    }
}