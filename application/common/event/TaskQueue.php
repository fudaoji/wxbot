<?php
// +----------------------------------------------------------------------
// | [KyPHP System] Copyright (c) 2020 http://www.kuryun.com/
// +----------------------------------------------------------------------
// | [KyPHP] 并不是自由软件,你可免费使用,未经许可不能去掉KyPHP相关版权
// +----------------------------------------------------------------------
// | License  https://gitee.com/fudaoji/KyPHP/blob/master/LICENSE
// +----------------------------------------------------------------------

/**
 * Created by PhpStorm.
 * Script Name: Queue.php
 * Create: 2020/8/7 下午11:29
 * Description: 队列任务
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\event;
use ky\ErrorCode;
use think\facade\Log;
use think\Queue;

class TaskQueue extends Base
{

    /**
     * 任务入队列
     * @param array $params
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function push($params = []){
        $worker = "app\\common\\job\\Task";
        $queue = config('queue_name');
        if(empty($params['params']['do'])){
            abort(ErrorCode::CatchException, '缺少任务执行者');
        }
        if(empty($params['delay'])){
            Queue::push($worker, $params['params'], $queue);
        }else{
            Queue::later($params['delay'], $worker, $params['params'], $queue);
        }
    }

    /**
     * 任务队列测试消费者
     * @param $data
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function testTask($data){
        echo '测试任务队列执行';
        $job = $data['job'];
        if ($job->attempts() > 2) {
            echo '我要删除任务了';
            //通过这个方法可以检查这个任务已经重试了几次了
            $job->delete();
        }
    }
}