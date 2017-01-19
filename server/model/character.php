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

class character extends Model
{
	
	// 表名
	protected $tablename = "character";

	/**
	 * 获取角色
	 *
	 * @return array
	 */
	public function getCharacter() {
		return $this->select("*", [
			    "uid" => $this->uid,
			    "LIMIT" => 1
			]);
	}
}