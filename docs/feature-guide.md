# Validator Feature Guide

## Namespace import
```php
use Progsmile\Validator\Validator;
```


## Getting messages
```php
$validation = Validator::make($_POST,[
   'phone'    => 'required|phoneMask:(+38(###)###-##-##)',
   'email'    => 'required|email',
   'age'      => 'numeric|required|min:16',
   'homepage' => 'url'
]);


$validation->messages(); //all messages


$validation->messages('age'); //all messages for age field

$validation->age->messages(); //the same as above


$validation->firsts(); // returns one error message from each invalid rule


$validation->first(); // returns first non-valid message

$validation->first('homepage'); // returns first non-valid message for specific field

$validation->phone->first(); //first error message from field `phone`
```

## Grouping fields rules
Just put comma after each field
```php
$validation = Validator::make($_POST,[
   'firstname, lastname, email' => 'required',
   'firstname, lastname'        => 'alpha|min:2',
   'email'                      => 'email'
]);

```

## Simple check :)
```php

$validation->fails() or $validation->passes()

$validation->firstname->fails() or $validation->firstname->passes() // `firstname` is field name
```


## Array validation support
Simple as usual variables
```php
$validation = Validator::make([
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
$validation = Validator::make($_POST,[
   'firstname' => 'required|alpha',
], [
   'firstname.required' => ':field: is required',
   'firstname.alpha'    => 'Please write your real name instead of :value:'
]);

```

## Validator has messages by default
```php
$validation = Validator::make([],[
   'age' => 'numeric|required',
]);

echo $validation->first(); //Field age is not a number
```



## Formatting messages
Supports HTML and Json formats from the box
```php
use \Progsmile\Validator\Format\Json;

echo $validation->format(); // HTML format

echo $validation->format(Json::class); // Json format

```
