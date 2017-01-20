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

abstract class Model
{
	// 用户名
	protected $username = "";

	// 用户ID
	protected $uid = 0;

	// 数据库链接
	protected $databaseconnection = null;

	// 表名
	protected $tablename = "";

	/**
	 * 构造函数
	 *
	 * @param string $username 用户名
	 * @param int $uid 用户ID[可选]
	 * @param object $databaseconnection 数据库连接
	 */
	public function __construct($username = "",$uid = 0, $databaseconnection = null) {
		// 当缺少一个参数时UID内的参数就是databaseconnection对象（UID参数在除了登录部分以外不可省略）
		if ($databaseconnection == null) {
			$databaseconnection = $uid;
			$uid = 0;
		}
		$this->username = $username;
		$this->uid = $uid;
		$this->databaseconnection = $databaseconnection;
	}

	/**
	 * 数据库查询
	 *
	 * @param array $join 多表查询,不使用可以忽略
	 * @param string|array $columns 要查询的字段名
	 * @param array $where  查询的条件
	 *
	 * @return array
	 */
	public function select($join, $columns = null, $where = null) {
		return $this->databaseconnection->select($this->tablename, $join, $columns, $where);
	}

	/**
	 * 添加数据
	 * @param  array $datas 需要插入的数据
	 * 
	 * @return number
	 */
	public function insert($datas) {
		return $this->databaseconnection->insert($this->tablename, $datas);
	}
}
