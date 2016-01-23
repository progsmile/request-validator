<?php

use \Progsmile\Validator\Validator;


include __DIR__ . '/../vendor/autoload.php';


class ValidatorTest extends PHPUnit_Framework_TestCase
{
    private $postData;
    private $nonUniqueEmail;

    public static function dd($var)
    {
        var_dump($var);
        ob_flush();
    }

    public function setUp()
    {
        $this->postData = [
            'firstname'       => 'Denis',
            'lastname'        => 'Klimenko',
            'email'           => 'denis.klimenko.dx@gmail.com',
            'age'             => '21',
            'rule'            => 'on',
            'ip'              => '192.168.0.0',
            'password'        => '123456789',
            'password_repeat' => '123456789',
            'json'            => '[]',
            'randNum'         => rand(1, 100),
            'site'            => 'https://github.com/progsmile/request-validator',
        ];

        $this->nonUniqueEmail = 'dd@dd.dd';
    }

    /** @test */
    public function testValidationOK()
    {
        $validationResult = Validator::make($this->postData, [
            'firstname'       => 'alpha|min:2',
            'lastname'        => 'alpha|min:2|max:18',
            'email'           => 'email|unique:users',
            'age'             => 'min:16|numeric',
            'rule'            => 'accepted',
            'randNum'         => 'between:1, 100',
            'ip'              => 'ip',
            'password'        => 'required|min:6',
            'password_repeat' => 'same:password',
            'json'            => 'json',
            'site'            => 'url',
        ]);


//        self::dd($validationResult->getMessages());

        $this->assertEmpty($validationResult->getMessages());
    }

    /** @test */
    public function testNonUniqueError()
    {
        $validationResult = Validator::make(['email' => $this->nonUniqueEmail], [
            'email' => 'unique:users',
        ], [
            'email.unique' => 'nonunique',
        ]);

        $this->assertFalse($validationResult->isValid());

        $errorMessage = $validationResult->getMessages('email');

        $this->assertEquals('nonunique', reset($errorMessage));
    }

    /** @test */
    public function testNumeric()
    {
        $validationResult = Validator::make([
            'n1' => '100',
            'n2' => '1asdasd',
            'n3' => 'sd1asdasd',
        ], [
            'n1' => 'numeric',
            'n2' => 'numeric',
            'n3' => 'numeric',
        ]);

//        self::dd($validationResult->getMessages());

        $this->assertCount(2, $validationResult->getMessages());
    }
}
