<?php

use Phalcon\Di;
use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql;

error_reporting(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include_once __DIR__ . "/../vendor/autoload.php";

$di = new FactoryDefault();

Di::reset();

$di->set('db', function(){
    return new Mysql([
            "host"     => "localhost",
            "username" => "root",
            "password" => "",
            "dbname"   => "valid",
        ]
    );
});

Di::setDefault($di);

if (!function_exists('dd')) {
    function dd($var) {
        var_dump($var);
        ob_flush();
    }
}
