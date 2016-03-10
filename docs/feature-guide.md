# Welcome to Validator Feature Guide

## Namespace import
```php
use Progsmile\Validator\Validator as V;
```


## Getting messages
```php
$v = V::make($_POST,[
   'phone'    => 'required|phoneMask:(+38(###)###-##-##)'
   'email'    => 'required|email|unique:users',
   'age'      => 'numeric|required|min:16',
   'homepage' => 'url',
]);


$v->messages(); //returns all messages


$v->messages('age'); //returns all messages for age field

$v->age->messages(); //the same


$v->firsts(); // returns one error message from each invalid rule


$v->first(); // returns first non-valid message

$v->first('homepage'); // returns first non-valid message for specific field

$v->phone->first(); //first error message from field `phone`
```

## Grouping fields rules
Just put comma after each field
```php
$v = V::make($_POST,[
   'firstname, lastname, email' => 'required',
   'firstname, lastname'        => 'alpha|min:2',
   'email'                      => 'email'
]);

```

## Simple check :)
```php

$v->fails() or $v->passes()

$v->firstname->fails() or $v->firstname->passes() // `firstname` is field name
```


## Array validation support
Simple as usual variables
```php
$v = V::make([
    'info'      => ['phone' => '+380987365432', 'country' => 'Albania'],
    'test'      => [10, 20, 30, 'fail' => 40],
], [
    'info[phone]'               => 'required|phoneMask:(+380#########)',
    'info[country]'             => 'required|alpha',
    'roll[0], roll[1], roll[2]' => 'numeric|between:1, 100',
    'test[fail]'                => 'required|equals:41'
], [
    'test[fail].equals' => '40 need'
]);
```



## Adding own messages
Pass 3rd array to Validator make method for your messages
Available variables: **:field:**, **:value:**, **:param:** in messages
```php
$v = V::make($_POST,[
   'firstname' => 'required|alpha',
], [
   'firstname.required' => ':field: is required'
   'firstname.alpha'    => 'Please write your real name instead of :value:'
]);

```

## Validator has messages by default
```php
$v = V::make([],[
   'age' => 'numeric|required',
]);

echo $v->first(); //Field age is not a number
```



## Formatting messages
Supports HTML and Json formats from the box
```php
use \Progsmile\Validator\Format\Json;

echo $v->format(); // HTML format

echo $v->format(Json::class); // Json format

```

## Using unique and exists rules
Such rules requires database connection, several ways to achieve that

### Using PDO
Simple create new PDO connection, or pass ready PDO instance

```php
V::setupPDO('mysql:host=localhost;dbname=valid;charset=utf8', 'root', '123')

//or

$pdo = ServiceContainer::getMyPDOObject();
V::setPDO($pdo);

```

### Using built-in ORMs

```php
use \Progsmile\Validator\DbProviders\PhalconORM; //Phalcon ORM is by default
use \Progsmile\Validator\DbProviders\Wpdb;

V::setDataProvider(PhalconORM::Wpdb);
```

## Checking up table values
```php

$v = V::make([
    'id'    => '1',
    'email' => 'this.email@unique.com',
], [
    'id'    => 'required|numeric|exists:products', // id - table attribute, products - table
    'email' => 'required|email|unique:users',      // email - table attr, users - table
]);

```
