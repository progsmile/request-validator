# Rules Database

Some of the existing rules requires to connect via database to check some table's column or the value, etc.


### Connect with PDO or use built-in Data Providers (just for unique and exists rules)

```php
<?php

use Progsmile\Validator\Validator as v;
use Progsmile\Validator\DbProviders\Wpdb;       // Wordpress
use Progsmile\Validator\DbProviders\PhalconORM; // Phalcon

# to set the database adapter to use
v::setDataProvider(PhalconORM::class);
v::setDataProvider(Wpdb::class);

# or if the driver supports PDO
v::setupPDO('mysql:host=localhost;dbname=valid', 'root', '123');

# or an alternative, let as say we have an instance of PDO class
$pdo = $this->getPdoInstance();
v::setPDO($pdo);

$validation = v::make($_POST, [
    'email'           => 'required|email|unique:users'
    'password'        => 'min:6',
    'password_repeat' => 'same:password',
    'json'            => 'json',
]);

```
