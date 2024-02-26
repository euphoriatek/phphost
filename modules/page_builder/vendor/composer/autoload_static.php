<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0c1dc0132dccd692e6a8d48f18dd49fc
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Component\\HtmlSanitizer\\' => 32,
        ),
        'P' => 
        array (
            'Psr\\Http\\Message\\' => 17,
        ),
        'M' => 
        array (
            'Masterminds\\' => 12,
        ),
        'L' => 
        array (
            'League\\Uri\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Component\\HtmlSanitizer\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/html-sanitizer',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'Masterminds\\' => 
        array (
            0 => __DIR__ . '/..' . '/masterminds/html5/src',
        ),
        'League\\Uri\\' => 
        array (
            0 => __DIR__ . '/..' . '/league/uri-interfaces/src',
            1 => __DIR__ . '/..' . '/league/uri/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0c1dc0132dccd692e6a8d48f18dd49fc::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0c1dc0132dccd692e6a8d48f18dd49fc::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit0c1dc0132dccd692e6a8d48f18dd49fc::$classMap;

        }, null, ClassLoader::class);
    }
}
