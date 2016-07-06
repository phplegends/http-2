<?php

use PHPLegends\Http\ResponseHeaderCollection;
use PHPLegends\Http\CookieJar;

class ResponseHeaderCollectionTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $cookies = new CookieJar();

        $cookies->set('name', 'Wallace');

        $cookies->set('age', '26');

        $this->header = new ResponseHeaderCollection([
            'Connection' => 'Keep-Alive',
            'Accepted'   => ['application/json', 'application/xml']
        ], $cookies);
    }

    public function testGetCookies()
    {
        $this->assertTrue($this->header->getCookies() instanceof CookieJar);

        $this->assertEquals(
            'Wallace',
            $this->header->getCookies()->get('name')->getValue()
        );
    }

    public function setCookies()
    {
        $this->header->setCookies($cookies = new CookieJar());

        $this->assertEquals($cookies, $this->header->getCookies());
    }


}
