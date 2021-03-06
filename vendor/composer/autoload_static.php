<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbd0d821dfb92e018e1539fe3b34d395b
{
    public static $files = array (
        '9b552a3cc426e3287cc811caefa3cf53' => __DIR__ . '/..' . '/topthink/think-helper/src/helper.php',
        'c7359326b6707d98bdc176bf9ddeaebf' => __DIR__ . '/..' . '/catfan/medoo/medoo.php',
    );

    public static $prefixLengthsPsr4 = array (
        't' => 
        array (
            'think\\helper\\' => 13,
        ),
        'W' => 
        array (
            'Workerman\\' => 10,
        ),
        'P' => 
        array (
            'Predis\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'think\\helper\\' => 
        array (
            0 => __DIR__ . '/..' . '/topthink/think-helper/src',
        ),
        'Workerman\\' => 
        array (
            0 => __DIR__ . '/..' . '/workerman/workerman',
        ),
        'Predis\\' => 
        array (
            0 => __DIR__ . '/..' . '/predis/predis/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbd0d821dfb92e018e1539fe3b34d395b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbd0d821dfb92e018e1539fe3b34d395b::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
