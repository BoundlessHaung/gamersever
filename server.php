<?php
use Workerman\Worker;
require_once 'workerman/Autoloader.php';

// 创建一个Worker监听2346端口，使用websocket协议通讯
$ws_worker = new Worker("websocket://0.0.0.0:2346");

// 启动1个进程对外提供服务
$ws_worker->count = 1;

// 客户端连接服务器
$ws_worker->onConnect = function($connection)
{
    echo "IP为：" . $connection->getRemoteIp() . "的用户连接到了服务器\n";
};

// 当收到客户端发来的数据后返回hello $data给客户端
$ws_worker->onMessage = function($connection, $data)
{
	echo "IP为：" . $connection->getRemoteIp() . "发来消息：" . $data . "\n";
    // 向客户端发送hello $data
    $connection->send('Hello！我们收到了：' . $data);
};

// 运行worker
Worker::runAll();