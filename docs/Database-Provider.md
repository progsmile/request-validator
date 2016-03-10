### Connect with PDO or use built-in Data Providers (just for unique and exists rules)

```php
// Connect once - use everywhere

use Progsmile\Validator\Validator as V;
use Progsmile\Validator\DbProviders\PhalconORM; //Phalcon
use Progsmile\Validator\DbProviders\Wpdb;       //Wordpress

V::setDataProvider(PhalconORM::class);

//or
V::setDataProvider(Wpdb::class);

//or
V::setupPDO('mysql:host=localhost;dbname=valid', 'root', '123');

//or
$pdo = $this->getPdoInstance(); //should be instance of PDO class

V::setPDO($pdo);


$v = V::make($this->request->getPost(), [
    'email'           => 'required|email|unique:users' //users - table name
    'password'        => 'min:6',
    'password_repeat' => 'same:password',
    'json'            => 'json'
]);

```
