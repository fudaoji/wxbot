<?php
/**
 * Created by PhpStorm.
 * Script Name: Demo.php
 * Create: 2020/11/19 14:38
 * Description:
 * Author: Jason<dcq@kuryun.cn>
 */

namespace app\api\job;


use ky\Logger;
use think\queue\Job;
use think\Db;

class UserUpJob
{
    public function fire(Job $job, $data){
        print("<info>UserUpJob Started. job Data is: " . var_export($data, true) . "</info> \n");
        if(!$data['user_id']) {
            $job->delete();
            return;
        }
        //启动事务
        Db::startTrans();
        try {
            $result = true;
            //用户消费金额统计：项目订单+商品订单
            $total_book = model('common/OrderBook')->sums('total', ['user_id' => $data['user_id'], 'paid' => 1, 'status' => ['in', [2, 3, 5]]]);
            $total_goods = model('common/OrderGoods')->sums('total', ['user_id' => $data['user_id'], 'paid' => 1, 'status' => ['in', [2, 5]]]);
            $total = ($total_book + $total_goods) * 0.01;
            $map = [
                'min' => ['elt', $total],
                'max' => ['egt', $total],
                'status' => 1
            ];
            $level = model('common/Level')->getOneByMap($map);
            if($level) {
                $result = model('common/User')->updateOne(['id' => $data['user_id'], 'level_id' => $level['id']]);
            }
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $result = false;
        }

        if($result) {
            $job->delete();
            Logger::write('Info: 已重新核算会员的等级');
        }else {
            if($job->attempts() > 3) {
                $job->delete();
            }
        }
    }

    public function failed($data){
        // ...任务达到最大重试次数后，失败了
    }
}