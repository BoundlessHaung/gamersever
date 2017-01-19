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

namespace server\controller;

use server\model\user;

/**
 * 用户控制类
 *
 * @author Boundless <george.haung@sandboxcn.com>
 */
class account
{

	// 服务器对象
	private $server;

	// 链接对象
	private $connection;

	// 消息
	private $clientMsg;

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
	 * 如果该链接的用户没有登录则关闭链接
	 * 
	 * @return
	 */
	public function closeIfNotSignIn() {
		if ($this->connection->uid) {
			if ($this->server->playerlist[$this->connection->uid]) {
				return;
			}
			echo "IP为：" . $this->connection->getRemoteIp() . "的客户端逾期未做登录操作，已强制断开连接\n";
			return $this->connection->close();
		}
		// 如果能运行到这里那么就断开链接。
		return $this->connection->close();
	}

	/**
	 * 做登录操作
	 * 
	 * @return
	 */
	public function signin() {
		if (!isset($this->clientMsg['type']) || !isset($this->clientMsg['username']) || !isset($this->clientMsg['token'])) {
			return $this->connection->send(json_encode([
				'type' => 'login',
				'status' => 0,
				'msg' => '信息有误，链接断开'
			]))->close();
		}
		echo "IP：" . $this->connection->getRemoteIp() . " type：" . $this->clientMsg['type'] . " username：" . $this->clientMsg['username'] . " token：" . $this->clientMsg['token'] . "\n";

		// 进行对比
		if ($this->clientMsg['token'] == $this->server->redis->get('signin_' . $this->clientMsg['username'])) {

			// 根据用户名获取用户ID
			$usermodel = new user($this->clientMsg['username'], $this->server->database);
			$loginuserid = $usermodel->getUidByUserName();

			// 将用户名存入链接并以用户名为key将链接的客户端映射到playerlist中
			$this->connection->uid = $loginuserid;
			$this->server->playerlist[$loginuserid]['client'] = $this->connection;
			return $this->connection->send(json_encode([
				'type' => 'login',
				'status' => 1,
				'msg' => '登录成功'
			]));
		} else {
			return $this->connection->send(json_encode([
				'type' => 'login',
				'status' => 0,
				'msg' => '登录失败，链接断开'
			]))->close();
		}	
	}
}
