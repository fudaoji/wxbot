<?php
/**
 * Created by PhpStorm.
 * Script Name: Test.php
 * Create: 12/20/21 11:49 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\controller;
use think\Controller;
use app\admin\model\Goods;
use ky\Bot\Wx;

class Crontab extends Controller
{
    public function test(){
        echo "crontab";
    }
    /**
     * 
     * 商品入库
     */
    public function getGoods(){
        $url = "http://japi.jingtuitui.com/api/get_goods_list";
        $param = [
            'appid' => '2112202303504964',
            'appkey' => '3dd074414022a194939588a3547b20a6',
            'v' => 'v2',
            'pageIndex'=> 1,
            'pageSize' => 100
        ];
        //$model = new Goods();
        $redis = controller('common/base', 'event')->getRedis();
        $redis->select(1);
        $key = 'goods_page';
        $pageIndex = $redis->get($key);
        if (!$pageIndex) {
            $pageIndex = 1;
            $redis->set($key, $pageIndex);
        }
        $num = 0;
        $last = $pageIndex + 50;
        for ($pageIndex; $pageIndex < $last; $pageIndex++) {
            $param['pageIndex'] = $pageIndex;
            $res = json_decode(http_post($url, $param),true);
            if (count($res['result']['data']) > 0) {
                //$insert_datas = [];
                foreach ($res['result']['data'] as $val) {
                    if (!isset($val['spu_id'])) {
                        $insert_data = [
                            'sku_id' => $val['goods_id'],
                            'status' => 0,
                            'create_time' => time(),
                            'update_time' => time()
                        ];
                    } else {
                        $insert_data = [
                            'sku_id' => $val['goods_id'],
                            'spu_id' => $val['spu_id'],
                            'create_time' => time(),
                            'update_time' => time()
                        ];
                    }
                    $redis->rpush('goods_data',json_encode($insert_data));
                    // $insert_datas[] = $insert_data;
                    $num ++;
                }
                // $model->insertAll($insert_datas);
            } else {
                break;
            }
        }
        
        $redis->set($key, $pageIndex);

        echo "OK";
        echo "</br>";
        echo $num;
        
    }
    /**
     * 
     * 定时入库
     */
    public function inGoods(){
        $redis = controller('common/base', 'event')->getRedis();
        $redis->select(1);
        $key = 'goods_data';
        $model = new Goods();
        $insert_data = [];
        $num = 0;
        for ($i = 0;$i < 1000;$i++) {
            $r_data = $redis->lpop($key);
            if ($r_data) {
                $data = json_decode($r_data, true);
                $insert_data[] = $data;
                $num++;
            } else {
                break;
            } 
        }
        if ($insert_data) {
            $model->insertAll($insert_data);
        }

        echo "OK";
        echo "</br>";
        echo $num;

    }

    public function updateGoods(){
        $url = "http://japi.jingtuitui.com/api/get_goods_update";
        $param = [
            'appid' => '2112202303504964',
            'appkey' => '3dd074414022a194939588a3547b20a6',
            'v' => 'v2',
            'pageIndex'=> 1,
            'pageSize' => 100
        ];
        $model = new Goods();
        $redis = controller('common/base', 'event')->getRedis();
        $redis->select(1);
        $key2 = 'goods_start_time';
        $start_time = $redis->get($key2);
        if (!$start_time) {
            $start_time = $model->value('update_time');
        }
        $key = 'goods_update_page';
        $pageIndex = $redis->get($key);
        if (!$pageIndex) {
            $pageIndex = 1;
            $redis->set($key, $pageIndex);
        }
        $num = 0;
        $now = time();
        $flag = 0;
        $last = $pageIndex + 50;
        for ($pageIndex; $pageIndex < $last; $pageIndex++) {
            $param['pageIndex'] = $pageIndex;
            $param['start_time'] = $start_time;
            $res = json_decode(http_post($url, $param),true);
            if (count($res['result']['data']) > 0) {
                //$insert_datas = [];
                foreach ($res['result']['data'] as $val) {
                    if (!isset($val['spu_id'])) {
                        $insert_data = [
                            'sku_id' => $val['goods_id'],
                            'status' => 0,
                            'create_time' => time(),
                            'update_time' => time()
                        ];
                    } else {
                        $insert_data = [
                            'sku_id' => $val['goods_id'],
                            'spu_id' => $val['spu_id'],
                            'create_time' => time(),
                            'update_time' => time()
                        ];
                    }
                    $redis->rpush('goods_update_data',json_encode($insert_data));
                    // $insert_datas[] = $insert_data;
                    $num ++;
                }
                // $model->insertAll($insert_datas);
            } else {
                //到头了,不一定没了
                $flag = 1;
                break;
            }
        }
        if (!$flag) {//还在上一轮更新
            $redis->set($key, $pageIndex);
        } else {
            $redis->set($key, 1);
            $redis->set($key2, $now);
        }
        

        echo "OK";
        echo "</br>";
        echo $num;
    }


    public function updateGoods2(){
        $redis = controller('common/base', 'event')->getRedis();
        $redis->select(1);
        $key = 'goods_update_data';
        $model = new Goods();
        $insert_data = [];
        $update_data = [];
        $num = 0;
        for ($i = 0;$i < 1000;$i++) {
            $r_data = $redis->lpop($key);
            if ($r_data) {
                $data = json_decode($r_data, true);
                $exist = $model->where(['sku_id' => $data['sku_id']])->find();
                if ($exist) {
                    $update = ['id' => $exist['id'],  'update_time' => $data['update_time']];
                    if (isset($data['status'])) {
                        $update['status'] = $data['status'];
                    }
                    $update_data[] = $update;
                } else {
                    $insert_data[] = $data;
                }
                
                $num++;
            } else {
                break;
            } 
        }
        if ($insert_data) {
            $model->insertAll($insert_data);
        }
        if ($update_data) {
            $model->saveAll($update_data);
        }

        echo "OK";
        echo "</br>";
        echo $num;

    }
}