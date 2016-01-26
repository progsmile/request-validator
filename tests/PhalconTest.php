<?php

use Progsmile\Validator\Validator as V;

class PhalconTest extends PHPUnit_Framework_TestCase
{
    private $nonUniqueEmail;

    public function setUp()
    {
        if (!extension_loaded('phalcon')) {
            $this->markTestSkipped('Warning: phalcon extension is not loaded');
        }

        $this->nonUniqueEmail = 'dd@dd.dd';
    }

    public function testNonUniqueError()
    {
        V::setDataProvider('Progsmile\Validator\DbProviders\PhalconORM');
        $validationResult = V::make(
            ['email' => $this->nonUniqueEmail],
            ['email' => 'unique:users'],
            ['email.unique' => 'nonunique']
        );

        $this->assertFalse($validationResult->isValid());

        $errorMessage = $validationResult->getMessages('email');

        $this->assertEquals('nonunique', reset($errorMessage));
    }
}
