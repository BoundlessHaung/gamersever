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
	 * @param  array $join 多表查询,不使用可以忽略
	 * @param  string|array $columns 要查询的字段名
	 * @param  array $where  查询的条件
	 *
	 * @return array
	 */
	public function select($join, $columns = null, $where = null) {
		return $this->databaseconnection->select($this->tablename, $join, $columns, $where);
	}

	/**
	 * 添加数据
	 *
	 * @param  array $datas 需要插入的数据
	 *
	 * @return number        插入的id
	 */
	public function insert($datas) {
		return $this->databaseconnection->insert($this->tablename, $datas);
	}


	/**
	 * 修改表数据
	 *
	 * @param  array $data  修改的数据
	 * @param  array $where WHERE 条件[可选]
	 *
	 * @return number        受影响的行数
	 */
	public function update($data, $where = null) {
		return $this->databaseconnection->update($this->tablename, $data, $where);
	}

	/**
	 * 删除表中的数据（危险操作慎用）
	 *
	 * @param  array $where WHERE 删除条件
	 *
	 * @return number        返回被删除的行数
	 */
	public function delete($where) {
		return $this->databaseconnection->delete($this->tablename, $where);
	}

	/**
	 * 将新的数据替换旧的数据
	 *
	 * @param  string|array $columns 会被影响的目标列
	 * @param  string $search  需要被替换的字符串
	 * @param  string $replace 替换后的值
	 * @param  array $where   WHERE 条件
	 *
	 * @return number          返回受影响的行数
	 */
	public function replace($columns, $search = null, $replace = null, $where = null) {
		return $this->databaseconnection->replace($this->tablename, $columns, $search, $replace, $where);
	}

	/**
	 * 从表中返回一行数据
	 *
	 * @param  array $join 多表查询,不使用可以忽略
	 * @param  string|array $columns 返回的字段列
	 * @param  array $where   WHERE 条件
	 *
	 * @return string|array          返回查询到的数据
	 */
	public function get($join = null, $columns = null, $where = null) {
		return $this->databaseconnection->get($this->tablename, $join, $columns, $where);
	}

	/**
	 * 确定数据是否存在
	 *
	 * @param  array $join 多表查询,不使用可以忽略
	 * @param  array $where   WHERE 条件
	 *
	 * @return boolean
	 */
	public function has($join, $where = null) {
		return $this->databaseconnection->has($this->tablename, $join, $where);
	}

	/**
	 * 获取数据表中的行数
	 *
	 * @param  array $join 多表查询,不使用可以忽略
	 * @param  string $column 需要统计的字段
	 * @param  array $where   WHERE 条件
	 *
	 * @return number         行数
	 */
	public function count($join = null, $column = null, $where = null) {
		return $this->databaseconnection->count($this->tablename, $join, $column, $where);
	}

	/**
	 * 获得数据表中，值最大的
	 *
	 * @param  array $join 多表查询,不使用可以忽略
	 * @param  string $column 字段名
	 * @param  array $where   WHERE 条件
	 *
	 * @return number         返回最大的值
	 */
	public function max($join, $column = null, $where = null) {
		return $this->databaseconnection->max($this->tablename, $join, $column, $where);
	}

	/**
	 * 获得数据表中，值最小的
	 *
	 * @param  array $join 多表查询,不使用可以忽略
	 * @param  string $column 字段名
	 * @param  array $where   WHERE 条件
	 *
	 * @return number         返回最小的值
	 */
	public function min($join, $column = null, $where = null) {
		return $this->databaseconnection->min($this->tablename, $join, $column, $where);
	}

	/**
	 * 获得某个列字段的平均值
	 *
	 * @param  array $join 多表查询,不使用可以忽略
	 * @param  string $column 字段名
	 * @param  array $where   WHERE 条件
	 *
	 * @return number         平均值
	 */
	public function avg($join, $column = null, $where = null) {
		return $this->databaseconnection->avg($this->tablename, $join, $column, $where);
	}

	/**
	 * 某个列字段相加
	 *
	 * @param  array $join 多表查询,不使用可以忽略
	 * @param  string $column 字段名
	 * @param  array $where   WHERE 条件
	 *
	 * @return number         相加后的值
	 */
	public function sum($join, $column = null, $where = null) {
		return $this->databaseconnection->sum($this->tablename, $join, $column, $where);
	}

	/**
	 * 启动一个事务
	 *
	 * @param  function $actions 事务内执行的方法
	 *
	 * @return void
	 */
	public function action($actions) {
		$this->databaseconnection->action($actions);
	}

	/**
	 * 执行sql语句
	 *
	 * @param  string $query SQL语句
	 *
	 * @return object        PDOStatement对象
	 */
	public function query($query) {
		return $this->databaseconnection->query($query);
	}

	/**
	 * 字符串转义
	 *
	 * @param  string $string 字符串
	 *
	 * @return string
	 */
	public function quote($string) {
		return $this->databaseconnection->quote($string);
	}

	/**
	 * PDO
	 * http://php.net/manual/en/class.pdo.php
	 *
	 * @return object
	 */
	public function pdo() {
		return $this->databaseconnection->pdo;
	}

	/**
	 * 输入sql语句，但不执行
	 *
	 * @return 开启Medoo调试模式
	 */
	public function debug() {
		return $this->databaseconnection->debug();
	}

	/**
	 * 返回错误的数组代码
	 *
	 * @return array
	 */
	public function error() {
		return $this->databaseconnection->error();
	}

	/**
	 * 返回所有执行的查询
	 *
	 * @return array
	 */
	public function log() {
		return $this->databaseconnection->log();
	}

	/**
	 * 返回最后一条执行的SQL语句
	 *
	 * @return string
	 */
	public function last_query() {
		return $this->databaseconnection->last_query();
	}

	/**
	 * 获得数据库的信息
	 *
	 * @return string
	 */
	public function info() {
		return $this->databaseconnection->info();
	}
}
