<?php
// +----------------------------------------------------------------------
// | Manor: chaos —————— Times war strategy game
// +----------------------------------------------------------------------
// | Manor: chaos GameServer
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2016 http://xxxxxxxx.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: Boundless <george.haung@sandboxcn.com>
// +----------------------------------------------------------------------

namespace server;

use Workerman\Worker;
use Workerman\Lib\Timer;
use Predis\Client;
use think\helper\Str;

// 创建一个Worker监听2346端口，使用websocket协议通讯
$mc_server = new Worker("websocket://0.0.0.0:2346");

// 启动1个进程对外提供服务
$mc_server->count = 1;

// 为服务器增加一个属性用来存储在线玩家（映射 username=>$connection）
$mc_server->playerlist = array();

// 客户端连接服务器
$mc_server->onConnect = function($connection)
{
	global $mc_server;
	echo "IP为：" . $connection->getRemoteIp() . "的用户连接到服务器\n";
	Timer::add(2, function($connection, $mc_server)
	{
		if ($connection->username) {
			if ($mc_server->playerlist[$connection->username]) {
				return;
			}
			echo "IP为：" . $connection->getRemoteIp() . "的客户端逾期未做登录操作，已强制断开连接\n";
			return $connection->close();
		}
		// 如果能运行到这里那么就断开链接。
		return $connection->close();
	}, array($connection, $mc_server), false);
};
	
// 当收到客户端发来的数据时
$mc_server->onMessage = function($connection, $data)
{
	global $mc_server;
	echo "IP为：" . $connection->getRemoteIp() . "发来消息：" . $data . "\n";
	// 格式化接收到的数据
	$clientMsg = json_decode($data, true);
	switch ($clientMsg['type']) {
		case 'login':
			echo "username：" . $clientMsg['username'] . "\n";
			echo "token：" . $clientMsg['token'] . "\n";
			// 进行对比
			$redisClient = new Client('tcp://127.0.0.1:6379');
			if ($clientMsg['token'] == $redisClient->get('signin_' . $clientMsg['username'])) {
				// 将用户名存入链接并以用户名为key将链接的客户端映射到playerlist中
				$connection->username = $clientMsg['username'];
				$mc_server->playerlist[$clientMsg['username']] = $connection;
				return $connection->send(json_encode([
					'type' => 'login',
					'status' => 1,
					'msg' => '登录成功'
				]));
			} else {
				return $connection->send(json_encode([
					'type' => 'login',
					'status' => 0,
					'msg' => '登录失败，链接断开'
				]))->close();
			}			
			break;
		default:
			$connection->send('ERROR：未知的数据类型。');
			break;
	}

};

// 运行worker
Worker::runAll();