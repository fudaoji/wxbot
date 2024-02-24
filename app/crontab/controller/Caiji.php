<?php

namespace app\crontab\controller;


class Caiji extends Base
{
    protected $domain = 'http://www.m326.com/';
    
    function start(){
        $cate = input('cate', 'top');
        $page_max = 10;
        $page_index = 1;
        $list = [];
        while($page_index <= $page_max){
            $list = array_merge($list, $this->listDeal($page_index, $cate));
            $page_index++;
        }
        
        $delay = 0;
        foreach ($list as $k => $item){
            //放入任务队列
            invoke('\\app\\common\\event\\TaskQueue')->push([
                'delay' => $delay,
                'params' => [
                    'do' => ['\\app\\crontab\\controller\\Caiji', 'saveFile'],
                    'file' => $item
                ]
            ]);
            $delay++;
        }
        var_dump(__FUNCTION__ . count($list));
    }
    
    function saveFile($params){
        $job = $params['job'];
        if ($job->attempts() > 2) {
            $job->delete();
        }
        $file = $params['file'];
        $file['url'] = $this->detailDeal($file['url']);
        $path = public_path() . '/uploads/file/mp3';
        if(! is_dir($path)){
            @mkdir($path, 0777);
        }
        $save_name = $path . '/'. $file['title'].'.mp3';
        if(! file_exists($save_name)){
            file_put_contents($save_name, file_get_contents($file['url']));
            dump($save_name.'完成!');
        }else{
            dump($save_name.'已存在');
        }
        $job->delete();
    }
    
    private function detailDeal($url){
        $html = file_get_contents($url);
        //return $html;
        $pattern = '/<a href="(.*)" target="_blank" download class="bt">/i';
        if(preg_match_all($pattern, $html, $matches)) {
            return $matches[1][0] ?? '';
        }
        return $matches;
    }
    
    private function listDeal($page_index, $cate){
        $list_html = file_get_contents($this->domain . "list/{$cate}/{$page_index}.html");
        $pattern = '/<div class="lk"><a href="(.*)" target="_mp3" title="(.*)">(.*)<\/a><div class="mv">/i';
        $list = [];
        if(preg_match_all($pattern, $list_html, $matches)) {
            $uri_list = $matches[1];
            $title_list = $matches[2];
            foreach ($uri_list as $k => $uri){
                $list[] = ['title' => str_replace("[MP3/LRC]","",$title_list[$k]), 'url' => $this->domain . $uri];
            }
        }
        return $list;
    }
}

?>