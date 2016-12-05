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

/**
 * 轻量级 PSR-0 兼容自动加载机制
 *
 * @author Boundless <george.haung@sandboxcn.com>
 */
class Autoloader
{
    private $directory;

    /**
     * @param string $baseDirectory 基础目录
     */
    public function __construct($baseDirectory = __DIR__)
    {
        $this->directory = $baseDirectory;
    }

    /**
     * 注册自动加载
     *
     * @param bool $prepend 设定自动加载无法注册时是否抛异常
     */
    public static function register($prepend = false)
    {
        spl_autoload_register(array(new self(), 'autoload'), true, $prepend);
    }

    /**
     * 根据clsaaName加载文件
     *
     * @param string $className 类名
     */
    public function autoload($className)
    {
        $parts = explode('\\', $className);
        $filepath = $this->directory.DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR, $parts).'.php';
        if (is_file($filepath)) {
            require $filepath;
        }
    }
}
