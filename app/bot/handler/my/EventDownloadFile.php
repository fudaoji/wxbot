<?php
/**
 * Created by PhpStorm.
 * Script Name: EventDownloadFile.php
 * Create: 2023/5/29 9:06
 * Description: 文件下载结束事件
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\bot\handler\my;


use app\bot\handler\HandlerDownloadFile;

class EventDownloadFile extends HandlerDownloadFile
{

    public function handle(){
        $this->addon();
    }
}