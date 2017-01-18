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

/**
 * 负责服务器逻辑的整体控制
 *
 * @author Boundless <george.haung@sandboxcn.com>
 */
class eventController
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
	 * @param string $data 尚未格式化的消息数据
	 */
	public function __construct($server, $connection, $data) {
		$this->server = $server;
		$this->connection = $connection;
		// 格式化消息数据
		$this->clientMsg = json_decode($data, true);
	}

	/**
	 * Just Do It
	 * 
	 * @return
	 */
	public function doIt() {
		if (is_array($this->clientMsg) || count($this->clientMsg) > 0) {
			if (isset($this->clientMsg['type'])) {
				switch ($this->clientMsg['type']) {
					case 'login':
						$account = new controller\account($this->server, $this->connection, $this->clientMsg);
						return $account->signin();
						break;
					default:
						return $this->connection->send('ERROR：未知的数据类型。');
						break;
				}
			}
		}
		// 运行到这里那就肯定是出错了
		return $this->connection->send('ERROR：未知的数据类型。');
	}
}
