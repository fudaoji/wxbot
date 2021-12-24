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
 * Script Name: Task.php
 * Create: 2020/8/7 13:44
 * Description: 任务队列
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\job;

use think\facade\Log;
use think\queue\Job;

class Task
{

    /**
     * 任务worker
     * @param Job $job
     * @param $data
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function fire(Job $job, $data){
        try {
            if(isset($data['do'])){
                $data['job'] = $job; //将job抛给开发者
                if(is_string($data['do'])){//全局函数
                    $callback = $data['do'];
                }else{ //对象方法
                    $obj = new $data['do'][0]();
                    $callback = [$obj, $data['do'][1]];
                }
                //echo '==================='.json_encode($data).'==================';
                call_user_func_array($callback, [$data]);
            }else{
                $job->delete(); //待验证，是否会把其他job误杀
                echo(date('Y-m-d H:i:s') . '缺少do参数' . json_encode($data));
            }
        }catch (\Exception $e){
            Log::error($e->getMessage());
            $job->delete();
        }
    }

    public function failed($data){
        echo "任务执行失败, 参数：".json_encode($data);
    }

}