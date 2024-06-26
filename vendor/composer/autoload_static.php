<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5de0f4ce1169823f3d01f7df9f7075fd
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Services\\' => 9,
        ),
        'R' => 
        array (
            'Router\\' => 7,
        ),
        'M' => 
        array (
            'Models\\' => 7,
        ),
        'D' => 
        array (
            'Database\\' => 9,
        ),
        'C' => 
        array (
            'Components\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Services\\' => 
        array (
            0 => __DIR__ . '/../..' . '/services',
        ),
        'Router\\' => 
        array (
            0 => __DIR__ . '/../..' . '/routes',
        ),
        'Models\\' => 
        array (
            0 => __DIR__ . '/../..' . '/models',
        ),
        'Database\\' => 
        array (
            0 => __DIR__ . '/../..' . '/database',
        ),
        'Components\\' => 
        array (
            0 => __DIR__ . '/../..' . '/components',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5de0f4ce1169823f3d01f7df9f7075fd::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5de0f4ce1169823f3d01f7df9f7075fd::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit5de0f4ce1169823f3d01f7df9f7075fd::$classMap;

        }, null, ClassLoader::class);
    }
}
