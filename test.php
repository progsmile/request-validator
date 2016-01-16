<?php
namespace Progsmile\Validator;

include_once 'vendor/autoload.php';

$validator = (new Validator)->make(
    [
        'first_name' => 'Daison Pascual Carino',
        'password'   => 'abcde',
        'email'      => 'daison12006013@gmail.com',
//        'age'        => 22,
//        'rule'       => 'On',
    ],
    [
        'first_name' => 'max:20',
        'password'   => 'min:8', //for strings
        'age'        => 'required|min:16', //for numbers
        'email'      => 'required|email|unique:users',
        'rule'       => 'accepted',
    ]
);

echo 'Is Valid: ';
var_dump($validator->isValid());

echo 'Array Message: ';
var_dump($validator->messages());

echo 'HTML Message: ';
var_dump($validator->format());

echo 'JSON Message: ';
var_dump($validator->format(\Progsmile\Validator\Format\Json::class));