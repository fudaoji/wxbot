<?php

declare(strict_types=1);

namespace app\http;

use think\facade\Db;
use think\worker\Server;
use Workerman\Lib\Timer;
use app\common\model\EmojiCode;
use app\common\model\kefu\ChatLog;
use ky\Logger;
// define('HEARTBEAT_TIME', 30);// 心跳间隔
class Worker extends Server
{
	protected $socket = 'websocket://0.0.0.0:9506';
	protected static $heartbeat_time = 50;
	public static $daemonize = true;
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
					//$connection->close();
				}

				// #这里是一个用户下线后通知其他用户
				// if (count($offline_user) > 0){
				// 	$msg = ['type'=>'message','uid'=>$connection->uid,"message"=>"用户【".implode(',',$offline_user)."】下线了"];
				// 	$connection->send(json_encode($msg));
				// }
			}
		});

		Timer::add(1, function () use ($worker, $redis) {
			$key = 'receive_private_chat';
			$limit = 1000;
			// $chatLogM = new ChatLog();
			for ($i = 0; $i < $limit; $i++) {
				$msg = $redis->lPop($key);
				if ($msg) {
					$res = json_decode($msg, true);
					//Logger::write("发送消息---" . json_encode($res));
					//echo "用户：";
					//dump($this->worker);
					$lock_key = 'lock_'.$res['client'];
					$lock = $redis->get($lock_key);
					if ($lock) {
						//echo "用户锁：".$lock_key;
						sleep(1);
						$redis->rpush($key, json_encode($res));
						continue;
					} else {
						$redis->setex($lock_key,600, 1);
					}
					if (isset($this->worker->uidConnections) && isset($this->worker->uidConnections[$res['client']])) {
						$conn = $this->worker->uidConnections[$res['client']];
						if ($res['event'] == 'msg') {
							// $convert = $chatLogM->convertReceiveMsg($res['msg'], $res['msg_type']);
							$content = $res['msg'];
							// if ($res['msg_type'] == 1) {
							// 	$res['msg'] = $this->emojiCodeM->emojiText($content);
							// 	$content = $res['msg'];
							// } else if (!in_array($res['msg_type'],[3,2004])) {//图片和文件不处理
							// 	$res['msg'] = '[链接]';
							// 	$content = $res['msg'];
							// }
							// $content = $convert['content'];
							$last_chat_log = $res['last_chat_content'];
							$res['msg'] = $content;
							$conn->send(json_encode($res));
							//Logger::write("最终发送消息---" . json_encode($res));
							//echo "work发送消息：" . json_encode($res);
							//最后一条聊天记录放redis
							$last_log_key = 'last_chat_log:' . $res['robot_wxid'];
							$hkey = $res['from_wxid'];
							$result = [
								'msg_id' => $res['msg_id'],
								'date' => $res['date'],
								'content' => $last_chat_log,
								'type' => 'receive',
								'headimgurl' => $res['headimgurl'],
								'friend' => $res['friend'],
								'msg_type' => $res['msg_type'],
							];
							$redis->hSet($last_log_key, $hkey, json_encode($result));
						} else if ($res['event'] == 'new_friend') {
							$conn->send($msg);
						} else if ($res['event'] == 'callback') {
							//消息发送回调
							//echo "定时器消息发送回调---" . json_encode($res);
							//最后一条聊天记录放redis
							$last_log_key = 'last_chat_log:' . $res['robot_wxid'];
							$last_chat_log = $res['friend']['last_chat_content'];
							$hkey = $res['to'];
							$date = date("Y-m-d H:i:s", $res['create_time']);
							$result = [
								'msg_id' => $res['msg_id'],
								'date' => $date,
								'content' => $last_chat_log,
								'type' => 'send',
								'headimgurl' => $res['headimgurl'],
								'friend' => $res['friend'],
								'msg_type' => $res['msg_type'],
							];
							$redis->hSet($last_log_key, $hkey, json_encode($result));
							$conn->send($msg);
						}
					} else {
						echo("未找到客户端：" . $res['client']);
					}
					$redis->del($lock_key);
				}
			}
		});

		/**
		 * 
		 * 文件接收延迟
		 */
		Timer::add(5, function () use ($worker, $redis) {
			$key = 'receive_private_chat_delay';
			$limit = 1000;
			for ($i = 0; $i < $limit; $i++) {
				$msg = $redis->lPop($key);
				if ($msg) {
					$time = time();
					$data = json_decode($msg, true);
					//echo "文件接收延迟---" . json_encode($data) . "\n";
					if ($time < $data['start_time']) {
						//echo "延迟时间还没到,放回---" . json_encode($data) . "\n";
						$redis->rpush($key, json_encode($data));
						sleep(1);
						continue;
					}
					$lock_key = 'lock_'.$data['client'];
					$lock = $redis->get($lock_key);
					if ($lock) {
						//echo "用户锁：".$lock_key;
						sleep(1);
						$redis->rpush($key, json_encode($data));
						continue;
					} else {
						$redis->setex($lock_key, 60, 1);
					}

					$chatLogM = new ChatLog();
					// echo "开始转换数据：".$data['msg']."\n".$data['msg_type']."\n".json_encode($data['bot'])."\n";
					$convert = $chatLogM->convertReceiveMsg($data['msg'], $data['msg_type'], $data['bot']);
					//echo "延迟队列转换数据结果：" . json_encode($convert) . "\n";
                    //Logger::write("延迟队列转换数据结果：" . json_encode($convert));
					$data['num'] = $data['num'] + 1;
					if ($convert['content'] != '') {
						//视频转换成功
						//更新数据库，发送到前端替换视频
						$chatLogM->where(['id' => $data['id']])->update(['content' => $convert['content']]);
						if (isset($this->worker->uidConnections[$data['client']])) {
							$conn = $this->worker->uidConnections[$data['client']];
							$data['msg'] = $convert['content'];
							$data['event'] = 'delay';
							$conn->send(json_encode($data));
							//echo "视频转换成功，发送前端：" . json_encode($data) . "\n";
						}
					} else {
						//echo "延迟数据转换失败" . json_encode($convert) . "\n";
						if ($data['num'] < 100) {
							//失败+10秒再补回
							$data['start_time'] = $data['start_time'] + $data['delay_second'];
							$redis->rpush($key, json_encode($data));
							//sleep(1);
						}
					}

					$redis->del($lock_key);
				}
			}
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

	public function onError($connection, $code, $msg)
	{
		$error = "worker error [ $code ] $msg\n";
		Logger::write($error);
	}
}
