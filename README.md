# PHP Request Validator
[![Build Status](https://travis-ci.org/progsmile/request-validator.svg?branch=master)](http://travis-ci.org/progsmile/request-validator) [![Monthly Downloads](https://poser.pugx.org/progsmile/request-validator/d/monthly)](https://packagist.org/packages/progsmile/request-validator) [![License](https://poser.pugx.org/progsmile/request-validator/license.svg)](https://packagist.org/packages/progsmile/request-validator) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/918ec166-799d-4ac1-a2c9-13d4cb8dafd4/mini.png)](https://insight.sensiolabs.com/projects/918ec166-799d-4ac1-a2c9-13d4cb8dafd4)

## Usage

```php
// Add namespace
use \Progsmile\Validator\Validator as V;

// Create new Validator, pass data, define rules and custom messages
// Also has errors messages by default
$validator = V::make($_POST, [

    //group validation fields
    'firstname, lastname' => 'required|alpha|min:2',         //alphabetic support
    'lastname'            => 'max:18',                       //string max length
    'email'               => 'email|unique:users',           //email uniqueness
    'age'                 => 'min:16|numeric',               //numeric min
    'date'                => 'dateFormat:(m-Y.d H:i)',       //custom date time format
    'profileImg'          => 'image',                        //image
    'phoneMask'           => 'phoneMask:(+38(###)###-##-##)',//custom phone mask validator
    'rule'                => 'accepted',                     //checkboxes acception
    'randNum'             => 'between:1, 100',               //value between
    'ip'                  => 'ip',                           //ipv4 or ipv6
    'password'            => 'required|min:6',               //required fields
    'password_repeat'     => 'same:password',                //same validator
    'json'                => 'json',                         //json format
    'site'                => 'url',                          //url format
    'cash10, cash25'      => 'in:1, 2, 5, 10, 20, 50',       //in array
    'elevatorFloor'       => 'notIn:13'                      //not in array
], [
   'email.required'      => 'Field :field: is required',     //Add custom messages
   'email.email'         => 'Email has bad format :value:',  //Support :field: and :value: params
   'email.unique'        => 'Email is not unique',
   'elevatorFloor.notIn' => 'Oops',
]);
```

## Installation

### Installing via Composer

Install [Composer](http://getcomposer.org) in a common location or in your project:

```sh
$ curl -s http://getcomposer.org/installer | php
```

Create the `composer.json` file as follows:

```json
{
    "require": {
        "progsmile/request-validator": "@dev"
    }
}
```

Run the Composer installer:

```sh
$ php composer.phar install
```

## Available rules
- [x]  accepted
- [x]  alpha
- [x]  between
- [x]  boolean
- [x]  dateFormat
- [x]  email
- [x]  json
- [x]  in
- [x]  image
- [x]  ip
- [x]  max
- [x]  min
- [x]  notIn
- [x]  numeric
- [x]  phoneMask
- [x]  required
- [x]  same
- [x]  unique (db provider required)
- [x]  url



### Connect with PDO or use built-in Data Providers (just for unique rule)

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


$validator = V::make($this->request->getPost(), [
    'email'           => 'required|email|unique:users' //users - table name
    'password'        => 'min:6',
    'password_repeat' => 'same:password',
    'json'            => 'json'
]);

```

#### Formatting - the best way to auto-reformat the returned array into your own style

The `$validator->format()`, by default, the messages will be formatted to html `<ul><li></li>...</ul>` element.

You can create your own class to format the array `$validator->messages()` into a well formed result.

```php
use Progsmile\Validator\Contracts\Format\FormatInterface;

class MarkdownFormatter implements FormatInterface
{
    public function reformat($messages)
    {
        $ret = '#### Error Found';

        foreach ($messages as $field => $message) {

            foreach ($message as $content) {

                $ret .= "- [x] ".$content."**\n"
            }
        }

        return $ret;
    }
}
```

Then in to use this call, you must do this way:

```php
$validator = V::make(
    // ... some code here...
);

echo $validator->format(MarkdownFormatter::class);
```


## Dear contributors

Project is just started and it is not stable yet, we love to have your fork requests

**For testing**

A MySQL database is also required for several tests. Follow these instructions to create the database:

```sh
echo 'create database valid charset=utf8mb4 collate=utf8mb4_unicode_ci;' | mysql -u root
cat tests/schema.sql | mysql valid -u root

```

For these tests we use the user `root` without a password. You may need to change this in `tests/TestHelper.php` file.

## License

PHP Request Validator is open-sourced software licensed under the [GNU GPL](LICENSE).
© 2016 Denis K
