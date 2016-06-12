<?php

use PHPLegends\Http\Response;
use PHPLegends\Http\ParameterCollection;

class ParameterCollectionTest extends PHPUnit_Framework_TestCase {

    public function setUp()
    {
        $this->param = new ParameterCollection([
            'name'      => 'Guilherme',
            'age'       => 18,
            'languages' => ['PHP', 'Python', 'Javascript']
        ]);
    }


    public function testGetTypes()
    {
        $this->assertTrue(is_array($this->param->getAsArray('name')));

        $this->assertTrue(is_string($this->param->getAsString('age')));

    }
}