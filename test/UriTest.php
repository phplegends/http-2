<?php

use PHPLegends\Http\Uri;

class UriTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->uri = new Uri('http://localhost/path?name=Wallace');
    }

    public function testPath()
    {
        $this->assertEquals('/path', $this->uri->getPath());

        var_dump($this->uri->build());
    }
}