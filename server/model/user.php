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

namespace server\model;

use server\Model;

class user extends Model
{
	
	// 表名
	protected $tablename = "user";

	/**
	 * 根据username获取uid
	 *
	 * @return number|string
	 */
	public function getUidByUserName() {
		return $this->select(["uid"], [
			    "username" => $this->username,
			    "LIMIT" => 1
			])[0]['uid'];
	}

	public function getUserByUserName() {

	}
}