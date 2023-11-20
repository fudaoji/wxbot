<?php
/**
 * Created by PhpStorm.
 * Script Name: Mini.php
 * Create: 2020/7/22 18:05
 * Description: 小程序相关
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\event;

class MsgLog extends Base
{
    /**
     * 写入消息
     * @param $params
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function addLog($params)
    {
        /**
         * @var \think\queue\Job
         */
        $job = $params['job'];
        if ($job->attempts() > 2) {
            $job->delete();
        }

        \app\common\service\MsgLog::saveData($params);
        $job->delete();
    }
}