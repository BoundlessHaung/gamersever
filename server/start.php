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
use server\eventController;
use medoo;

// 创建一个Worker监听2346端口，使用websocket协议通讯
$mc_server = new Worker("websocket://0.0.0.0:2346");

// 启动1个进程对外提供服务
$mc_server->count = 1;

// 为服务器增加一个属性用来存储在线玩家（映射 uid=>player）
$mc_server->playerlist = array();

$mc_server->config = include CONFIG_PATH;

// redis连接常驻内存
$mc_server->redis = new Client('tcp://127.0.0.1:6379');

// 数据库连接常驻内存
$mc_server->database = new medoo([
	// 必须配置项
	'database_type' => 'mysql',
	'database_name' => 'manor_chaos',
	'server' => '127.0.0.1',
	'username' => 'root',
	'password' => 'killtomcat2016!',
	'charset' => 'utf8',

	// 可选参数
	'port' => 3306,

	// 可选，定义表的前缀
	'prefix' => 'mc_',
]);

/**
 * 客户端连接服务器
 */
$mc_server->onConnect = function($connection)
{
	global $mc_server;
	echo "IP为：" . $connection->getRemoteIp() . "的用户连接到服务器\n";
	$account = new controller\account($mc_server, $connection, "");
	Timer::add(2, array($account, 'closeIfNotSignIn'), array(), false);
};

/**
 * 当收到客户端发来的数据时
 */
$mc_server->onMessage = function($connection, $data)
{
	global $mc_server;
	echo "IP为：" . $connection->getRemoteIp() . "发来消息：" . $data . "\n";
	$serverEventController = new eventController($mc_server, $connection, $data);
	$serverEventController->doIt();
};
/**
 * 当有客户端连接断开时
 */
$mc_server->onClose = function($connection)
{
	global $mc_server;
	if(isset($connection->uid))
	{
		// 连接断开时删除映射
		unset($mc_server->playerlist[$connection->uid]);
	}
};

// 运行worker
Worker::runAll();