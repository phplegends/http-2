<?php

use PHPLegends\Http\HeaderCollection;

class HeaderCollectionTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->header = new HeaderCollection([
            'Connection'     => 'Keep-Alive',
            'Content-Length' => '0',
            'Accepted'       => ['application/json', 'application/xml']
        ]);
    }

    public function testHas()
    {
        $this->assertTrue($this->header->has('Connection'));

        $this->assertTrue($this->header->has('connection'));

        $this->assertTrue($this->header->has('CONNECTION'));
    }

    public function testGetLine()
    {

        $this->assertEquals(
            'application/json, application/xml',
            $this->header->getLine('accepted')
        );
    }

    public function testGet()
    {
        $value = $this->header->get('CONTENT-LENGTH');

        $this->assertEquals([0], $value);
    }

    public function testSet()
    {
        $this->header->set('Content-Type', 'text/html');


        $this->assertEquals(
            'text/html',
            $this->header->getLine('content-type')
        );
    }

    public function testGetOrDefault()
    {
        $this->assertEquals(
            'text/html',
            $this->header->getOrDefault('Content-Type', 'text/html')
        );


        $this->header->set('Content-Type', 'application/json');

        $this->assertEquals(
            ['application/json'],
            $this->header->getOrDefault('Content-Type', 'text/html')
        );
    }

    public function testGetFormatted()
    {
        $data = $this->header->getFormatted();

        $this->assertTrue(is_array($data));

        $this->assertEquals('Connection: Keep-Alive', $data[0]);
    }


}
