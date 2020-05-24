<?php

namespace Tests\Validator;

use Progsmile\Validator\Helpers\DataFilter;

class DataFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testGroupsDataToArrayWithStringRules()
    {
        $result = DataFilter::filterRules([
            'firstname, lastname, age, number' => 'required',
            'firstname, lastname' => 'alpha',
            'email' => 'email',
            'age, number' => 'required|integer',
            'number' => 'between:1,10'
        ]);

        $expected = [
            'firstname' => ['required', 'alpha'],
            'lastname' => ['required', 'alpha'],
            'age' => ['required', 'integer'],
            'number' => ['required', 'integer', 'between:1,10'],
            'email' => ['email'],
        ];

        $this->assertArraysEqual($result, $expected);
    }

    public function testGroupsDataToArrayWithArrayRules()
    {
        $result = DataFilter::filterRules([
            'firstname, lastname' => ['required'],
            'age, number' => ['required', 'integer', 'required'],
            'number' => ['between:1,10'],
            'data' => ['json', 'required']
        ]);

        $expected = [
            'firstname' => ['required'],
            'lastname' => ['required'],
            'age' => ['required', 'integer'],
            'number' => ['required', 'integer', 'between:1,10'],
            'data' => ['json', 'required'],
        ];

        $this->assertArraysEqual($result, $expected);
    }

    protected function assertArraysEqual(array $result, array $expected)
    {
        foreach (array_keys($expected) as $field) {
            $this->assertArrayHasKey($field, $result);
            $this->assertEquals(array_values($result[$field]), array_values($expected[$field]));
        }

        $this->assertSameSize($result, $expected);
    }
}
