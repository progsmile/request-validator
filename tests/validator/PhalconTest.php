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

        V::setDataProvider('Progsmile\Validator\DbProviders\PhalconAdapter');
    }

    public function testNonUniqueError()
    {
        $validator = V::make(
            ['email'        => $this->nonUniqueEmail],
            ['email'        => 'unique:users'],
            ['email.unique' => 'nonunique']
        );

        $this->assertFalse($validator->passes());

        $errorMessage = $validator->messages('email');

        $this->assertTrue(is_array($errorMessage));
        $this->assertArraySubset(['nonunique'], $errorMessage);
        $this->assertEquals('nonunique', reset($errorMessage));
    }

    public function testIsUniqueEmail()
    {
        $validator = V::make(
            ['email'        => 'some.unique.email@to.check'],
            ['email'        => 'unique:users'],
            ['email.unique' => 'nonunique']
        );

        $this->assertTrue($validator->passes());
        $this->assertEmpty($validator->messages('email'));
    }
}
