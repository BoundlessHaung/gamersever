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

use server\Controller;
use server\model\character;

/**
 * 角色控制类
 *
 * @author Boundless <george.haung@sandboxcn.com>
 */
class player extends Controller
{

	/**
	 * 创建角色
	 * 
	 * @return
	 */
	public function creatCharacter() {
		if (!isset($this->clientMsg['type']) || !isset($this->clientMsg['charactername'])) {
			return $this->sendMsgToClient([
				'type' => 'creatCharacter',
				'status' => 0,
				'msg' => '数据格式错误'
			]);
		}
		if ($this->hasSignIn()) {
			echo "IP：" . $this->connection->getRemoteIp() . " type：" . $this->clientMsg['type'] . " charactername：" . $this->clientMsg['charactername'] . "\n";

			// 判断时候满足创建角色的条件
			$charactermodel = new character($this->connection->username, $this->connection->uid, $this->server->database);
			if (count($charactermodel->getCharacter()) > 0) {
				$this->errorMsg = "该用户已经创建过角色";
				$this->status = 2;
			} else if (!preg_match("/^[\w\x4e00-\x9fa5]{2,12}$/", $this->clientMsg['charactername'])) {
				$this->errorMsg = "角色名称只能由中文、数字、字母、下划线组成，并且在2到12个字符之间";
				$this->status = 3;
			} else if (count($charactermodel->getCharacterByCharacterName($this->clientMsg['charactername'])) > 0) {
				$this->errorMsg = "该角色名称已经存在";
				$this->status = 4;
			} else {
				// 能活着走到这里那就可以创建角色了
				$characterdata = [
					"charactername" => $this->clientMsg['charactername'],
					"strength" => rand($this->server->config["basic_attribute_threshold"]['strength'][0], $this->server->config["basic_attribute_threshold"]['strength'][1]),
					"constitution" => rand($this->server->config["basic_attribute_threshold"]['constitution'][0], $this->server->config["basic_attribute_threshold"]['constitution'][1]),
					"willpower" => rand($this->server->config["basic_attribute_threshold"]['willpower'][0], $this->server->config["basic_attribute_threshold"]['willpower'][1]),
					"intelligence" => rand($this->server->config["basic_attribute_threshold"]['intelligence'][0], $this->server->config["basic_attribute_threshold"]['intelligence'][1]),
					"agility" => rand($this->server->config["basic_attribute_threshold"]['agility'][0], $this->server->config["basic_attribute_threshold"]['agility'][1]),
					"leadership" => $this->server->config['leadership'],
					"wordx" => rand(0, $this->server->config['wordx']),
					"wordy" => rand(0, $this->server->config['wordy'])
				];
				if ($charactermodel->insertCharacter($characterdata)) {
					$this->errorMsg = "角色创建成功";
					$this->status = 1;
					echo "IP：" . $this->connection->getRemoteIp() . " 登录用户：" . $this->connection->username . " 创建了角色名为：" . $this->clientMsg['charactername'] . " 的角色\n";
				} else {
					$this->errorMsg = "未知错误";
					$this->status = 5;
				}
			}
		} else {
			$this->errorMsg = "非法操作，没有登录";
			$this->status = 10001;
		}
		
		return $this->sendMsgToClient([
			'type' => 'creatCharacter',
			'status' => $this->status,
			'msg' => $this->errorMsg
		]);
	}

	/**
	 * 角色的移动
	 * @return [type] [description]
	 */
	public function characterMove() {
		if (!isset($this->clientMsg['wordx']) || !isset($this->clientMsg['wordy'])) {
			return $this->sendMsgToClient([
				'type' => 'characterMove',
				'status' => 0,
				'msg' => '数据格式错误'
			]);
		}
		
	}

}
