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
use Predis\Client;
use think\helper\Str;

// 创建一个Worker监听2346端口，使用websocket协议通讯
$mc_server = new Worker("websocket://0.0.0.0:2346");

// 启动1个进程对外提供服务
$mc_server->count = 1;

// 为服务器增加一个属性用来存储在线玩家（映射 uid=>$connection）
$mc_server->playerlist = array();

// 客户端连接服务器
$mc_server->onConnect = function($connection)
{
    echo "IP为：" . $connection->getRemoteIp() . "的用户连接到了服务器\n";
};

// 当收到客户端发来的数据时
$mc_server->onMessage = function($connection, $data)
{
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
				$connection->send(json_encode([
					'type' => 'login',
					'status' => 1,
					'msg' => '登录成功'
				]));
			} else {
				$connection->send(json_encode([
					'type' => 'login',
					'status' => 0,
					'msg' => '登录失败，链接断开'
				]))->close();
			}
			Str::random(4);// 我是来搞笑的
			echo "随机着玩：" . Str::random(4) . "\n";
			
			break;
		default:
			$connection->send('ERROR：未知的数据类型。');
			break;
	}

};

// 运行worker
Worker::runAll();