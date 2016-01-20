<?php
namespace Progsmile\Validator;

use Progsmile\Validator\DbProviders\PhalconORM;

include_once 'vendor/autoload.php';

//Validator::setDbProvider(\Progsmile\Validator\DbProviders\WordPress\Wpdb::class);

Validator::setDbProvider(PhalconORM::class);

$validator = Validator::make($_POST, //here your data
    [
        'firstname'       => 'required|alpha|min:2',
        'lastname'        => 'required|alpha|min:2|max:18',
        'email'           => 'required|email|unique:users',
        'age'             => 'min:16|numeric',
        'rule'            => 'accepted',
        'ip'              => 'required|ip',
        'password'        => 'min:6',
        'password_repeat' => 'same:password',
        'json'            => 'json',
        'myImage'         => 'image',
    ], [
        'email.required' => 'Please, enter your email!',
        'email.unique'   => 'Choose another email, somebody uses it)',
        'json.json'      => 'It\'s not json man!',
    ]
);


echo 'Is Valid: ';
var_dump($validator->isValid());

echo 'Array Message: ';
var_dump($validator->getMessages());

echo 'Pass field for getting messages: ';
var_dump($validator->getMessages('email'));

echo 'HTML Message: ';
var_dump($validator->format());

echo 'JSON Message: ';
var_dump($validator->format(\Progsmile\Validator\Format\Json::class));