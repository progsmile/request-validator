<?php
namespace Progsmile\Validator;

use Progsmile\Validator\Format\Json;

ini_set('display_errors', '1');
//include_once 'vendor/autoload.php';

$validator = (new Validator)->make([
        'amt' => 201,
    ],
    [
        'amt' => 'max:200',
    ]
);

echo 'Is Valid: ';
var_dump($validator->isValid());

echo 'Array Message: ';
var_dump($validator->messages());

echo 'HTML Message: ';
var_dump($validator->format());

echo 'JSON Message: ';
var_dump($validator->format(Json::class));
