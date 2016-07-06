<?php

use PHPLegends\Http\Response;
use PHPLegends\Http\ParameterCollection;

class ParameterCollectionTest extends PHPUnit_Framework_TestCase {

    public function setUp()
    {
        $this->param = new ParameterCollection([
            'name'        => 'Guilherme',
            'age'         => 18,
            'card_number' => '0555',
            'languages'   => ['PHP', 'Python', 'Javascript'],
            'empty'       => '',
            'empty_array'  => []
        ]);
    }
    public function testGetAsString()
    {
        $this->assertTrue(is_string($this->param->getAsString('age')));
    }

    public function testGetAsArray()
    {
        $this->assertTrue(is_array($this->param->getAsArray('name')));
    }

    public function testGetAsFloat()
    {
        $this->assertTrue(
            is_float($this->param->getAsFloat('age'))
        );
    }

    public function testGetAsInteger()
    {
        $this->assertTrue(
            is_int($this->param->getAsInt('card_number'))
        );
    }

    public function testIsEmptyOrNullString()
    {
        $this->assertTrue($this->param->isEmptyOrNullString('empty'));

        $this->assertTrue($this->param->isEmptyOrNullString('null'));

        $this->assertFalse($this->param->isEmptyOrNullString('empty_array'));
    }


    public function testOffsetGet()
    {
        $this->assertEquals('PHP', $this->param['languages'][0]);

        $this->assertEquals('Python', $this->param['languages'][1]);
    }

    public function testOnly()
    {
        $this->assertCount(2, $only = $this->param->only(['languages', 'age']));

        $this->assertTrue($only instanceof ParameterCollection);

    }
}
