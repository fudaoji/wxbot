<?php

declare(strict_types=1);

namespace app\http;

use think\facade\Db;
use think\worker\Server;
use Workerman\Lib\Timer;

// define('HEARTBEAT_TIME', 30);// 心跳间隔
class Worker extends Server
{
	protected $socket = 'websocket://0.0.0.0:9506';
	protected static $heartbeat_time = 50;
    protected static $daemonize = true;

	public function init()
    {

    }

    public function onWorkerStart($worker)
	{
		$redis = get_redis();
		Timer::add(10, function () use ($worker) {
			$time_now = time();
			#这里统计下线人员的id
			// $offline_user = [];

			foreach ($worker->connections as $connection) {
				// 有可能该connection还没收到过消息，则lastMessageTime设置为当前时间
				if (empty($connection->lastMessageTime)) {
					$connection->lastMessageTime = $time_now;
					continue;
				}
				// 上次通讯时间间隔大于心跳间隔，则认为客户端已经下线，关闭连接
				// if ($time_now - $connection->lastMessageTime > HEARTBEAT_TIME) {
					// echo $connection->lastMessageTime.PHP_EOL;
				if ($time_now - $connection->lastMessageTime > self::$heartbeat_time) {
					// echo "当前时间：".date("Y-m-d H:i:s",$time_now).PHP_EOL;
					// echo "最后通讯时间：".date("Y-m-d H:i:s",$connection->lastMessageTime).PHP_EOL;
					// echo "时间间隔：".$time_now - $connection->lastMessageTime.PHP_EOL;
					// echo "心跳：".self::$heartbeat_time;
					#这里统计下线人员的id
					// $offline_user[] = $connection->uid;
					#关闭连接
					$connection->close();
				}

				// #这里是一个用户下线后通知其他用户
				// if (count($offline_user) > 0){
				// 	$msg = ['type'=>'message','uid'=>$connection->uid,"message"=>"用户【".implode(',',$offline_user)."】下线了"];
				// 	$connection->send(json_encode($msg));
				// }
			}
		});


		Timer::add(5, function () use ($worker, $redis) {
			$key = 'receive_private_chat';
			$limit = 1000;
			for ($i = 0; $i < $limit; $i++) {
				$msg = $redis->lPop($key);
				if ($msg) {
					$res = json_decode($msg, true);
					if (isset($this->worker->uidConnections[$res['client']])) {
						$conn = $this->worker->uidConnections[$res['client']];
						$conn->send($msg);
						echo "发生消息：".$msg;
						//最后一条聊天记录放redis
						$last_log_key = 'last_chat_log:' . $res['robot_wxid'];
						$hkey = $res['from_wxid'];
						$result = [
							'msg_id' => $res['msg_id'],
							'date' => $res['date'],
							'content' => $res['msg'],
							'type' => 'receive',
							'headimgurl' => $res['headimgurl'],
							'friend' => $res['friend'],
							'msg_type' => $res['msg_type'],
						];
						$redis->hSet($last_log_key, $hkey, json_encode($result));
					}
				}
			}
			
			// $msg = json_encode(['msg' => 'zzy测试测试', 'date' => date("Y-m-d H:i:s"), 'msg_id' => time(), 'headimgurl' => '', 'from_wxid' => 'wxid_53fet7200ygs22', 'robot_wxid' => 'cengzhiyang4294']);
			
		});
	}

	public function onMessage($connection, $data)
	{
		#最后接收消息时间
		$connection->lastMessageTime = time();
		

		$msg_data = json_decode($data, true);
		// echo date("Y-m-d:H:i:s")."最后接收消息:".$data.PHP_EOL;
		if (!$msg_data) {
			return;
		}
		#绑定用户ID
		if (isset($msg_data['type']) && $msg_data['type'] == 'bind' && !isset($connection->uid)) {
			$connection->uid = $msg_data['uid'];
			$this->worker->uidConnections[$connection->uid] = $connection;
		}


		// Db::name('online_customer_service')->insert();

		#单人发消息
		// if ($msg_data['type'] == 'text' && $msg_data['mode'] == 'single'){
		// 	if (isset($this->worker->uidConnections[$msg_data['to_id']])){
		// 		$conn = $this->worker->uidConnections[$msg_data['to_id']];
		// 		$conn->send($data);
		// 	}
		// }
		#群聊
		// if ($msg_data['type'] == 'text' && $msg_data['mode'] == 'group'){
		// 	#实际项目通过群号查询群里有哪些用户
		// 	$group_user = [10009,10010,10011,10012,10013,10014,10015,10016,10017];
		// 	foreach ($group_user as $key => $val){
		// 		if (isset($this->worker->uidConnections[$val])){
		// 			$conn = $this->worker->uidConnections[$val];
		// 			$conn->send($data);
		// 		}
		// 	}

		// }

	}
}
