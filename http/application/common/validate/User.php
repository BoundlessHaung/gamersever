<?php
namespace app\common\validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
        'username'  => 'require|length:4,12|regex:/^\w+$/',  // 用户名
        'password'  => 'require|length:6,16|regex:/^[\w\@\!\#\$\%\^\&\*\.\~\-\+\=\{\}\[\]\:\;\"\\\'\\\\<\,\>\?\/]+$/'   // 物品名称
    ];
    protected $message = [
        'username.require'  => '必须填写用户名',
        'username.length'   => '用户名只能在4~12个字符之间',
        'username.regex'    => '用户名只能使用字母、数字及下划线',
        'password.require'  => '必须填写密码',
        'password.length'   => '密码只能在6~16个字符之间',
        'password.regex'    => '密码只能使用字母、数字、下划线及特殊字符',
    ];
}