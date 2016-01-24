# PHP Request Validator

## Usage
```php
# Add namespace
use \Progsmile\Validator\Validator as V;

# Create new Validator, pass data, define rules and custom messages
# Also has errors messages by default
$validator = V::make($_POST, [
    'firstname, lastname' => 'required|alpha|min:2',
    'lastname'            => 'max:18',
    'email'               => 'email|unique:users', //table in db
    'age'                 => 'min:16|numeric',
    'date'                => 'dateFormat:(m-Y.d H:i)', //any format you set
    'phoneMask'           => 'phoneMask:(+38(###)###-##-##)',
    'rule'                => 'accepted',
    'randNum'             => 'between:1, 100',
    'ip'                  => 'ip',
    'password'            => 'required|min:6',
    'password_repeat'     => 'same:password',
    'json'                => 'json',
    'site'                => 'url',
    'cash10, cash25'      => 'in:1, 2, 5, 10, 20, 50, 100, 200, 500',
    'elevatorFloor'       => 'notIn:13'
], [
   'email.required'      => 'Email is required',
   'email.email'         => 'Email has bad format',
   'email.unique'        => 'Email is not unique',
   'elevatorFloor.notIn' => 'Oops',
]);
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


## Install
Coming soon on composer :)


### Advanced Usage
----

#### Connect with PDO or use build in Data Providers **just for unique rule**

```php

** Connect once - use everywhere **

use Progsmile\Validator\DbProviders\PhalconORM; //Phalcon
use Progsmile\Validator\DbProviders\Wpdb;       //Wordpress

V::setDataProvider(PhalconORM::class);

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
    # ... some code here...
);

echo $validator->format(MarkdownFormatter::class);
```

----

Project is just started and it is not stable yet, we love to have your fork requests.
