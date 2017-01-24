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

	/**
	 * 向当前客户端发送信息
	 *
	 * @param  array|string|number  $msg 消息
	 *
	 * @return $this
	 */
	public function sendMsgToThisClient($msg = null) {
		$this->_sendMsgToClient($this->connection, $msg);
		return $this;
	}

	/**
	 * 向所有在线用户广播消息
	 *
	 * @param  array|string|number  $msg 消息
	 *
	 * @return $this
	 */
	public function sendMsgToAllOnlinePlayers($msg = null) {
		foreach ($this->server->playerlist as $signinplayer) {
			$this->_sendMsgToClient($signinplayer['client'], $msg)
		}
		return $this;
	}

	/**
	 * 向指定的客户端发送消息
	 *
	 * @param  object $client 链接的实例
	 * @param  string $msg    消息
	 *
	 * @return
	 */
	private function _sendMsgToClient($client, $msg = "") {
		return $client->send(json_encode($msg));
	}

	/**
	 * 关闭当前链接
	 *
	 * @return
	 */
	public function close() {
		// 如果是登陆后的用户要从玩家池中清除
		if (isset($this->connection->uid) || isset($this->connection->username)) {
			unset($this->server->playerlist[$this->connection->uid]);
			unset($this->connection->uid);
			unset($this->connection->username);
		}
		return $this->connection->close();
	}
}
