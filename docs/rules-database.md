# Rules Database

Some of the existing rules requires to connect via database to check some table's column or the value, etc.

Rules who uses this:
- Unique
- Exists

```php
<?php

use Progsmile\Validator\Validator as v;
use Progsmile\Validator\DbProviders\PdoAdapter;        // PDO
use Progsmile\Validator\DbProviders\PhalconAdapter;    // Phalcon
use Progsmile\Validator\DbProviders\WordpressAdapter;  // Wordpress

# to set the data adapter
v::setDataProvider(PdoAdapter::class);
v::setDataProvider(PhalconAdapter::class);
v::setDataProvider(WordpressAdapter::class);

# Note: if you're using PDO data provider, you must set it up
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
