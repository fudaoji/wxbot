<?php
/**
 * Created by PhpStorm.
 * Script Name: Media.php
 * Create: 12/29/21 10:15 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\constants;


class Media
{
    const TEXT = "text";
    const IMAGE = "image";
    const FILE = "file";
    const VIDEO = "video";
    const LINK = "link";
    const XML = "xml";

    public static function types($id = null){
        $list = [
            Media::TEXT => '文本',
            Media::IMAGE => '图片',
            Media::FILE => '文件',
            Media::VIDEO => '视频',
            Media::LINK => '分享链接',
            Media::XML => 'XML卡片'
        ];
        return isset($list[$id]) ? $list[$id] : $list;
    }
}