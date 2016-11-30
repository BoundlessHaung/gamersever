<?php
namespace app\api\controller;

use think\Validate;
use app\common\model\User as Users;
use think\helper\Str;
use think\helper\Hash;

class User
{
    public function index()
    {
        return "Manor: chaos";
    }

    /**
     *	注册流程
     */
    public function signUp()
    {
    	// 判断所需参数是否全部存在，缺失则结束
    	if (!isset($_POST['username']) || !isset($_POST['password'])) {
    		return json([
                'status' => 0,
                'msg' => '传入参数错误'
            ]);
    	}
    	// 获取参数存入data
    	$data['username'] = $_POST['username'];
    	$data['password'] = $_POST['password'];
        $data['mail'] = "none";
        $data['lastsigninip'] = "none";
    	// 验证数据，不通过则结束并返回信息
    	$UserValidate = validate('User');
    	if (!$UserValidate->check($data)) {
            return json([
                'status' => 0,
                'msg' => $UserValidate->getError()
            ]);
        }
        // 获取IP地址
    	$data['singupip'] = $_SERVER["REMOTE_ADDR"];
    	// 生成盐值
    	$data['salt'] = Str::random(4);
    	// 加密密码
    	$data['password'] = Hash::make($data['password'], 'Md5', ['salt'  =>  $data['salt']]);

    	// 过滤数据并存入数据库
        $UserModel = new Users($data);
        $isin = $UserModel->allowField(true)->save();
        // $uid =  $UserModel->uid;
        // 判断是否添加成功
        if ($isin) {
            return json([
                'status' => 1,
                'msg' => '注册成功'
            ]);
        } else {
            return json([
                'status' => 0,
                'msg' => '注册失败'
            ]);
        }
    }
}
