<?php

use PHPLegends\Http\Response;
use PHPLegends\Http\ParameterCollection;

class ResponseTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->response = new Response('Hello, world!', 200, [
            'X-ResponseTest' => 'Yep'
        ]);
    }

    public function test()
    {
        $this->assertEquals('Hello, world!', $this->response->getContent());

    }

    public function testHeaderGet()
    {
        $this->assertEquals(
            ['Yep'], 
            $this->response->getHeaders()->get('X-ResponseTest')
        );
    }


    public function testGetStatusCode()
    {
        $this->assertEquals(
            200, 
            $this->response->getStatusCode()
        );
    }
}