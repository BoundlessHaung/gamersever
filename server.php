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

// [ 应用入口文件 ]

// 注册Composer依赖管理自动加载
require 'vendor/autoload.php';

// 注册自动加载
require 'Autoloader.php';
Autoloader::register();

// 加载服务器引导文件
require __DIR__ . '/server/start.php';
