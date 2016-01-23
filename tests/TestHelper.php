<?php

use Phalcon\Di;
use Phalcon\Di\FactoryDefault;

ini_set('display_errors',1);
error_reporting(E_ALL);

define('ROOT_PATH', __DIR__);
define('PATH_LIBRARY', __DIR__ . '/../app/library/');
define('PATH_SERVICES', __DIR__ . '/../app/services/');
define('PATH_RESOURCES', __DIR__ . '/../app/resources/');

set_include_path(ROOT_PATH . PATH_SEPARATOR . get_include_path());

include __DIR__ . "/../vendor/autoload.php";

$loader = new \Phalcon\Loader();

$loader->registerDirs([ROOT_PATH]);

$loader->register();

$di = new FactoryDefault();

Di::reset();

$di->set('db', function(){

    return new Phalcon\Db\Adapter\Pdo\Mysql([
            "host"     => "localhost",
            "username" => "root",
            "password" => "123",
            "dbname"   => "valid",
        ]
    );
});

Di::setDefault($di);
