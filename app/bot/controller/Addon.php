<?php
/**
 * Created by PhpStorm.
 * Script Name: Addon.php
 * Create: 2022/4/18 10:58
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\controller;

use app\bot\handler\Handler;
use ky\Logger;

class Addon extends Handler
{

    public function init($options = []){
        $this->botClient = $options['bot_client'];
        $this->botWxid = $options['bot_wxid'];
        $this->fromWxid = $options['from_wxid'];
        $this->fromName = $options['from_name'];
        $this->groupWxid = $options['group_wxid'];
        $this->groupName = $options['group_name'];
        $this->group = $options['group'];
        $this->content = $options['content'];
        $this->isNewFriend = $options['is_new_friend'];
        $this->isNewGroup = $options['is_new_group'];

        $this->driver = $options['driver'];
        $this->ajaxData = $options['ajax_data'];
        $this->event = $options['event'];
        $this->bot = $options['bot'];
        $this->beAtStr = $options['be_at_str'];
        return $this;
    }

    /**
     * 应用内省略模块和控制的快速url
     * @param string $url
     * @param array $vars
     * @param bool $suffix
     * @param bool $domain
     * @return string
     * Author: fudaoji<fdj@kuryun.cn>
     */
    protected function url(string $url = '', array $vars = [], $suffix = true, $domain = false){
        $url_arr = explode('/', $url);
        switch (count($url_arr)){
            case 1:
                $url = $this->module.'/'.$this->controller . '/'.$url;
                break;
            case 2:
                $url = $this->module.'/'.$url;
                break;
        }
        $url = trim($url, '/');
        $module = request()->root();
        $rule = request()->rule()->getRule();
        $rule_arr = explode('/', $rule);
        $addon = $rule_arr[0];
        return url("/{$module}/{$addon}/{$url}", $vars, $suffix, $domain)->build();
    }
}