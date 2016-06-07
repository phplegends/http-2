<?php

use PHPLegends\Http\ServerRequest;

class ServerRequestTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->request = ServerRequest::createFromGlobals();
    }

    public function test()
    {
        $this->request->getBody();
    }
}