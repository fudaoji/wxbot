<?php
/**
 * Created by PhpStorm.
 * Script Name: ListBuilder.php
 * Create: 12/28/22 4:39 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\taglib;
use think\template\TagLib;

class App extends TagLib
{

    /**
     * 定义标签列表
     */
    protected $tags   =  [
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'types'     => ['close' => 0], //闭合标签，默认为不闭合
    ];

    /**
     * 应用类型
     * @param $tag
     * @return string
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function tagTypes($tag)
    {
        $types = $this->tpl->get($tag['types']);
        if(is_string($types)){
            $types = explode(',', $types);
        }
        $haystack = $this->tpl->get($tag['haystack']);
        if(is_string($haystack)){
            $haystack = explode(',', $haystack);
        }

        $html = '';
        foreach ($types as $v){
            isset($haystack[$v]) && $html .= '<span style="margin-right: 2px;" class="layui-badge layui-bg-orange">'.$haystack[$v].'</span>';
        }
        return $html;
    }
}