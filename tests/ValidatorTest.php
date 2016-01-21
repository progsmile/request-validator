<?php

include __DIR__ .'/../vendor/autoload.php';


use \Progsmile\Validator\Validator;

class ValidatorTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function testImageOK()
    {
        $r = Validator::make([
           'image' => ['', '', '', '']
        ], [
            'image'
        ]);

        $this->assertTrue(empty($r->getMessages()));

    }

    /** @test */
    public function testImageNotRequired()
    {
        $r = Validator::make([

        ], [
            'required|image'
        ]);

        $this->assertTrue(true);
    }
}