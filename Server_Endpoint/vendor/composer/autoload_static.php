<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8eac170b56b5499a42734e9cccdfde24
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'Automattic\\WooCommerce\\' => 23,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Automattic\\WooCommerce\\' => 
        array (
            0 => __DIR__ . '/..' . '/automattic/woocommerce/src/WooCommerce',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8eac170b56b5499a42734e9cccdfde24::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8eac170b56b5499a42734e9cccdfde24::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
