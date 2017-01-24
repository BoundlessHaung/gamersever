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

	/**
	 * 根据角色名获取角色
	 * 
	 * @param  string $charactername 角色名
	 * 
	 * @return array
	 */
	public function getCharacterByCharacterName($charactername = "") {
		return $this->select("*", [
			"charactername" => $charactername,
			"LIMIT" => 1
		]);
	}

	/**
	 * 添加角色
	 * 
	 * @param  array $characterdata 角色数据
	 *
	 * @return bool
	 */
	public function insertCharacter($characterdata) {
		$characterdata['uid'] = $this->uid;
		if ($this->insert($characterdata) == 0) {
			return false;
		}
		return true;
	}

	/**
	 * 移动角色
	 *
	 * @param  number $x x坐标
	 * @param  number $y y坐标
	 *
	 * @return number        受影响的行数
	 */
	public function characterMove($x, $y) {
		return $this->update([
			"wordx" => $x,
			"wordy" => $y
		], [
			"uid" => $this->uid
		]);
	}

	/**
	 * 获取当前角色的坐标
	 *
	 * @return array
	 */
	public function getThisCharacterPos() {
		return $this->get([
			"wordx",
			"wordy"
		], [
			"uid" => $this->uid
		]);
	}
}