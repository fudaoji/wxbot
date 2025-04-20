<?php
/**
 * Created by PhpStorm.
 * Script Name: Adv.php
 * Create: 2020/10/23 11:20
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\taglib\home;

use ky\Logger;
use think\template\TagLib;

class Blog extends TagLib
{
    /**
     * 定义标签列表
     */
    protected $tags   =  [
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'labels'     => ['close' => 0], //闭合标签，默认为不闭合
    ];

    /**
     * 标签云
     * @param array $tag
     * @return string
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function tagLabels($tag){
        $blog = $this->tpl->get($tag['blog']);
        $list = explode(',', $blog['columns']);

        $html = '<div class="tag-cloud mb-4">';
        foreach ($list as $k => $v){
            $html .= ('<a href="'.url('home/blog/index', ['keyword' => $v]).'" class="badge bg-light text-dark border">'.$v.'</a>&nbsp;');
        }
        return $html . "</div>";
    }
}