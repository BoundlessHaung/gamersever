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

abstract class Controller
{
	// 服务器对象
	protected $server;

	// 链接对象
	protected $connection;

	// 消息
	protected $clientMsg;

	// 错误信息
	protected $errorMsg;

	// 执行状态
	protected $status;

	/**
	 * @param object $server 服务器对象
	 * @param object $connection 链接客户端对象
	 * @param string $data 已经格式化的消息数据
	 */
	public function __construct($server, $connection, $data) {
		$this->server = $server;
		$this->connection = $connection;
		$this->clientMsg = $data;
	}

	/**
	 * 判断是否已经登录
	 * 
	 * @return boolean
	 */
	public function hasSignIn() {
		if (isset($this->connection->uid) && isset($this->connection->username)) {
			return true;
		}
		return false;
	}
}
