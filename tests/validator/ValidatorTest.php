<?php

use Progsmile\Validator\Validator as V;

class ValidatorTest extends PHPUnit_Framework_TestCase
{
    private $postData;
    private $nonUniqueEmail;

    public function setUp()
    {
        $config = require dirname(__DIR__).'/config/database.php';

        V::setDataProvider('Progsmile\Validator\DbProviders\PdoAdapter');

        try {
            V::setupPDO(
                'mysql:'.
                    "host={$config['host']};".
                    "dbname={$config['dbname']};".
                    'charset=utf8',

                $config['username'],
                $config['password']
            );
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
            'json'            => '[{"foo":"bar"}]',
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

        $this->assertEmpty($validationResult->messages());
    }

    public function testNonUniqueError()
    {
        $validationResult = V::make(['email' => $this->nonUniqueEmail], [
            'email' => 'unique:users',
        ], [
            'email.unique' => 'nonunique',
        ]);

        $this->assertFalse($validationResult->passes());

        $errorMessage = $validationResult->messages('email');

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

        $this->assertCount(2, $validationResult->messages());
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

        $this->assertCount(2, $validationResult->messages());
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

        $this->assertCount(3, $validationResult->messages());
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

        $this->assertFalse($v->passes());
        $this->assertCount(1, $v->messages());
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

        $this->assertEquals('Oops', $validationResult->first('elevatorFloor'));
        $this->assertCount(2, $validationResult->messages());
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

        $this->assertTrue($validationResult->passes());
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

        $this->assertFalse($validationResult->passes());
        $this->assertEquals($errorMessageIdExists, $validationResult->first('id'));
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

        $this->assertFalse($v->passes());

        $this->assertEquals('min 10', $v->first());

        $this->assertEquals('bad email', $v->first('email'));

        $this->assertCount(5, $v->firsts());

        $this->assertCount(2, $v->messages('randNum'));

        $this->assertCount(10, $v->messages());
    }

    public function testUrl()
    {
        $v = V::make([
            'http'  => 'http://duv.com-sk.com',
            'https' => 'https://duv.com-sk.com',
            'ldap'  => 'ldap://[::1]',
            'mail'  => 'mailto:john.do@example.com',
            'news'  => 'news:news.yahoo.com',
        ], [
            'http'  => 'url',
            'https' => 'url',
            'ldap'  => 'url',
            'mail'  => 'url',
            'news'  => 'url',
        ]);
        $this->assertTrue($v->passes());

        $v = V::make([
            'site'  => 'duv.com-sk.com',
        ], [
            'site'  => 'url',
        ]);
        $this->assertFalse($v->passes());

        $v = V::make([
            'site' => '/var/www/files',
        ], [
            'site' => 'url',
        ]);
        $this->assertFalse($v->passes());
    }

    public function testAllTypesOfErrorMessages()
    {
        //default message from file
        $v = V::make([
            'myEmail' => '',
            'name'    => '',
            'php'     => '5.6',
            'mmx'     => '',
            'login'   => 'admin',
            'age'     => '17',
            'age2'    => '20',

        ], [
            'myEmail' => 'email|required|min:5',
            'name'    => 'alpha|required',
            'php'     => 'equals:7',
            'mmx'     => 'required',
            'login'   => 'min:8|required',
            'age'     => 'min:18|numeric|required',
            'age2'    => 'max:18|numeric|required',
        ], [
            'mmx.required' => 'mmx is required',
            'login.min'    => 'minimum 8',
            'age.min'      => 'min 18, sorry',
        ]);

        $this->assertEquals('min 18, sorry', $v->first('age'));

        $this->assertEquals('minimum 8', $v->first('login'));

        $this->assertEquals('mmx is required', $v->first('mmx'));

        //from default messages
        $this->assertEquals('Field age2 must be less than or equal to 18', $v->first('age2'));

        $this->assertEquals('Field php has wrong value', $v->first('php'));
    }

    public function testMessagesFieldValue()
    {
        $v = V::make([
            'myEmail' => 'bobbob.ru',
        ], [
            'myEmail' => 'email',
        ], [
            'myEmail.email' => ':field: Should be Email :value:',
        ]);

        $message = $v->first('email');

        $this->assertEquals('myEmail Should be Email bobbob.ru', $message);
    }

    public function testArray()
    {
        $v = V::make([
            'firstname' => 'Den',
            'lastname'  => 'K',
            'info'      => ['phone' => '+380987365432', 'country' => 'Albania'],
            'email'     => 'ddx@mmx.uk',
            'test'      => [10, 20, 30, 'fail' => 40],
        ], [
            'firstname, lastname'       => 'required|alpha',
            'info[phone]'               => 'required|phoneMask:(+380#########)',
            'info[country]'             => 'required|alpha',
            'email'                     => 'required|email',
            'test[0], test[1], test[2]' => 'numeric|between:1, 100',
            'test[fail]'                => 'required|equals:41',
        ], [
            'test[fail].equals' => '40 need',
        ]);

        $this->assertEquals('40 need', $v->first('test.fail'));

        $this->assertCount(1, $v->messages());

        $this->assertFalse($v->passes());
    }

    public function testSizeRule()
    {
        $v = V::make([
            'testSize' => '123456',
            'failSize' => 'max_int',
        ], [
            'testSize' => 'required|numeric|size:6',
            'failSize' => 'required|size:6',
        ]);

        $this->assertFalse($v->passes());

        $this->assertCount(1, $v->messages());

        $this->assertCount(1, $v->messages('failSize'));
    }

    public function testBetweenNumberAndString()
    {
        $v = V::make([
            'age'      => '33',
            'name'     => 'Umar al-Khayyām',
            'fullname' => 'Armin van Buuren',
        ], [
            'age'            => 'required|numeric|between:30, 40',
            'name, fullname' => 'required|between:2, 20',
        ]);

        $this->assertEmpty($v->messages());
    }

    public function testAllNewMessagesMethodsAndMagicFields()
    {
        $v = V::make([
            'firstName' => 'Armin1',
            'lastName'  => 'Buuren2',
            'userEmail' => 'bob.bob.not-email.com',
            'age'       => 'sercet!!1',
            'valids'    => ['YES', 'Yep'],
        ], [

            'firstName, lastName'  => 'alpha',
            'userEmail'            => 'email',
            'age'                  => 'numeric|min:16',
            'valids[0], valids[1]' => 'required|alpha',
        ], [
            'firstName.alpha' => 'non-alpha-1',   // total 5 errors
            'lastName.alpha'  => 'non-alpha-2',
            'age.numeric'     => 'NaN',
            'userEmail.email' => 'NaE',
        ]);

        //basic test
        $this->assertFalse($v->passes());

        $this->assertEquals($v->first(), 'non-alpha-1');

        $this->assertCount(4, $v->firsts()); //firsts error messages 4

        $this->assertCount(5, $v->messages()); // all count of messages 5

        $this->assertCount(4, $v->raw()); //also has 4 elements for each rule

        //magic test

        $this->assertEquals('non-alpha-1', $v->firstName->first());
        $this->assertEquals('non-alpha-2', $v->lastName->first());
        $this->assertEquals('NaN', $v->age->first());
        $this->assertEquals('NaE', $v->userEmail->first());

        $this->assertContains('non-alpha-2', $v->lastName->messages());
        $this->assertContains('NaN', $v->age->messages());

        $v->add('bob', 'Hello!');

        $this->assertTrue($v->lastName->fails());
        $this->assertFalse($v->lastName->passes());

        $this->assertEquals('Hello!', $v->bob->first());
    }
}
