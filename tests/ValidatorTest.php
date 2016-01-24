<?php

use \Progsmile\Validator\Validator;


include __DIR__ . '/../vendor/autoload.php';


//little test helper
function dd($var)
{
    var_dump($var);
    ob_flush();
}

class ValidatorTest extends PHPUnit_Framework_TestCase
{
    private $postData;
    private $nonUniqueEmail;

    public function setUp()
    {
        $this->postData = [
            'firstname'       => 'Denis',
            'lastname'        => 'Klimenko',
            'email'           => 'denis.klimenko.dx@gmail.com',
            'age'             => '21',
            'date'            => '12-2013.01 23:32',
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
            'firstname, lastname' => 'required|alpha|min:2',
            'lastname'            => 'max:18',
            'email'               => 'email|unique:users',
            'age'                 => 'min:16|numeric',
            'date'                => 'dateFormat:(m-Y.d H:i)',
            'rule'                => 'accepted',
            'randNum'             => 'between:1, 100',
            'ip'                  => 'ip',
            'password'            => 'required|min:6',
            'password_repeat'     => 'same:password',
            'json'                => 'json',
            'site'                => 'url',
        ]);

        dd($validationResult->getMessages());

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

        dd($validationResult->getMessages());

        $this->assertCount(2, $validationResult->getMessages());
    }


    /** @test */
    public function testGroupedRules()
    {
        $validationResult = Validator::make([
            'n1'        => '111',
            'n2'        => '333',
            'n3'        => '7s7s7',
            'firstname' => 'Den1s',
            'lastname'  => 'Klimenko',
        ], [
            'n1, n2, n3'          => 'required|numeric',
            'firstname, lastname' => 'required|alpha',
        ], [
            'firstname.alpha' => 'Your real name needed)',
            'n3.numeric'      => 'N3 is not a number',
        ]);

        dd($validationResult->getMessages());

        $this->assertCount(2, $validationResult->getMessages());
    }


    /** @test */
    public function testPhoneMask()
    {
        $validationResult = Validator::make([
            'phone1' => '+38(097)123-45-67',
            'phone2' => '1-234-567-8901',
            'phone3' => '(020) 4420 7123',
        ], [
            'phone1' => 'phoneMask:(+38(###)###-##-##)',
            'phone2' => 'phoneMask:(#-###-###-####)',
            'phone3' => 'phoneMask:((020) xxxx xxxx)',
        ]);

        dd($validationResult->getMessages());
        $this->assertTrue($validationResult->isValid());
    }


    /** @test */
    public function testPDOClass()
    {
        Validator::setDbProvider(\Progsmile\Validator\DbProviders\DefaultPDO::class);

        $validationResult = Validator::make([
            'email' => $this->nonUniqueEmail,
        ], [
            'email' => 'required|email|unique:users',
        ]);

        dd($validationResult->getMessages());

        $this->assertTrue($validationResult->isValid());
    }


}
