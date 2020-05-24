<?php

namespace Tests\Validator;

use Progsmile\Validator\Validator;

class ValidatorWithCustomRulesTest extends \PHPUnit_Framework_TestCase
{
    private $testData;

    public function setUp()
    {
        $this->testData = [
            'firstname' => 'Daniel',
            'lastname' => 'Smith',
            'email' => 'smith.ajax@gmail.com',
        ];
    }

    public function testCanPassRulesAsArray()
    {
        $validationResult = Validator::make($this->testData, [
            'firstname, lastname' => 'required|alpha|min:2',
            'lastname' => ['min:1', 'max:18'],
            'email' => ['email'],
        ]);

        $this->assertEmpty($validationResult->messages());
    }

    public function testAcceptsCustomRule()
    {
        $testData = array_merge($this->testData, ['number' => 11]);

        $validationResult = Validator::make($testData, [
            'firstname, lastname' => ['\Tests\Validator\Rules\AlwaysFailRule'],
            'number' => ['numeric', 'required', 'between:1,100', '\Tests\Validator\Rules\DividesBy2Rule']
        ]);

        $this->assertTrue($validationResult->fails());
        $this->assertFalse($validationResult->passes());
        $this->assertEquals(3, $validationResult->count());

        $this->assertEquals('Your lastname is invalid', $validationResult->lastname->first());
        $this->assertEquals('Your firstname is invalid', $validationResult->first('firstname'));
        $this->assertEquals('Can\'t divide number by 2', $validationResult->first('number'));
    }
}
