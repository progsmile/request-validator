<?php

use Progsmile\Validator\Validator as V;

class ValidatorTest extends PHPUnit_Framework_TestCase
{
    private $postData;
    private $nonUniqueEmail;

    public function setUp()
    {
        try {
            V::setupPDO('mysql:host=localhost;dbname=valid;charset=utf8', 'root', '123');
        } catch (\Exception $e) {
            $this->markTestSkipped($e->getMessage());
        }

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

    public function testValidationOK()
    {
        $validationResult = V::make($this->postData, [
            'firstname, lastname' => 'required|alpha|min:2',
            'lastname'            => 'max:18',
            'email'               => 'email|unique:users',
            'age'                 => 'min:16|numeric',
            'date'                => 'dateFormat:(m-Y.d H:i)',
            'randNum'             => 'between:1, 100',
            'ip'                  => 'ip',
            'password'            => 'required|min:6',
            'password_repeat'     => 'same:password',
            'json'                => 'json',
            'site'                => 'url',
        ]);

        $this->assertEmpty($validationResult->getMessages());
    }

    public function testNonUniqueError()
    {
        $validationResult = V::make(['email' => $this->nonUniqueEmail], [
            'email' => 'unique:users',
        ], [
            'email.unique' => 'nonunique',
        ]);

        $this->assertFalse($validationResult->isValid());

        $errorMessage = $validationResult->getMessages('email');

        $this->assertEquals('nonunique', reset($errorMessage));
    }

    public function testNumeric()
    {
        $validationResult = V::make([
            'n1' => '100',
            'n2' => '1asdasd',
            'n3' => 'sd1asdasd',
        ], [
            'n1' => 'numeric',
            'n2' => 'numeric',
            'n3' => 'numeric',
        ]);

        $this->assertCount(2, $validationResult->getMessages());
    }

    public function testGroupedRules()
    {
        $validationResult = V::make([
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

        $this->assertCount(2, $validationResult->getMessages());
    }

    public function testRequiredRule()
    {
        $validationResult = V::make([
            'fieldZero'  => '0',
            'fieldSpace' => ' ',   //false
            'fieldEmpty' => '',    //false
            'fieldNull'  => null,  //false
            'fieldFalse' => 'false',
        ], [
            'fieldZero, fieldSpace, fieldEmpty, fieldFalse, fieldNull' => 'required',
        ]);

        $this->assertCount(3, $validationResult->getMessages());
    }

    public function testEquals()
    {
        $v = V::make([
            'greetings' => 'hello',
            'pinCode'   => '1111',
            'buy'       => '@!#@!',
        ], [
            'greetings' => 'equals:hello',
            'pinCode'   => 'equals:1111',
            'buy'       => 'equals:buy',
        ]);

        $this->assertFalse($v->isValid());
        $this->assertCount(1, $v->getMessages());
    }

    public function testInNotInValidator()
    {
        $validationResult = V::make([
            'cash10'        => '10',
            'cash25'        => '25',   //false
            'shop'          => 'Metro',
            'elevatorFloor' => '13',    //false
        ], [
            'cash10, cash25' => 'in:1, 2, 5, 10, 20, 50, 100, 200, 500',
            'shop'           => 'in:ATB, Billa, Metro',
            'elevatorFloor'  => 'notIn:13',

        ], [
            'elevatorFloor.notIn' => 'Oops',
        ]);

        $this->assertEquals('Oops', $validationResult->getFirstMessage('elevatorFloor'));
        $this->assertCount(2, $validationResult->getMessages());
    }

    public function testPhoneMask()
    {
        $validationResult = V::make([
            'phone1' => '+38(097)123-45-67',
            'phone2' => '1-234-567-8901',
            'phone3' => '(020) 4420 7123',
        ], [
            'phone1' => 'phoneMask:(+38(###)###-##-##)',
            'phone2' => 'phoneMask:(#-###-###-####)',
            'phone3' => 'phoneMask:((020) #### ####)',
        ]);

        $this->assertTrue($validationResult->isValid());
    }

    public function testExists()
    {
        $errorMessageIdExists = 'Such ID is not exist!';

        $validationResult = V::make([
            'email' => $this->nonUniqueEmail,
            'id'    => '9999999999999999',
        ], [
            'email' => 'required|email|exists:users',
            'id'    => 'required|numeric|exists:users',  //false
        ], [
            'id.exists' => $errorMessageIdExists,
        ]);

        $this->assertFalse($validationResult->isValid());
        $this->assertEquals($errorMessageIdExists, $validationResult->getFirstMessage('id'));
    }

    public function testAllMessagesMethods()
    {
        $v = V::make([], [
            'age'     => 'min:16|required',
            'date'    => 'dateFormat:(d-m-Y)|required',
            'randNum' => 'between:1, 100|required',
            'ip'      => 'ip|required',
            'email'   => 'email|required',
        ], [
            'age.required'   => 'age required',
            'age.min'        => 'min 10',
            'email.required' => 'email required',
            'email.email'    => 'bad email',
        ]);

        $this->assertFalse($v->isValid());

        $this->assertEquals('min 10', $v->getFirstMessage());

        $this->assertEquals('bad email', $v->getFirstMessage('email'));

        $this->assertCount(5, $v->getFirstMessages());

        $this->assertCount(2, $v->getMessages('randNum'));

        $this->assertCount(10, $v->getMessages());
    }


    public function testDocsExamples()
    {
        $v = V::make([
            'password_r' => 'duv.com-sk.com',
        ], [
            'password_r' => 'url',
        ]);

        $this->assertTrue($v->isValid());

    }
}
