<?php

use PHPLegends\Http\JsonResponse;

class JsonResponseTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->json = new JsonResponse(['name' => 'value']);
    }

    public function testGetContents()
    {
        $this->assertEquals('{"name":"value"}', $this->json->getContent());
    }

    public function testStatusCode()
    {
        $this->assertEquals(200, $this->json->getStatusCode());
    }

    public function testContentType()
    {
        $this->assertEquals('application/json', $this->json->getHeaders()->getLine('content-type'));
    }

    
}