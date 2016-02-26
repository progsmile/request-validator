<?php

use \Progsmile\Validator\Validator as V;

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
            ['email'        => 'unique:users'],
            ['email.unique' => 'nonunique']
        );

        $this->assertFalse($validationResult->passes());

        $errorMessage = $validationResult->messages('email');

        $this->assertTrue(is_array($errorMessage));
        $this->assertArraySubset(['nonunique'], $errorMessage);
        $this->assertEquals('nonunique', reset($errorMessage));
    }

    public function testIsUniqueEmail()
    {
        V::setDataProvider('Progsmile\Validator\DbProviders\PhalconORM');
        $validationResult = V::make(
            ['email' => 'some.unique.email@to.check'],
            ['email'        => 'unique:users'],
            ['email.unique' => 'nonunique']
        );

        $this->assertTrue($validationResult->passes());
        $this->assertEmpty($validationResult->messages('email'));
    }
}
