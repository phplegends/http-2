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
    }

    public function testQuery()
    {
        $this->assertEquals(['name' => 'Wallace'], $this->uri->getQueryAsArray());

        $this->assertEquals('Wallace', $this->uri->getQuery()->get('name'));

        $this->assertEquals('name=Wallace', $this->uri->getQueryString());
    }


    public function testBuild()
    {
        $uri = clone $this->uri;

        $uri->setPath('login')
            ->setQueryArray(['redirect' => 1])
            ->setHost('128.0.0.1')
            ->setScheme('ftp')
            ->setPort('22');

        $this->assertEquals(
            'ftp://128.0.0.1:22/login?redirect=1',
            $uri->build()
        );


    }
}