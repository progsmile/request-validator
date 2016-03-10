<?php

use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Di;
use Phalcon\Di\FactoryDefault;

error_reporting(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if (extension_loaded('phalcon')) {
    $di = new FactoryDefault();

    Di::reset();

    $di->set('db', function () {
        return new Mysql(
            require(__DIR__.'/config/database.php')
        );
    });

    Di::setDefault($di);
}

if (!function_exists('dd')) {
    function dd()
    {
        var_dump(func_get_args());
        ob_flush();
    }
}

require __DIR__.'/../vendor/autoload.php';
