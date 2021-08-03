<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit301a4dfed3a94792bb4b0ad3a95926a7
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'TeemIp\\TeemIp\\Extension\\NewsroomProvider\\' => 41,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'TeemIp\\TeemIp\\Extension\\NewsroomProvider\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'TeemIp\\TeemIp\\Extension\\NewsroomProvider\\Controller\\TeemIpNewsroomProvider' => __DIR__ . '/../..' . '/src/Controller/TeemIpNewsroomProvider.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit301a4dfed3a94792bb4b0ad3a95926a7::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit301a4dfed3a94792bb4b0ad3a95926a7::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit301a4dfed3a94792bb4b0ad3a95926a7::$classMap;

        }, null, ClassLoader::class);
    }
}
